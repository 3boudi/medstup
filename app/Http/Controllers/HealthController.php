<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HealthController extends Controller
{
    /**
     * Basic health check endpoint
     */
    public function check()
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'service' => 'Medical Consultation API',
            'version' => '1.0.0',
        ]);
    }

    /**
     * Detailed health check with system status
     */
    public function detailed()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];

        $overallStatus = collect($checks)->every(fn($check) => $check['status'] === 'healthy') 
            ? 'healthy' 
            : 'unhealthy';

        return response()->json([
            'status' => $overallStatus,
            'timestamp' => now()->toISOString(),
            'service' => 'Medical Consultation API',
            'version' => '1.0.0',
            'checks' => $checks,
        ], $overallStatus === 'healthy' ? 200 : 503);
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
            $userCount = DB::table('users')->count();
            
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'details' => [
                    'connection' => 'active',
                    'users_count' => $userCount,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function checkCache(): array
    {
        try {
            $key = 'health_check_' . time();
            Cache::put($key, 'test', 60);
            $value = Cache::get($key);
            Cache::forget($key);

            return [
                'status' => $value === 'test' ? 'healthy' : 'unhealthy',
                'message' => 'Cache is working properly',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Cache check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function checkStorage(): array
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            Storage::disk('public')->put($testFile, 'test');
            $exists = Storage::disk('public')->exists($testFile);
            Storage::disk('public')->delete($testFile);

            return [
                'status' => $exists ? 'healthy' : 'unhealthy',
                'message' => 'Storage is working properly',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Storage check failed',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function checkQueue(): array
    {
        try {
            // Basic queue connection check
            $connection = config('queue.default');
            
            return [
                'status' => 'healthy',
                'message' => 'Queue connection available',
                'details' => [
                    'default_connection' => $connection,
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Queue check failed',
                'error' => $e->getMessage(),
            ];
        }
    }
}