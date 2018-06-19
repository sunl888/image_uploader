<?php
/**
 * Created by PhpStorm.
 * User: sunlong
 * Date: 18-6-16
 * Time: 下午10:10
 */

namespace Wqer1019\ImageUploader;


use Illuminate\Http\Response;
use League\Glide\ServerFactory;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Request;
use League\Glide\Responses\LaravelResponseFactory;

class ImageUploadServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Imageupload::class, function ($app) {
            return new Imageupload(new ImageManager(), config('image_upload', []));
        });
    }

    public function boot()
    {
        $root = dirname(__DIR__);

        if (!file_exists(config_path('image_upload.php'))) {
            $this->publishes([
                $root . '/../config/image_upload.php' => config_path('image_upload.php'),
            ], 'config');
        }
        if (!class_exists('CreateImagesTable')) {
            $datePrefix = date('Y_m_d_His');
            $this->publishes([
                $root . '/../migrations/create_images_table.php' => database_path("/migrations/{$datePrefix}_create_images_table.php"),
            ], 'migrations');
        }

        $this->registerRoute();
    }

    public function registerRoute()
    {
        $config = config('image_upload');

        // 顯示圖片
        Route::get($config['base_url'] . '/{image}', function ($path) use ($config) {
            $server = ServerFactory::create([
                'response' => new LaravelResponseFactory(app('request')),
                'source' => Storage::disk($config['disk'])->getDriver(),
                'cache' => Storage::disk($config['cache_disk'])->getDriver(),
                'source_path_prefix' => $config['path'],
                'cache_path_prefix' => $config['cache_path'],
                'base_url' => $config['base_url'],
                'presets' => $config['presets'],
                'defaults' => $config['default_style']
            ]);
            return $server->getImageResponse($path, request()->all());
        })->name($config['route_name']);

        // 上傳圖片
        Route::post('imageupload', function () use ($config) {
            if (Request::hasFile($config['upload_key'])) {
                $data['result'] = app(Imageupload::class)->upload(Request::file($config['upload_key']));
            }
            return new Response($data ?? []);
        });
    }
}