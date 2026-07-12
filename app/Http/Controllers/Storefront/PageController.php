<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function show(Page $page): Response
    {
        abort_unless($page->is_active, 404);

        return Inertia::render('Storefront/PageShow', [
            'page' => $page->load('seo'),
        ]);
    }
}
