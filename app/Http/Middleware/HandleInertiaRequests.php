<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'roles' => $user?->getRoleNames() ?? [],
                'permissions' => $user?->getAllPermissions()->pluck('name') ?? [],
            ],
            'siteSettings' => [
                'site_name' => Setting::get('site_name', config('app.name')),
                'logo' => Setting::get('site_logo'),
                'favicon' => Setting::get('site_favicon'),
                'phone' => Setting::get('contact_phone'),
                'email' => Setting::get('contact_email'),
                'address' => Setting::get('contact_address'),
                'facebook' => Setting::get('social_facebook'),
                'instagram' => Setting::get('social_instagram'),
                'whatsapp' => Setting::get('social_whatsapp'),
                'currency' => Setting::get('currency', 'USD'),
                'currency_symbol' => Setting::get('currency_symbol', '$'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'footerPages' => fn () => Cache::remember('footer.pages', now()->addHour(), fn () => \App\Models\Page::query()
                ->active()
                ->orderBy('title')
                ->get(['id', 'title', 'slug'])),
            'navCategories' => fn () => Cache::remember('nav.categories', now()->addHour(), fn () => Category::query()
                ->active()
                ->whereNull('parent_id')
                ->with(['children' => fn ($q) => $q->active()->select('id', 'parent_id', 'name', 'slug')])
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug'])),
            'cartCount' => function () use ($request, $user) {
                $cart = $user
                    ? Cart::where('user_id', $user->id)->first()
                    : Cart::where('session_id', $request->session()->get('cart_session_id'))->first();

                return (int) ($cart?->items()->sum('quantity') ?? 0);
            },
        ];
    }
}
