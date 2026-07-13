<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasTranslations;

class Slider extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'subtitle', 'button_text'];

    protected $fillable = [
        'title', 'subtitle', 'image', 'mobile_image',
        'button_text', 'button_link', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
