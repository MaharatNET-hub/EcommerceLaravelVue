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
        $brandData = [
            'Apple' => ['ar' => 'منتجات Apple الأصلية بضمان الوكيل.', 'en' => 'Genuine Apple products with official warranty.'],
            'Samsung' => ['ar' => 'منتجات Samsung الأصلية بضمان الوكيل.', 'en' => 'Genuine Samsung products with official warranty.'],
            'Sony' => ['ar' => 'منتجات Sony الأصلية بضمان الوكيل.', 'en' => 'Genuine Sony products with official warranty.'],
            'LG' => ['ar' => 'منتجات LG الأصلية بضمان الوكيل.', 'en' => 'Genuine LG products with official warranty.'],
            'Nike' => ['ar' => 'منتجات Nike الأصلية بضمان الوكيل.', 'en' => 'Genuine Nike products with official warranty.'],
            'Adidas' => ['ar' => 'منتجات Adidas الأصلية بضمان الوكيل.', 'en' => 'Genuine Adidas products with official warranty.'],
            'IKEA' => ['ar' => 'منتجات IKEA الأصلية بضمان الوكيل.', 'en' => 'Genuine IKEA products with official warranty.'],
            'Generic' => ['ar' => 'منتجات متنوعة بجودة عالية.', 'en' => 'A variety of high-quality products.'],
        ];

        $brands = [];

        foreach ($brandData as $name => $description) {
            $brands[$name] = Brand::create([
                'name' => ['ar' => $name, 'en' => $name],
                'slug' => Str::slug($name),
                'logo' => "https://placehold.co/200x120?text={$name}",
                'description' => $description,
                'is_active' => true,
            ]);
        }

        return $brands;
    }

    private function seedCategoriesAndProducts(array $brands): void
    {
        $tree = [
            [
                'name' => ['ar' => 'إلكترونيات', 'en' => 'Electronics'],
                'children' => [
                    ['ar' => 'هواتف ذكية', 'en' => 'Smartphones'],
                    ['ar' => 'حواسيب ولابتوب', 'en' => 'Computers & Laptops'],
                    ['ar' => 'أجهزة صوت', 'en' => 'Audio Devices'],
                ],
                'products' => [
                    ['name' => ['ar' => 'هاتف ذكي X200 برو', 'en' => 'X200 Pro Smartphone'], 'price' => 799, 'sale' => 699, 'brand' => 'Samsung'],
                    ['name' => ['ar' => 'آيفون 16 برو ماكس', 'en' => 'iPhone 16 Pro Max'], 'price' => 1399, 'sale' => null, 'brand' => 'Apple'],
                    ['name' => ['ar' => 'لابتوب ألترا بوك 15', 'en' => 'UltraBook 15 Laptop'], 'price' => 1099, 'sale' => 949, 'brand' => 'LG'],
                    ['name' => ['ar' => 'سماعات لاسلكية نويز كانسل', 'en' => 'Wireless Noise-Cancelling Headphones'], 'price' => 129, 'sale' => 99, 'brand' => 'Sony'],
                    ['name' => ['ar' => 'ساعة ذكية رياضية', 'en' => 'Sport Smartwatch'], 'price' => 199, 'sale' => null, 'brand' => 'Samsung'],
                    ['name' => ['ar' => 'مكبر صوت بلوتوث محمول', 'en' => 'Portable Bluetooth Speaker'], 'price' => 79, 'sale' => 59, 'brand' => 'Sony'],
                ],
            ],
            [
                'name' => ['ar' => 'أزياء', 'en' => 'Fashion'],
                'children' => [
                    ['ar' => 'أزياء رجالية', 'en' => "Men's Fashion"],
                    ['ar' => 'أزياء نسائية', 'en' => "Women's Fashion"],
                    ['ar' => 'أحذية', 'en' => 'Shoes'],
                ],
                'products' => [
                    ['name' => ['ar' => 'قميص رجالي قطن كلاسيك', 'en' => 'Classic Cotton Men\'s Shirt'], 'price' => 39, 'sale' => 29, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'فستان صيفي أنيق', 'en' => 'Elegant Summer Dress'], 'price' => 59, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'حذاء رياضي إيروماكس', 'en' => 'AeroMax Sports Shoe'], 'price' => 129, 'sale' => 99, 'brand' => 'Nike'],
                    ['name' => ['ar' => 'جاكيت رياضي ثلاثية الخطوط', 'en' => 'Three-Stripes Sport Jacket'], 'price' => 89, 'sale' => 69, 'brand' => 'Adidas'],
                    ['name' => ['ar' => 'حقيبة يد جلد طبيعي', 'en' => 'Genuine Leather Handbag'], 'price' => 75, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'بنطلون جينز رجالي', 'en' => "Men's Denim Jeans"], 'price' => 49, 'sale' => 39, 'brand' => 'Generic'],
                ],
            ],
            [
                'name' => ['ar' => 'المنزل والمطبخ', 'en' => 'Home & Kitchen'],
                'children' => [
                    ['ar' => 'أثاث', 'en' => 'Furniture'],
                    ['ar' => 'أدوات مطبخ', 'en' => 'Kitchenware'],
                ],
                'products' => [
                    ['name' => ['ar' => 'طقم أواني طهي غير لاصقة', 'en' => 'Non-Stick Cookware Set'], 'price' => 89, 'sale' => 69, 'brand' => 'IKEA'],
                    ['name' => ['ar' => 'كرسي مكتب مريح', 'en' => 'Comfort Office Chair'], 'price' => 149, 'sale' => null, 'brand' => 'IKEA'],
                    ['name' => ['ar' => 'خلاط كهربائي متعدد السرعات', 'en' => 'Multi-Speed Electric Blender'], 'price' => 45, 'sale' => 35, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'طاولة طعام خشبية', 'en' => 'Wooden Dining Table'], 'price' => 259, 'sale' => 219, 'brand' => 'IKEA'],
                    ['name' => ['ar' => 'مكنسة كهربائية لاسلكية', 'en' => 'Cordless Vacuum Cleaner'], 'price' => 159, 'sale' => null, 'brand' => 'LG'],
                ],
            ],
            [
                'name' => ['ar' => 'الجمال والعناية الشخصية', 'en' => 'Beauty & Personal Care'],
                'children' => [],
                'products' => [
                    ['name' => ['ar' => 'مجموعة عناية بالبشرة', 'en' => 'Skincare Care Set'], 'price' => 55, 'sale' => 45, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'مجفف شعر احترافي', 'en' => 'Professional Hair Dryer'], 'price' => 65, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'عطر رجالي فاخر', 'en' => "Luxury Men's Perfume"], 'price' => 89, 'sale' => 74, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'ماكينة حلاقة كهربائية', 'en' => 'Electric Shaver'], 'price' => 49, 'sale' => 39, 'brand' => 'Generic'],
                ],
            ],
            [
                'name' => ['ar' => 'الرياضة واللياقة', 'en' => 'Sports & Fitness'],
                'children' => [],
                'products' => [
                    ['name' => ['ar' => 'دراجة هوائية جبلية', 'en' => 'Mountain Bike'], 'price' => 349, 'sale' => 299, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'سجادة يوغا مانعة للانزلاق', 'en' => 'Non-Slip Yoga Mat'], 'price' => 25, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'دمبل قابل للتعديل', 'en' => 'Adjustable Dumbbell'], 'price' => 69, 'sale' => 55, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'حذاء جري احترافي', 'en' => 'Professional Running Shoe'], 'price' => 119, 'sale' => 95, 'brand' => 'Nike'],
                ],
            ],
            [
                'name' => ['ar' => 'ألعاب وهوايات', 'en' => 'Toys & Hobbies'],
                'children' => [],
                'products' => [
                    ['name' => ['ar' => 'مجسم تركيب 3D للأطفال', 'en' => "3D Puzzle Model for Kids"], 'price' => 29, 'sale' => null, 'brand' => 'Generic'],
                    ['name' => ['ar' => 'وحدة تحكم ألعاب لاسلكية', 'en' => 'Wireless Game Controller'], 'price' => 59, 'sale' => 49, 'brand' => 'Sony'],
                    ['name' => ['ar' => 'طائرة تحكم عن بعد', 'en' => 'Remote Control Drone'], 'price' => 89, 'sale' => 69, 'brand' => 'Generic'],
                ],
            ],
        ];

        $sortOrder = 0;

        foreach ($tree as $info) {
            $categoryNameAr = $info['name']['ar'];

            $category = Category::create([
                'name' => $info['name'],
                'slug' => Str::slug($categoryNameAr) ?: Str::slug(Str::ascii($categoryNameAr)),
                'description' => [
                    'ar' => "تسوق أفضل منتجات {$categoryNameAr} بأسعار تنافسية وجودة عالية.",
                    'en' => "Shop the best {$info['name']['en']} products at competitive prices and top quality.",
                ],
                'image' => 'https://placehold.co/600x400?text='.urlencode($categoryNameAr),
                'is_active' => true,
                'sort_order' => $sortOrder++,
            ]);

            foreach ($info['children'] as $child) {
                $childSlug = Str::slug($child['ar']) ?: Str::slug(Str::ascii($child['ar'])).'-'.Str::random(4);

                Category::create([
                    'parent_id' => $category->id,
                    'name' => $child,
                    'slug' => $childSlug,
                    'is_active' => true,
                    'sort_order' => $sortOrder++,
                ]);
            }

            foreach ($info['products'] as $index => $productInfo) {
                $nameAr = $productInfo['name']['ar'];
                $nameEn = $productInfo['name']['en'];
                $slug = Str::slug($nameAr) ?: Str::slug(Str::ascii($nameAr));
                $slug = $slug ?: 'product-'.Str::random(8);

                $product = Product::create([
                    'category_id' => $category->id,
                    'brand_id' => $brands[$productInfo['brand']]->id,
                    'name' => $productInfo['name'],
                    'slug' => $slug.'-'.Str::random(4),
                    'sku' => 'SKU-'.strtoupper(Str::random(8)),
                    'description' => [
                        'ar' => "<p>{$nameAr} بجودة عالية وضمان شامل. مناسب للاستخدام اليومي مع تصميم عصري ومتانة تدوم طويلاً.</p>",
                        'en' => "<p>{$nameEn} — high quality with full warranty. Great for everyday use with a modern design and lasting durability.</p>",
                    ],
                    'short_description' => [
                        'ar' => "{$nameAr} - جودة عالية وسعر مناسب.",
                        'en' => "{$nameEn} — great quality at a fair price.",
                    ],
                    'price' => $productInfo['price'],
                    'sale_price' => $productInfo['sale'],
                    'stock_quantity' => rand(5, 80),
                    'is_active' => true,
                    'is_featured' => $index % 3 === 0,
                ]);

                $product->images()->create([
                    'path' => 'https://placehold.co/800x800?text='.urlencode($nameAr),
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        }
    }

    private function seedPages(): void
    {
        $pages = [
            [
                'title' => ['ar' => 'من نحن', 'en' => 'About Us'],
                'content' => [
                    'ar' => 'نحن متجر إلكتروني يقدم تشكيلة واسعة من المنتجات عالية الجودة بأسعار تنافسية، مع خدمة عملاء متميزة وتوصيل سريع.',
                    'en' => 'We are an online store offering a wide range of high-quality products at competitive prices, with excellent customer service and fast delivery.',
                ],
            ],
            [
                'title' => ['ar' => 'اتصل بنا', 'en' => 'Contact Us'],
                'content' => [
                    'ar' => 'يسعدنا تواصلكم معنا عبر الهاتف أو البريد الإلكتروني أو منصات التواصل الاجتماعي لأي استفسار أو طلب دعم.',
                    'en' => "We'd love to hear from you by phone, email, or social media for any questions or support requests.",
                ],
            ],
            [
                'title' => ['ar' => 'الشحن والتوصيل', 'en' => 'Shipping & Delivery'],
                'content' => [
                    'ar' => 'نوفر خدمة توصيل سريعة وآمنة لجميع المناطق، مع إمكانية تتبع الطلب لحظة بلحظة حتى وصوله إليك.',
                    'en' => 'We provide fast and secure delivery to all areas, with real-time order tracking until it reaches you.',
                ],
            ],
            [
                'title' => ['ar' => 'سياسة الاسترجاع', 'en' => 'Return Policy'],
                'content' => [
                    'ar' => 'يمكنك استرجاع أو استبدال المنتج خلال 14 يوماً من تاريخ الاستلام وفق الشروط الموضحة في هذه الصفحة.',
                    'en' => 'You can return or exchange a product within 14 days of receipt, subject to the terms outlined on this page.',
                ],
            ],
            [
                'title' => ['ar' => 'الشروط والأحكام', 'en' => 'Terms & Conditions'],
                'content' => [
                    'ar' => 'باستخدامك لهذا الموقع فإنك توافق على الشروط والأحكام الموضحة أدناه والتي تحكم عملية الشراء والاستخدام.',
                    'en' => 'By using this website, you agree to the terms and conditions below, which govern purchasing and usage.',
                ],
            ],
            [
                'title' => ['ar' => 'سياسة الخصوصية', 'en' => 'Privacy Policy'],
                'content' => [
                    'ar' => 'نحن نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية وعدم مشاركتها مع أي جهة خارجية دون موافقتك.',
                    'en' => 'We respect your privacy and are committed to protecting your personal data and never sharing it with third parties without your consent.',
                ],
            ],
        ];

        foreach ($pages as $page) {
            $titleAr = $page['title']['ar'];

            Page::create([
                'title' => $page['title'],
                'slug' => Str::slug($titleAr) ?: Str::slug(Str::ascii($titleAr)),
                'content' => [
                    'ar' => "<p>{$page['content']['ar']}</p>",
                    'en' => "<p>{$page['content']['en']}</p>",
                ],
                'is_active' => true,
                'published_at' => now(),
            ]);
        }
    }

    private function seedSliders(): void
    {
        $slides = [
            ['title' => ['ar' => 'تخفيضات الموسم', 'en' => 'Season Sale'], 'subtitle' => ['ar' => 'خصومات تصل إلى 40%', 'en' => 'Discounts up to 40%'], 'button' => ['ar' => 'تسوق الآن', 'en' => 'Shop Now']],
            ['title' => ['ar' => 'وصل حديثاً', 'en' => 'New Arrivals'], 'subtitle' => ['ar' => 'أحدث المنتجات الإلكترونية', 'en' => 'The latest electronics'], 'button' => ['ar' => 'اكتشف المزيد', 'en' => 'Discover More']],
            ['title' => ['ar' => 'أزياء العصر', 'en' => 'Modern Fashion'], 'subtitle' => ['ar' => 'تشكيلة جديدة كل أسبوع', 'en' => 'New collection every week'], 'button' => ['ar' => 'تصفح الأزياء', 'en' => 'Browse Fashion']],
        ];

        foreach ($slides as $index => $slide) {
            Slider::create([
                'title' => $slide['title'],
                'subtitle' => $slide['subtitle'],
                'image' => 'https://placehold.co/1600x600?text='.urlencode($slide['title']['ar']),
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
