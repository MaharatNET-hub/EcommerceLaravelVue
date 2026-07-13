<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Concerns\HasTranslations;

class SeoMeta extends Model
{
    use HasTranslations;

    public array $translatable = [
        'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'focus_keyword',
    ];

    protected $table = 'seo_meta';

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'robots',
        'focus_keyword',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
