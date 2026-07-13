<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;

trait SyncsSeo
{
    protected function syncSeo(Model $model, ?array $seo): void
    {
        if (! $seo) {
            return;
        }

        $model->seo()->updateOrCreate([], [
            'meta_title' => $seo['meta_title'] ?? null,
            'meta_description' => $seo['meta_description'] ?? null,
            'meta_keywords' => $seo['meta_keywords'] ?? null,
            'og_title' => $seo['og_title'] ?? null,
            'og_description' => $seo['og_description'] ?? null,
            'og_image' => $seo['og_image'] ?? null,
            'canonical_url' => $seo['canonical_url'] ?? null,
            'robots' => $seo['robots'] ?? 'index,follow',
            'focus_keyword' => $seo['focus_keyword'] ?? null,
        ]);
    }

    protected function seoValidationRules(): array
    {
        return [
            'seo' => ['nullable', 'array'],
            'seo.meta_title.ar' => ['nullable', 'string', 'max:255'],
            'seo.meta_title.en' => ['nullable', 'string', 'max:255'],
            'seo.meta_description.ar' => ['nullable', 'string', 'max:500'],
            'seo.meta_description.en' => ['nullable', 'string', 'max:500'],
            'seo.meta_keywords.ar' => ['nullable', 'string', 'max:255'],
            'seo.meta_keywords.en' => ['nullable', 'string', 'max:255'],
            'seo.og_title.ar' => ['nullable', 'string', 'max:255'],
            'seo.og_title.en' => ['nullable', 'string', 'max:255'],
            'seo.og_description.ar' => ['nullable', 'string', 'max:500'],
            'seo.og_description.en' => ['nullable', 'string', 'max:500'],
            'seo.og_image' => ['nullable', 'string', 'max:255'],
            'seo.canonical_url' => ['nullable', 'string', 'max:255'],
            'seo.robots' => ['nullable', 'string', 'max:50'],
            'seo.focus_keyword.ar' => ['nullable', 'string', 'max:255'],
            'seo.focus_keyword.en' => ['nullable', 'string', 'max:255'],
        ];
    }
}
