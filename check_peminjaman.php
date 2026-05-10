<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PeminjamanHeader;

$period = now()->subDays(30);
echo "Period from: " . $period . PHP_EOL;

$approved = PeminjamanHeader::where('status','approved')
    ->where('approved_at','>=',$period)
    ->get(['id','nomor_transaksi','approved_at','status']);

echo "Approved in last 30 days: " . $approved->count() . PHP_EOL;
foreach ($approved as $p) {
    echo "  #{$p->id} {$p->nomor_transaksi} - {$p->approved_at} (status: {$p->status})" . PHP_EOL;
}

// Also check: all approved with their dates
$allApproved = PeminjamanHeader::where('status','approved')->get(['id','nomor_transaksi','approved_at']);
echo "All approved peminjaman:" . PHP_EOL;
foreach ($allApproved as $p) {
    echo "  #{$p->id} {$p->nomor_transaksi} - {$p->approved_at}" . PHP_EOL;
}
