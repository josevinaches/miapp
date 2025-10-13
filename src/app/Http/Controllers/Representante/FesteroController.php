<?php

namespace App\Http\Controllers\Representante;

use App\Http\Controllers\Controller;
use App\Models\Comparsa;
use App\Models\Festero;
use App\Models\Tutor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Rules\ValidDniNie;
use Carbon\Carbon;
use App\Http\Requests\StoreFesteroRequest;
use App\Models\Revision;

class FesteroController extends Controller
{

    // obtiene comparsa_id del representante logueado (abort si no tiene)
    private function comparsaIdOrFail(Request $request): int
    {
        $comparsa = $request->user()->comparsa;
        abort_if(!$comparsa, 403, 'No tienes comparsa asignada.');
        return (int) $comparsa->id;
    }



    public function index(Request $request)
    {
        // Comparsa del representante (lanza 403 si no tiene)
        $comparsaId = $this->comparsaIdOrFail($request);

        // Filtros básicos
        $q = trim((string) $request->get('q', ''));
        $ejercicio = (int) ($request->input('ejercicio') ?? date('Y'));

        // Listado con eager load (sin N+1) y buscador (incluye apellidos)
        $festeros = Festero::where('comparsa_id', $comparsaId)
            ->when($q !== '', function ($query) use ($q) {
                $like = '%' . $q . '%';
                $query->where(function ($sub) use ($like) {
                    $sub->where('nombre', 'like', $like)
                        ->orWhere('primer_apellido', 'like', $like)
                        ->orWhere('segundo_apellido', 'like', $like)
                        ->orWhere('dni', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('telefono', 'like', $like);
                });
            })
            ->with([
                // Tutores: solo campos necesarios
                'tutores:id,festero_id,nombre',
                // Cuotas: solo del ejercicio actual (y solo flags de pago)
                'cuotas' => fn($q2) => $q2->where('ejercicio', $ejercicio)
                    ->select('id', 'festero_id', 'pagada_asociacion', 'pagada_fester'),
            ])
            ->orderBy('nombre')
            ->orderBy('primer_apellido')
            ->paginate(10)
            ->withQueryString();

        // Última revisión de ese ejercicio para esta comparsa (para mostrar estado en la vista)
        $revision = Revision::where('comparsa_id', $comparsaId)
            ->where('ejercicio', $ejercicio)
            ->latest()
            ->first();

        return view('representante.festeros.index', compact('festeros', 'q', 'ejercicio', 'revision'));
    }



    public function create(Request $request)
    {
        $this->comparsaIdOrFail($request);
        return view('representante.festeros.create');
    }

    public function store(Request $request)
    {
        $comparsaId = $this->comparsaIdOrFail($request);

        // Normaliza DNI
        $dni = strtoupper(preg_replace('/\s|-/', '', (string) $request->input('dni')));
        $request->merge(['dni' => $dni]);

        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:255'],
            'primer_apellido'  => ['required', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'dni'              => [
                'nullable',
                'string',
                'max:15',
                new ValidDniNie,
                Rule::unique('festeros', 'dni')->where(fn($q) => $q->where('comparsa_id', $comparsaId))
            ],
            'email'            => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('festeros', 'email')->where(fn($q) => $q->where('comparsa_id', $comparsaId))
            ],
            'telefono'         => ['nullable', 'string', 'max:50'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'trabuco'          => ['nullable', 'boolean'],
            'embarque'         => ['nullable', 'boolean'],
        ], [
            'dni.unique'   => 'Este DNI/NIE ya está registrado en tu comparsa.',
            'email.unique' => 'Este email ya está registrado en tu comparsa.',
        ]);

        $ejercicio = (int) ($request->input('ejercicio') ?? now()->year);
        if ($this->isMenorPara($data['fecha_nacimiento'] ?? null, $ejercicio)) {
            return redirect()
                ->route('representante.menores.create')
                ->withInput()
                ->with('menor_warning', 'La fecha indica menor de edad. Usa el formulario de Menores para registrarlo con su tutor.');
        }

        // Fuerza comparsa y normaliza checkboxes
        $data['comparsa_id'] = $comparsaId;
        $data['trabuco']  = (bool)($data['trabuco'] ?? false);
        $data['embarque'] = (bool)($data['embarque'] ?? false);

        Festero::create($data);

        return redirect()->route('representante.festeros.index')->with('status', 'Festero creado.');
    }

    public function update(Request $request, Festero $festero)
    {
        $comparsaId = $this->comparsaIdOrFail($request);
        abort_if($festero->comparsa_id !== $comparsaId, 403);

        // Normaliza DNI
        $dni = strtoupper(preg_replace('/\s|-/', '', (string) $request->input('dni')));
        $request->merge(['dni' => $dni]);

        $data = $request->validate([
            'nombre'           => ['required', 'string', 'max:255'],
            'primer_apellido'  => ['required', 'string', 'max:255'],
            'segundo_apellido' => ['nullable', 'string', 'max:255'],
            'dni'              => [
                'nullable',
                'string',
                'max:15',
                new ValidDniNie,
                Rule::unique('festeros', 'dni')
                    ->ignore($festero->id)
                    ->where(fn($q) => $q->where('comparsa_id', $comparsaId))
            ],
            'email'            => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('festeros', 'email')
                    ->ignore($festero->id)
                    ->where(fn($q) => $q->where('comparsa_id', $comparsaId))
            ],
            'telefono'         => ['nullable', 'string', 'max:50'],
            'fecha_nacimiento' => ['nullable', 'date'],
            'trabuco'          => ['nullable', 'boolean'],
            'embarque'         => ['nullable', 'boolean'],
        ], [
            'dni.unique'   => 'Este DNI/NIE ya está registrado en tu comparsa.',
            'email.unique' => 'Este email ya está registrado en tu comparsa.',
        ]);

