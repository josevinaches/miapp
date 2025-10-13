@php
  use Carbon\Carbon;
  $fmt = fn($d) => $d ? Carbon::parse($d)->format('d/m/Y') : '—';
  $esMenor = function($fecha) use ($cutoff) {
    if (!$fecha) return false;
    return Carbon::parse($fecha)->gt($cutoff->copy()->subYears(18));
  };
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Festeros {{ $ejercicio }} - {{ $comparsa->nombre }}</title>
  <style>
    *{ font-family: DejaVu Sans, sans-serif; }
    body{ font-size:11px; color:#111; margin:20px; }
    h1{ font-size:15px; margin:0 0 6px; }
    h2{ font-size:12px; margin:10px 0 6px; }
    .muted{ color:#777; }
    .small{ font-size:10px; }
    .tiny{ font-size:9px; }
    table{ width:100%; border-collapse:collapse; table-layout:fixed; }
    th,td{ border:1px solid #ddd; padding:5px; vertical-align:top; word-wrap:break-word; }
    th{ background:#f3f3f3; text-align:left; }
    /* Evitar (en lo posible) cortes de fila entre páginas */
    tr{ page-break-inside: avoid; }
    .badge{ display:inline-block; padding:2px 6px; border-radius:4px; font-size:9px; border:1px solid #ddd;}
    .ok{ background:#e8f7e9; border-color:#bfe3c2; color:#166534; }
    .warn{ background:#fff3cd; border-color:#ffe69c; color:#7a5d00; }
    .section{ margin-top:8px; }
    /* Anchuras para que todo quepa y envuelva bien */
    .w-idx{ width:24px; }
    .w-nom{ width:160px; }
    .w-doc{ width:90px; }
    .w-contact{ width:130px; }
    .w-date{ width:80px; }
    .w-menor{ width:60px; }
    .w-pagos{ width:55px; }
    .w-tutores{ width:210px; }
    .tutores{ line-height:1.25; }
    .tutor{ display:block; margin-bottom:2px; }
  </style>
</head>
<body>
  <h1>Festeros {{ $ejercicio }} — {{ $comparsa->nombre }}</h1>
  <p class="muted small">Generado: {{ now()->format('d/m/Y H:i') }}</p>

  <div class="section">
    <h2>Listado completo (adultos y menores)</h2>
    <table>
      <colgroup>
        <col class="w-idx"><col class="w-nom"><col class="w-doc"><col class="w-contact">
        <col class="w-date"><col class="w-menor"><col class="w-pagos"><col class="w-pagos"><col class="w-tutores">
      </colgroup>
      <thead>
        <tr>
          <th>#</th>
          <th>Nombre y apellidos</th>
          <th>DNI</th>
          <th>Email / Teléfono</th>
          <th>Nacimiento</th>
          <th>Menor</th>
          <th>Pago A</th>
          <th>Pago F</th>
          <th>Tutor/es</th>
        </tr>
      </thead>
      <tbody>
      @foreach($festeros as $i => $f)
        @php
          $menor = $esMenor($f->fecha_nacimiento);
          $cq = $f->relationLoaded('cuotas') ? $f->cuotas->first() : null;
          $pagoA = $cq ? (bool)$cq->pagada_asociacion : null;
          $pagoF = $cq ? (bool)$cq->pagada_fester     : null;
        @endphp
        <tr>
          <td>{{ $i+1 }}</td>
          <td>
            {{ $f->nombre }} {{ $f->primer_apellido }} {{ $f->segundo_apellido }}
          </td>
          <td>{{ $f->dni ?? '—' }}</td>
          <td>
            {{ $f->email ?? '—' }}<br>
            {{ $f->telefono ?? '—' }}
          </td>
          <td>{{ $fmt($f->fecha_nacimiento) }}</td>
          <td>
            @if($menor)
              <span class="badge warn">👶 Menor</span>
            @else
              <span class="badge ok">Adulto</span>
            @endif
          </td>
          <td>
            @if($pagoA === true)<span class="badge ok">Pagada</span>
            @elseif($pagoA === false)<span class="badge warn">Pendiente</span>
            @else — @endif
          </td>
          <td>
            @if($pagoF === true)<span class="badge ok">Pagada</span>
            @elseif($pagoF === false)<span class="badge warn">Pendiente</span>
            @else — @endif
          </td>
          <td>
            @if($menor && $f->tutores && $f->tutores->count())
              <div class="tutores tiny">
                @foreach($f->tutores as $t)
                  <span class="tutor">• {{ $t->nombre }} ({{ $t->telefono ?? '—' }} / {{ $t->email ?? '—' }})</span>
                @endforeach
              </div>
            @else
              <span class="tiny">—</span>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>
