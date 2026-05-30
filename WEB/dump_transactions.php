<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$transactions = App\Models\Transaction::orderBy('date_time', 'asc')->get();

$payload = $transactions->map(fn ($t) => $t->toFlaskFormat())->values()->toArray();

$targetPath = dirname(__DIR__) . '/ML/data/db_transactions.json';
file_put_contents($targetPath, json_encode($payload, JSON_PRETTY_PRINT));

echo "Successfully dumped " . count($payload) . " transactions to $targetPath\n";