        // Si es menor en el ejercicio, se fuerzan flags a false por seguridad
        $ejercicio = (int) ($request->input('ejercicio') ?? now()->year);
        if ($this->isMenorPara($data['fecha_nacimiento'] ?? null, $ejercicio)) {
            $data['trabuco'] = false;
            $data['embarque'] = false;
        } else {
            $data['trabuco']  = (bool)($data['trabuco'] ?? false);
            $data['embarque'] = (bool)($data['embarque'] ?? false);
        }

        $festero->update($data);

        return redirect()->route('representante.festeros.index')->with('status', 'Festero actualizado.');
    }


    public function destroy(Request $request, Festero $festero)
    {
        $comparsaId = $this->comparsaIdOrFail($request);
        abort_if($festero->comparsa_id !== $comparsaId, 403);

        $festero->delete();

        return redirect()->route('representante.festeros.index')->with('status', 'Festero eliminado.');
    }

    // en FesteroController
    private function normalizeDni(?string $v): ?string
    {
        if ($v === null) return null;
        return strtoupper(preg_replace('/\s|-/', '', $v));
    }

    public function edit(\Illuminate\Http\Request $request, \App\Models\Festero $festero)
    {
        // Solo puede editar festeros de su propia comparsa
        $comparsa = $request->user()->comparsa; // relación User->comparsa (por user_id en comparsas)
        abort_if(!$comparsa || $festero->comparsa_id !== $comparsa->id, 403);

        return view('representante.festeros.edit', compact('festero'));
    }

    private function isMenorPara(string|null $fecha, int $ejercicio): bool
    {
        if (empty($fecha)) return false;
        $cutoff = Carbon::createFromDate($ejercicio, 7, 24)->endOfDay();
        // Menor si su 18º cumpleaños es posterior al corte
        return Carbon::parse($fecha)->gt($cutoff->copy()->subYears(18));
    }

    public function createMinor(Request $request)
    {
        $ejercicio = (int) ($request->input('ejercicio') ?? date('Y'));
        return view('representante.menores.create', compact('ejercicio'));
    }

    public function storeMinor(Request $request)
    {
        $ejercicio = (int) ($request->input('ejercicio') ?? date('Y'));

        // Validación combinada
        $dataFestero = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'dni' => ['nullable', 'string', 'max:15', 'unique:festeros,dni', new \App\Rules\ValidDniNie],
            'email' => ['nullable', 'email', 'max:255', 'unique:festeros,email'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'fecha_nacimiento' => ['required', 'date'], // requerido para menores
        ]);

        if (!$this->isMenorPara($dataFestero['fecha_nacimiento'], $ejercicio)) {
            return back()->withErrors(['fecha_nacimiento' => 'La fecha no corresponde a menor de edad para el ejercicio. Usa el alta normal.'])->withInput();
        }

        $dataTutor = $request->validate([
            'tutor.nombre' => ['required', 'string', 'max:255'],
            'tutor.dni' => ['required', 'string', 'max:15', new \App\Rules\ValidDniNie],
            'tutor.parentesco' => ['required', 'string', 'max:100'],
            'tutor.telefono' => ['nullable', 'string', 'max:50'],
            'tutor.email' => ['nullable', 'email', 'max:255'],
        ]);

        // Normaliza DNI festero
        if (!empty($dataFestero['dni'])) {
            $dataFestero['dni'] = strtoupper(preg_replace('/\s|-/', '', $dataFestero['dni']));
        }

        $comparsaId = Comparsa::where('user_id', Auth::id())->value('id');
        if (!$comparsaId) {
            abort(403, 'No tienes comparsa asignada.');
        }

        DB::transaction(function () use ($comparsaId, $dataFestero, $dataTutor) {
            /** @var Festero $festero */
            $festero = Festero::create(array_merge($dataFestero, [
                'comparsa_id' => $comparsaId,
            ]));

            Tutor::create([
                'festero_id'  => $festero->id,
                'nombre'      => $dataTutor['tutor']['nombre'],
                'dni'         => strtoupper(preg_replace('/\s|-/', '', $dataTutor['tutor']['dni'])),
                'parentesco'  => $dataTutor['tutor']['parentesco'],
                'telefono'    => $dataTutor['tutor']['telefono'] ?? null,
                'email'       => $dataTutor['tutor']['email'] ?? null,
            ]);
        });



        return redirect()->route('representante.festeros.index')->with('status', 'Menor y tutor creados correctamente.');
    }
}
