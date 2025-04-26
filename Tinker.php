<?php
# ======================= Please Don't Remove this section of code ===========================
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
# ======================= Please Don't Remove this section of code ===========================

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

// Enable query logging
DB::enableQueryLog();

// Fetch customers using lazy loading
$customers = Customer::cursor();

// Process customers
foreach ($customers as $customer) {
}

// Get the query log
$queries = DB::getQueryLog();

// Calculate total execution time and number of queries
$totalTime = 0;
$queryCount = count($queries);

foreach ($queries as $query) {
    $totalTime += $query['time'];
}

// Display query statistics
echo "\n";
echo "Total Queries Executed: {$queryCount}\n";
echo "Total Execution Time: {$totalTime} ms\n";
echo "\n";

// Optionally, display detailed query log
echo "Query Log:\n";
foreach ($queries as $index => $query) {
    echo "Query #" . ($index + 1) . ":\n";
    echo "  SQL: " . $query['query'] . "\n";
    echo "  Bindings: " . json_encode($query['bindings']) . "\n";
    echo "  Time: " . $query['time'] . " ms\n";
    echo "\n";
}