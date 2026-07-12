<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingController extends Controller
{
    use HandlesUploads;

    private const GENERAL_KEYS = ['site_name', 'contact_phone', 'contact_email', 'contact_address', 'currency', 'currency_symbol'];

    private const SOCIAL_KEYS = ['social_facebook', 'social_instagram', 'social_whatsapp', 'social_tiktok', 'social_x'];

    private const SEO_KEYS = ['seo_default_meta_title', 'seo_default_meta_description', 'seo_default_og_image', 'seo_google_verification', 'seo_facebook_pixel', 'seo_google_analytics'];

    public function index(): Response
    {
        $this->authorize('settings.view');

        return Inertia::render('Admin/Settings/Index', [
            'general' => Setting::group('general'),
            'social' => Setting::group('social'),
            'seo' => Setting::group('seo'),
            'logo' => Setting::get('site_logo'),
            'favicon' => Setting::get('site_favicon'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->authorize('settings.update');

        $group = $request->input('group', 'general');

        if ($group === 'general') {
            $data = $request->validate([
                'site_name' => ['nullable', 'string', 'max:255'],
                'contact_phone' => ['nullable', 'string', 'max:50'],
                'contact_email' => ['nullable', 'email', 'max:255'],
                'contact_address' => ['nullable', 'string', 'max:255'],
                'currency' => ['nullable', 'string', 'max:10'],
                'currency_symbol' => ['nullable', 'string', 'max:5'],
                'logo' => ['nullable', 'image', 'max:2048'],
                'favicon' => ['nullable', 'image', 'max:1024'],
            ]);

            foreach (self::GENERAL_KEYS as $key) {
                if (array_key_exists($key, $data)) {
                    Setting::set($key, $data[$key], 'general');
                }
            }

            if ($request->hasFile('logo')) {
                Setting::set('site_logo', $this->storeUpload($request->file('logo'), 'settings'), 'general');
            }
            if ($request->hasFile('favicon')) {
                Setting::set('site_favicon', $this->storeUpload($request->file('favicon'), 'settings'), 'general');
            }
        } elseif ($group === 'social') {
            $data = $request->validate([
                'social_facebook' => ['nullable', 'string', 'max:255'],
                'social_instagram' => ['nullable', 'string', 'max:255'],
                'social_whatsapp' => ['nullable', 'string', 'max:255'],
                'social_tiktok' => ['nullable', 'string', 'max:255'],
                'social_x' => ['nullable', 'string', 'max:255'],
            ]);

            foreach (self::SOCIAL_KEYS as $key) {
                Setting::set($key, $data[$key] ?? null, 'social');
            }
        } elseif ($group === 'seo') {
            $data = $request->validate([
                'seo_default_meta_title' => ['nullable', 'string', 'max:255'],
                'seo_default_meta_description' => ['nullable', 'string', 'max:500'],
                'seo_default_og_image' => ['nullable', 'image', 'max:2048'],
                'seo_google_verification' => ['nullable', 'string', 'max:255'],
                'seo_facebook_pixel' => ['nullable', 'string', 'max:255'],
                'seo_google_analytics' => ['nullable', 'string', 'max:255'],
            ]);

            foreach (self::SEO_KEYS as $key) {
                if ($key === 'seo_default_og_image') {
                    continue;
                }
                Setting::set($key, $data[$key] ?? null, 'seo');
            }

            if ($request->hasFile('seo_default_og_image')) {
                Setting::set('seo_default_og_image', $this->storeUpload($request->file('seo_default_og_image'), 'settings'), 'seo');
            }
        }

        return back()->with('success', 'تم حفظ الإعدادات.');
    }
}
