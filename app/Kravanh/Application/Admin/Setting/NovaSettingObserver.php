<?php

namespace App\Kravanh\Application\Admin\Setting;

use Illuminate\Support\Facades\Cache;

class NovaSettingObserver
{
    public $afterCommit = true;

    public function created(\OptimistDigital\NovaSettings\Models\Settings $settings): void
    {
        Cache::put("app:setting:{$settings['key']}", $settings['value']);
    }

    public function updated(\OptimistDigital\NovaSettings\Models\Settings $settings): void
    {
        Cache::put("app:setting:{$settings['key']}", $settings['value']);
    }
}
