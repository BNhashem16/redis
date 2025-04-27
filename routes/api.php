<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RedisController;
use App\Jobs\TestJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

Route::get('customers', [CustomerController::class, 'getCustomersWithoutRedis']);
Route::get('customers/redis', [CustomerController::class, 'getCustomersWithRedis']);
Route::get('customers/without-redis/{id}', [CustomerController::class, 'getCustomerWithoutRedis']);
Route::get('customers/with-redis/{id}', [CustomerController::class, 'getCustomerWithRedis']);
// Route::resource('customers', CustomerController::class);
Route::get('/check-redis', function () {
	try {
		Redis::set('test_connection', 'success');
		$value = Redis::get('test_connection');
		return response()->json([
			'status' => 'Connected ✅',
			'value' => $value,
		]);
	} catch (\Exception $e) {
		return response()->json([
			'status' => 'Failed ❌',
			'error' => $e->getMessage(),
		]);
	}
});

Route::get('dispatch-job', function () {
	dispatch(new TestJob());
	return 'Job dispatched!';
});
Route::get('videos/{id}/get', [RedisController::class, 'get']);
Route::get('videos/{id}/incr', [RedisController::class, 'incr']);
