
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::get('/_dbcheck', function () {
    $conn = Config::get('database.default');
    $cfg  = Config::get("database.connections.$conn");

    $db    = DB::select('select database() as db');
    $dbNow = $db[0]->db ?? null;

    $hasUsers = Schema::hasTable('users');

    $hasUsersInfo = null;
    try {
        $res = DB::select("
            SELECT COUNT(*) AS c
            FROM information_schema.tables
            WHERE table_schema = ? AND table_name = 'users'
            LIMIT 1
        ", [$dbNow]);
        $hasUsersInfo = (int)($res[0]->c ?? 0) > 0;
    } catch (\Throwable $e) {
        $hasUsersInfo = 'error: '.$e->getMessage();
    }

    $usersCount = null; $countError = null;
    try {
        $usersCount = DB::scalar('SELECT COUNT(*) FROM users');
    } catch (\Throwable $e) {
        $countError = $e->getMessage();
    }

    return response()->json([
        'connection' => $conn,
        'host'       => $cfg['host'] ?? null,
        'username'   => $cfg['username'] ?? null,
        'database'   => $dbNow,
        'schema_has_users' => $hasUsers,
        'info_schema_has_users' => $hasUsersInfo,
        'users_count' => $usersCount,
        'users_count_error' => $countError,
    ]);
});
