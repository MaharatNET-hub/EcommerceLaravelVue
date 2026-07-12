<?php

namespace App\Models\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    /**
     * Resolve the effective SEO tag values, falling back to sensible
     * defaults derived from the model itself when no override is set.
     */
    public function resolveSeo(): array
    {
        $seo = $this->seo;
        $title = $seo?->meta_title ?: $this->seoFallbackTitle();
        $description = $seo?->meta_description ?: $this->seoFallbackDescription();
        $image = $seo?->og_image ?: $this->seoFallbackImage();

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $seo?->meta_keywords,
            'og_title' => $seo?->og_title ?: $title,
            'og_description' => $seo?->og_description ?: $description,
            'og_image' => $image,
            'canonical_url' => $seo?->canonical_url,
            'robots' => $seo?->robots ?: 'index,follow',
        ];
    }

    protected function seoFallbackTitle(): string
    {
        return $this->name ?? $this->title ?? '';
    }

    protected function seoFallbackDescription(): ?string
    {
        $text = $this->short_description ?? $this->description ?? $this->content ?? null;

        return $text ? \Illuminate\Support\Str::limit(strip_tags($text), 160) : null;
    }

    protected function seoFallbackImage(): ?string
    {
        return null;
    }
}
