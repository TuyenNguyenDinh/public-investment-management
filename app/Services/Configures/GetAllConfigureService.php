<?php

namespace App\Services\Configures;

use App\Models\Configuration;
use App\Traits\MediaTrait;
use Illuminate\Support\Collection;

class GetAllConfigureService
{
    use MediaTrait;

    /**
     * Run the get all configuration
     * 
     * @return Collection
     */
    public function run(): Collection
    {
        $configs = Configuration::all()
            ->pluck('value', 'key');

        $configs->put('logo_url', $this->getFileUrl($configs['logo'] ?? '') ?: asset('assets/img/logo/logo.png'));
        $configs->put('favicon_url', $this->getFileUrl($configs['favicon'] ?? '') ?: asset('assets/img/favicon/favicon.ico'));

        return $configs;
    }
}
