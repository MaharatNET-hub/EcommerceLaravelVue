<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $map = [
        'categories' => ['name', 'description'],
        'brands' => ['name', 'description'],
        'products' => ['name', 'description', 'short_description'],
        'pages' => ['title', 'content'],
        'sliders' => ['title', 'subtitle', 'button_text'],
        'seo_meta' => ['meta_title', 'meta_description', 'meta_keywords', 'og_title', 'og_description', 'focus_keyword'],
    ];

    public function up(): void
    {
        foreach ($this->map as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->dropColumn($columns);
            });

            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                foreach ($columns as $column) {
                    $blueprint->json($column)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->map as $table => $columns) {
            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                $blueprint->dropColumn($columns);
            });

            Schema::table($table, function (Blueprint $blueprint) use ($columns) {
                foreach ($columns as $column) {
                    $blueprint->string($column)->nullable();
                }
            });
        }
    }
};
