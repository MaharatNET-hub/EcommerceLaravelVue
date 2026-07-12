<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Page;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::query()->exists()) {
            return;
        }

        $brands = $this->seedBrands();
        $this->seedCategoriesAndProducts($brands);
        $this->seedPages();
        $this->seedSliders();
        $this->seedCoupon();
    }

    private function seedBrands(): array
    {
        $names = ['Apple', 'Samsung', 'Sony', 'LG', 'Nike', 'Adidas', 'IKEA', 'Generic'];
        $brands = [];

        foreach ($names as $name) {
            $brands[$name] = Brand::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'logo' => "https://placehold.co/200x120?text={$name}",
                'description' => "منتجات {$name} الأصلية بضمان الوكيل.",
                'is_active' => true,
            ]);
        }

        return $brands;
    }

    private function seedCategoriesAndProducts(array $brands): void
    {
        $tree = [
            'إلكترونيات' => [
                'children' => ['هواتف ذكية', 'حواسيب ولابتوب', 'أجهزة صوت'],
                'products' => [
                    ['name' => 'هاتف ذكي X200 برو', 'price' => 799, 'sale' => 699, 'brand' => 'Samsung'],
                    ['name' => 'آيفون 16 برو ماكس', 'price' => 1399, 'sale' => null, 'brand' => 'Apple'],
                    ['name' => 'لابتوب ألترا بوك 15', 'price' => 1099, 'sale' => 949, 'brand' => 'LG'],
                    ['name' => 'سماعات لاسلكية نويز كانسل', 'price' => 129, 'sale' => 99, 'brand' => 'Sony'],
                    ['name' => 'ساعة ذكية رياضية', 'price' => 199, 'sale' => null, 'brand' => 'Samsung'],
                    ['name' => 'مكبر صوت بلوتوث محمول', 'price' => 79, 'sale' => 59, 'brand' => 'Sony'],
                ],
            ],
            'أزياء' => [
                'children' => ['أزياء رجالية', 'أزياء نسائية', 'أحذية'],
                'products' => [
                    ['name' => 'قميص رجالي قطن كلاسيك', 'price' => 39, 'sale' => 29, 'brand' => 'Generic'],
                    ['name' => 'فستان صيفي أنيق', 'price' => 59, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => 'حذاء رياضي إيروماكس', 'price' => 129, 'sale' => 99, 'brand' => 'Nike'],
                    ['name' => 'جاكيت رياضي ثلاثية الخطوط', 'price' => 89, 'sale' => 69, 'brand' => 'Adidas'],
                    ['name' => 'حقيبة يد جلد طبيعي', 'price' => 75, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => 'بنطلون جينز رجالي', 'price' => 49, 'sale' => 39, 'brand' => 'Generic'],
                ],
            ],
            'المنزل والمطبخ' => [
                'children' => ['أثاث', 'أدوات مطبخ'],
                'products' => [
                    ['name' => 'طقم أواني طهي غير لاصقة', 'price' => 89, 'sale' => 69, 'brand' => 'IKEA'],
                    ['name' => 'كرسي مكتب مريح', 'price' => 149, 'sale' => null, 'brand' => 'IKEA'],
                    ['name' => 'خلاط كهربائي متعدد السرعات', 'price' => 45, 'sale' => 35, 'brand' => 'Generic'],
                    ['name' => 'طاولة طعام خشبية', 'price' => 259, 'sale' => 219, 'brand' => 'IKEA'],
                    ['name' => 'مكنسة كهربائية لاسلكية', 'price' => 159, 'sale' => null, 'brand' => 'LG'],
                ],
            ],
            'الجمال والعناية الشخصية' => [
                'children' => [],
                'products' => [
                    ['name' => 'مجموعة عناية بالبشرة', 'price' => 55, 'sale' => 45, 'brand' => 'Generic'],
                    ['name' => 'مجفف شعر احترافي', 'price' => 65, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => 'عطر رجالي فاخر', 'price' => 89, 'sale' => 74, 'brand' => 'Generic'],
                    ['name' => 'ماكينة حلاقة كهربائية', 'price' => 49, 'sale' => 39, 'brand' => 'Generic'],
                ],
            ],
            'الرياضة واللياقة' => [
                'children' => [],
                'products' => [
                    ['name' => 'دراجة هوائية جبلية', 'price' => 349, 'sale' => 299, 'brand' => 'Generic'],
                    ['name' => 'سجادة يوغا مانعة للانزلاق', 'price' => 25, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => 'دمبل قابل للتعديل', 'price' => 69, 'sale' => 55, 'brand' => 'Generic'],
                    ['name' => 'حذاء جري احترافي', 'price' => 119, 'sale' => 95, 'brand' => 'Nike'],
                ],
            ],
            'ألعاب وهوايات' => [
                'children' => [],
                'products' => [
                    ['name' => 'مجسم تركيب 3D للأطفال', 'price' => 29, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => 'وحدة تحكم ألعاب لاسلكية', 'price' => 59, 'sale' => 49, 'brand' => 'Sony'],
                    ['name' => 'طائرة تحكم عن بعد', 'price' => 89, 'sale' => 69, 'brand' => 'Generic'],
                ],
            ],
        ];

        $sortOrder = 0;

        foreach ($tree as $categoryName => $info) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName, '-', 'ar') ?: Str::slug(Str::ascii($categoryName)),
                'description' => "تسوق أفضل منتجات {$categoryName} بأسعار تنافسية وجودة عالية.",
                'image' => 'https://placehold.co/600x400?text='.urlencode($categoryName),
                'is_active' => true,
                'sort_order' => $sortOrder++,
            ]);

            foreach ($info['children'] as $childName) {
                Category::create([
                    'parent_id' => $category->id,
                    'name' => $childName,
                    'slug' => Str::slug($childName) ?: Str::slug(Str::ascii($childName)).'-'.Str::random(4),
                    'is_active' => true,
                    'sort_order' => $sortOrder++,
                ]);
            }

            foreach ($info['products'] as $index => $productInfo) {
                $slug = Str::slug($productInfo['name']) ?: Str::slug(Str::ascii($productInfo['name']));
                $slug = $slug ?: 'product-'.Str::random(8);

                $product = Product::create([
                    'category_id' => $category->id,
                    'brand_id' => $brands[$productInfo['brand']]->id,
                    'name' => $productInfo['name'],
                    'slug' => $slug.'-'.Str::random(4),
                    'sku' => 'SKU-'.strtoupper(Str::random(8)),
                    'description' => "<p>{$productInfo['name']} بجودة عالية وضمان شامل. مناسب للاستخدام اليومي مع تصميم عصري ومتانة تدوم طويلاً.</p>",
                    'short_description' => "{$productInfo['name']} - جودة عالية وسعر مناسب.",
                    'price' => $productInfo['price'],
                    'sale_price' => $productInfo['sale'],
                    'stock_quantity' => rand(5, 80),
                    'is_active' => true,
                    'is_featured' => $index % 3 === 0,
                ]);

                $product->images()->create([
                    'path' => 'https://placehold.co/800x800?text='.urlencode($productInfo['name']),
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }
    }

    private function seedPages(): void
    {
        $pages = [
            'من نحن' => 'نحن متجر إلكتروني يقدم تشكيلة واسعة من المنتجات عالية الجودة بأسعار تنافسية، مع خدمة عملاء متميزة وتوصيل سريع.',
            'اتصل بنا' => 'يسعدنا تواصلكم معنا عبر الهاتف أو البريد الإلكتروني أو منصات التواصل الاجتماعي لأي استفسار أو طلب دعم.',
            'الشحن والتوصيل' => 'نوفر خدمة توصيل سريعة وآمنة لجميع المناطق، مع إمكانية تتبع الطلب لحظة بلحظة حتى وصوله إليك.',
            'سياسة الاسترجاع' => 'يمكنك استرجاع أو استبدال المنتج خلال 14 يوماً من تاريخ الاستلام وفق الشروط الموضحة في هذه الصفحة.',
            'الشروط والأحكام' => 'باستخدامك لهذا الموقع فإنك توافق على الشروط والأحكام الموضحة أدناه والتي تحكم عملية الشراء والاستخدام.',
            'سياسة الخصوصية' => 'نحن نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية وعدم مشاركتها مع أي جهة خارجية دون موافقتك.',
        ];

        foreach ($pages as $title => $content) {
            Page::create([
                'title' => $title,
                'slug' => Str::slug($title) ?: Str::slug(Str::ascii($title)),
                'content' => "<p>{$content}</p>",
                'is_active' => true,
                'published_at' => now(),
            ]);
        }
    }

    private function seedSliders(): void
    {
        $slides = [
            ['title' => 'تخفيضات الموسم', 'subtitle' => 'خصومات تصل إلى 40%', 'button' => 'تسوق الآن'],
            ['title' => 'وصل حديثاً', 'subtitle' => 'أحدث المنتجات الإلكترونية', 'button' => 'اكتشف المزيد'],
            ['title' => 'أزياء العصر', 'subtitle' => 'تشكيلة جديدة كل أسبوع', 'button' => 'تصفح الأزياء'],
        ];

        foreach ($slides as $index => $slide) {
            Slider::create([
                'title' => $slide['title'],
                'subtitle' => $slide['subtitle'],
                'image' => 'https://placehold.co/1600x600?text='.urlencode($slide['title']),
                'button_text' => $slide['button'],
                'button_link' => '/',
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }

    private function seedCoupon(): void
    {
        Coupon::create([
            'code' => 'WELCOME10',
            'type' => 'percent',
            'value' => 10,
            'min_order_amount' => 50,
            'max_uses' => 500,
            'is_active' => true,
        ]);
    }
}
