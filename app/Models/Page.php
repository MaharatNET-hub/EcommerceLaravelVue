<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class Page extends Model
{
    use HasFactory, HasSeo, HasTranslations;

    public array $translatable = ['title', 'content'];

    protected $fillable = ['title', 'slug', 'content', 'is_active', 'published_at'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    protected function seoFallbackTitle(): string
    {
        return $this->title;
    }
}
