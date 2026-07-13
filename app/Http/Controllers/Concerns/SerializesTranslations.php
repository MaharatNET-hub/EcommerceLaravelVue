<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Database\Eloquent\Model;

trait SerializesTranslations
{
    /**
     * Serialize a translatable model with every locale's value for each
     * translatable attribute (instead of just the current app locale),
     * so admin forms can populate all language fields at once.
     */
    protected function withAllTranslations(Model $model): array
    {
        $data = $model->toArray();

        if (method_exists($model, 'getTranslatableAttributes')) {
            foreach ($model->getTranslatableAttributes() as $field) {
                $data[$field] = $model->getTranslations($field);
            }
        }

        if ($model->relationLoaded('seo') && $model->seo) {
            $data['seo'] = $this->withAllTranslations($model->seo);
        }

        return $data;
    }
}
