<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'general' => [
                'site_name' => 'المتجر',
                'contact_phone' => '+963 900 000 000',
                'contact_email' => 'support@maharatnet.com',
                'contact_address' => 'دمشق، سوريا',
                'currency' => 'USD',
                'currency_symbol' => '$',
                'shipping_flat_fee' => '5',
            ],
            'social' => [
                'social_facebook' => 'https://facebook.com',
                'social_instagram' => 'https://instagram.com',
                'social_whatsapp' => 'https://wa.me/963900000000',
                'social_tiktok' => '',
                'social_x' => '',
            ],
            'seo' => [
                'seo_default_meta_title' => 'المتجر - تسوق أونلاين لكل ما تحتاجه',
                'seo_default_meta_description' => 'تسوق أحدث المنتجات بأفضل الأسعار: إلكترونيات، أزياء، منتجات منزلية والمزيد، مع توصيل سريع وآمن.',
                'seo_google_verification' => '',
                'seo_facebook_pixel' => '',
                'seo_google_analytics' => '',
            ],
        ];

        foreach ($defaults as $group => $values) {
            foreach ($values as $key => $value) {
                if (Setting::where('key', $key)->doesntExist()) {
                    Setting::set($key, $value, $group);
                }
            }
        }
    }
}
