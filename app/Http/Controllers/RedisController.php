<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RedisController extends Controller
{
	public function get(int $videoId): JsonResponse
    {
		$downloads = Redis::get("videos.$videoId.downloads");
        return response()->json($downloads);
    }

	public function incr(int $videoId): JsonResponse
    {
		$downloads = Redis::incr("videos.$videoId.downloads");
        
        return response()->json($downloads);
    }

}
