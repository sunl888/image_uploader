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
        $this->publishes([
            __DIR__ . '/../config/image_upload.php' => config_path('image_upload.php')
        ]);

        $this->loadMigrationsFrom(__DIR__ . '../migrations');


        Route::get('img/{image}', function ($path) {
            $config = config('image_upload');
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
        })->name('image');

        Route::post('imageupload', function () {
            if (Request::hasFile('image')) {
                $data['result'] = app(Imageupload::class)->upload(Request::file('image'));
            }
            return new Response($data ?? []);
        });
    }
}