<?php

namespace App\Providers;

use Alipay\EasySDK\Kernel\Factory as AlipayFactory;
use Alipay\EasySDK\Kernel\Payment as AlipayPayment;
use AlphaSnow\Flysystem\Aliyun\AliyunFactory;
use EasyWeChat\Factory as WechatFactory;
use EasyWeChat\Payment\Application as WechatPayment;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use JPush\Client as JPushClient;
use League\Flysystem\Filesystem;
use Overtrue\EasySms\EasySms;
use Overtrue\Flysystem\Cos\CosAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        $this->app->singleton(EasySms::class, function ($app) {
            return new EasySms($app->config->get('sms'));
        });

        $this->app->singleton(AlipayPayment::class, function () {
            AlipayFactory::setOptions(getAlipayConfig());
            return AlipayFactory::payment();
        });

        $this->app->singleton(WechatPayment::class, function () {
            return WechatFactory::payment(getWechatPayConfig());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Storage::extend('oss', function (Application $app, array $config) {

            $driver = (new AliyunFactory())->createFilesystem($config);
            $adapter = (new AliyunFactory())->createAdapter($config);

            return new FilesystemAdapter(
                $driver,
                $adapter,
                $config
            );
        });

        Storage::extend('qcloud', function (Application $app, array $config) {
            $adapter = new CosAdapter($config);
            $flysystem = new Filesystem($adapter);
            return new FilesystemAdapter($flysystem, $adapter, $config);
        });

        $this->app->singleton(JPushClient::class, function ($app) {
            $options = [
                $app->config->get('jipush.key'),
                $app->config->get('jipush.secret'),
                $app->config->get('jipush.log'),
            ];

            return new JPushClient(...$options);
        });
    }
}
