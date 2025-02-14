<?php

namespace App\Providers;

use App\Services\Configures\GetAllConfigureService;
use App\Traits\MediaTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    use MediaTrait;

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $configs = [];
        if (Schema::hasTable('configurations')) {
            $configs = resolve(GetAllConfigureService::class)->run()?->toArray();
            if ($configs) {
                $mailConfig = [
                    'mailers' => [
                            $configs['email_protocol'] ?? config('mail.mailers.smtp.transport') => [
                            'transport'  => $configs['email_protocol'] ?? config('mail.mailers.smtp'),
                            'host'       => $configs['smtp_server'] ?? config('mail.mailers.smtp.host'),
                            'port'       => $configs['smtp_port'] ?? config('mail.mailers.smtp.port'),
                            'username'   => $configs['smtp_user'] ?? config('mail.mailers.smtp.username'),
                            'password'   => $configs['smtp_password'] ?? config('mail.mailers.smtp.password'),
                            'encryption' => $configs['security_type'] ?? config('mail.mailers.smtp.encryption'),
                        ],
                    ],
                    'from' => [
                        'address' => $configs['from_address'] ?? config('mail.from.address'),
                        'name'    => $configs['from_name'] ?? config('mail.from.name'),
                    ],
                ];

                if (!empty($configs['test_email'])) {
                    $mailConfig['to'] = [
                        'address' => $configs['test_email'],
                        'name' => 'Test User'
                    ];
                }
                Config::set('app.date_format', $configs['date_format'] ?? 'Y-m-d');
                Config::set('app.time_format', $configs['time_format'] ?? 'H:i:s');
                Config::set('mail', array_merge(Config::get('mail', []), $mailConfig));
                $lang = $configs['language'] ?? 'vn';
                session()->put('locale', $lang);
                $configs['favicon'] = $this->getFileUrl($configs['favicon'] ?? '') ?: asset('assets/img/favicon/favicon.ico');
                $configs['logo'] = $this->getFileUrl($configs['logo'] ?? '') ?: asset('assets/img/logo/logo.png');
            }
        }

        $this->app->make('view')->share([
            'configs' => $configs,
        ]);
    }
}
