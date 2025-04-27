<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
	public function getCustomersWithoutRedis(): JsonResponse
    {
        $startTime = microtime(true);
        
        $customers = Customer::all();
        
        $executionTime = microtime(true) - $startTime;
        
        return response()->json([
            'data' => $customers,
            'meta' => [
                'execution_time' => $executionTime,
                'count' => count($customers),
                'source' => 'database'
            ]
        ]);
    }

	public function getCustomersWithRedis(): JsonResponse
	{
		$cacheKey = 'customers_data_v3';
		$cacheDuration = now()->addMinutes(30);
		
		if (Cache::has($cacheKey)) {
			return response()->json([
				'data' => Cache::get($cacheKey),
				'source' => 'redis_cache'
			]);
		}
		
		$customers = Customer::query()
			->select(['id', 'name', 'subscription_end_date'])
			->get()
			->map(function ($customer) {
				return [
					'id' => $customer->id,
					'name' => $customer->name,
					'subscription_end_date' => $customer->subscription_end_date->toDateTimeString()
				];
			})
			->toArray();
		
		Cache::put($cacheKey, $customers, $cacheDuration);
		
		return response()->json([
			'data' => $customers,
			'source' => 'database'
		]);
	}

	public function getCustomerWithoutRedis(int $customerId): JsonResponse
    {
        $startTime = microtime(true);
        $customer = Customer::findOrFail($customerId);
        $executionTime = microtime(true) - $startTime;
        return response()->json([
            'data' => [
                'id' => $customer->id,
                'name' => $customer->name,
            ],
            'execution_time' => $executionTime
        ]);
    }

    public function getCustomerWithRedis(int $customerId): JsonResponse
    {
        $startTime = microtime(true);
        $cacheKey = 'customer:' . $customerId;
        $cachedCustomer = Redis::get($cacheKey);
        if ($cachedCustomer) {
            $customerData = json_decode($cachedCustomer, true);
            $executionTime = microtime(true) - $startTime;
            return response()->json([
                'data' => $customerData,
                'execution_time' => $executionTime,
                'source' => 'redis'
            ]);
        }
        
        $customer = Customer::findOrFail($customerId);
        $customerData = [
            'id' => $customer->id,
            'name' => $customer->name,
        ];
        Redis::incr
        Redis::setex($cacheKey, 3600, json_encode($customerData));
        $executionTime = microtime(true) - $startTime;
        return response()->json([
            'data' => $customerData,
            'execution_time' => $executionTime,
            'source' => 'database'
        ]);
    }

    public function index(): JsonResponse
    {
		$customers = Cache::remember('all_customers', 60, function () {
			return Customer::get();
		});

		return response()->json($customers);
    }

    // public function create()
    // {
    //     //
    // }

    // public function store(StoreCustomerRequest $request)
    // {
    //     //
    // }

    // public function show(Customer $customer)
    // {
    //     //
    // }

    // public function edit(Customer $customer)
    // {
    //     //
    // }

    // public function update(UpdateCustomerRequest $request, Customer $customer)
    // {
    //     //
    // }

    // public function destroy(Customer $customer)
    // {
    //     //
    // }
}
