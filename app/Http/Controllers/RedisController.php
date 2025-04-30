<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

	public function logActivitySet(Request $request): JsonResponse
    {
        $timestamp = now()->toDateTimeString();
        $ip = $request->ip();

        $logLine = "[{$timestamp}] زيارة من المستخدم: {guest} - IP: {$ip}\n";

        Redis::append("user:activity:log", $logLine);

        return response()->json([
            'message' => 'تم تسجيل النشاط بنجاح',
            'logged' => $logLine,
        ]);
    }

	public function logActivityGet(Request $request): JsonResponse
    {
		$log = Redis::get('user:activity:log');
		return response()->json($log);
    }
	
	public function decr(int $videoId): JsonResponse
    {
		$downloads = Redis::decr("videos.$videoId.downloads");
        
        return response()->json($downloads);
    }

}
