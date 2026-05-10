<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{PengambilanHeader, PeminjamanHeader};
use Illuminate\Support\Facades\DB;

$period = now()->subDays(30);

$pengambilan = PengambilanHeader::select('users.bidang', DB::raw('COUNT(*) as total'))
    ->join('users', 'users.id', '=', 'pengambilan_headers.user_id')
    ->where('pengambilan_headers.status', 'approved')
    ->where('pengambilan_headers.approved_at', '>=', $period)
    ->whereNotNull('users.bidang')
    ->groupBy('users.bidang');

$peminjaman = PeminjamanHeader::select('users.bidang', DB::raw('COUNT(*) as total'))
    ->join('users', 'users.id', '=', 'peminjaman_headers.user_id')
    ->where('peminjaman_headers.status', 'approved')
    ->where('peminjaman_headers.approved_at', '>=', $period)
    ->whereNotNull('users.bidang')
    ->groupBy('users.bidang');

$sqlUnion = "({$pengambilan->toSql()} UNION ALL {$peminjaman->toSql()})";
echo "Union SQL: $sqlUnion" . PHP_EOL;

$union = DB::table(DB::raw($sqlUnion))
    ->mergeBindings($pengambilan->getQuery())
    ->mergeBindings($peminjaman->getQuery())
    ->select('bidang', DB::raw('SUM(total) as grand_total'))
    ->groupBy('bidang')
    ->orderByDesc('grand_total')
    ->get();

echo "Result:" . PHP_EOL;
print_r($union->toArray());
