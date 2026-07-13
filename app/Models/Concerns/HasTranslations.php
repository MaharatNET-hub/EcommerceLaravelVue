<?php

namespace App\Models\Concerns;

use Spatie\Translatable\HasTranslations as SpatieHasTranslations;

/**
 * Wraps spatie/laravel-translatable so that array/JSON serialization
 * (used for every Inertia response) returns the current-locale string for
 * translatable attributes, matching normal property access — instead of
 * spatie's default of exposing every locale's translation in one payload.
 * Translatable attributes are merged into $casts as 'array', so Eloquent's
 * cast-resolution path (addCastAttributesToArray) decodes the raw JSON
 * before any accessor/mutator override gets a say — the only reliable
 * place to fix this up is after attributesToArray() has run.
 * Admin forms that need every locale opt in explicitly via
 * SerializesTranslations::withAllTranslations(), which calls
 * getTranslations() directly and is unaffected by this override.
 */
trait HasTranslations
{
    use SpatieHasTranslations;

    public function attributesToArray(): array
    {
        $attributes = parent::attributesToArray();

        foreach ($this->getTranslatableAttributes() as $key) {
            if (array_key_exists($key, $attributes)) {
                $attributes[$key] = $this->getTranslation($key, $this->getLocale(), $this->useFallbackLocale());
            }
        }

        return $attributes;
    }
}
