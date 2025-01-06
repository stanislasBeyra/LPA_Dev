<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\S3\S3Client;

class MinioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('minio', function ($app) {
            return new S3Client([
                'version' => 'latest',
                'region'  => env('MINIO_REGION', 'us-east-1'),
                'endpoint' => env('MINIO_ENDPOINT', 'https://bucket-production-5e4b.up.railway.app:443'),
                'use_path_style_endpoint' => true,
                'credentials' => [
                    'key'    => env('MINIO_ACCESS_KEY'),
                    'secret' => env('MINIO_SECRET_KEY'),
                ],
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
