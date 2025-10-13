<?php
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

require __DIR__."/../vendor/autoload.php";
$app = require_once __DIR__."/../bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$kernel->handle($request);

$default = Config::get("database.default");
$cfg     = Config::get("database.connections.$default");

$out = [
  "default" => $default,
  "config"  => $cfg,
];

try {
  $out["db_now"] = DB::select("select database() db")[0]->db ?? null;
  $out["server"] = DB::select("select @@hostname as hostname, @@port as port, @@version as version")[0] ?? null;
  $out["tables"] = array_map(fn($r)=>array_values((array)$r)[0], DB::select("SHOW TABLES"));
} catch (\Throwable $e) {
  $out["err"] = $e->getMessage();
}

header("Content-Type: application/json");
echo json_encode($out);
