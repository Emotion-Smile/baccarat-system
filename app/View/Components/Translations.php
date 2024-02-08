<?php

namespace App\View\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class Translations extends Component
{
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $locale = App::getLocale();
        if (App::isLocal()) {
            Cache::forget('lang:translate:' . $locale);
        }
        $translations = Cache::remember('lang:translate:' . $locale, now()->addHour(), function () use ($locale) {
            $phpTranslations = [];
            $jsonTranslations = [];

            if (File::exists(resource_path("lang/{$locale}"))) {
                $phpTranslations = collect(File::allFiles(resource_path("lang/{$locale}")))
                    ->filter(fn($file) => $file->getExtension() === 'php')
                    ->flatMap(fn($file) => Arr::dot(File::getRequire($file->getRealPath())))
                    ->toArray();
            }

            if (File::exists(resource_path("lang/{$locale}.json"))) {
                $jsonTranslations = json_decode(File::get(resource_path("lang/{$locale}.json"), true));
            }

            return array_merge($phpTranslations, $jsonTranslations);
        });

        return view('components.translations', [
            'translations' => $translations
        ]);
    }
}
