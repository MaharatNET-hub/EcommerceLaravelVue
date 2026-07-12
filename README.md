# EcommerceLaravelVue

منصة تجارة إلكترونية كاملة مبنية بـ **Laravel 11 + Inertia.js + Vue 3 + Tailwind CSS**، مع واجهة متجر أمامية ولوحة تحكم إدارية شاملة.

## المزايا

- **واجهة متجر**: صفحة رئيسية بسلايدر، تصنيفات متداخلة، براندات، بحث، سلة تسوق، إتمام طلب (دفع عند الاستلام)، تتبع الطلبات، تقييمات المنتجات، صفحات محتوى (CMS).
- **لوحة تحكم إدارية** (`/admin`) مبنية بالكامل بنفس الستاك:
  - إدارة التصنيفات، البراندات، المنتجات (بالصور والمتغيرات مثل اللون/المقاس).
  - إدارة الطلبات وحالاتها، كوبونات الخصم.
  - إدارة المستخدمين والأدوار والصلاحيات (عبر [spatie/laravel-permission](https://spatie.be/docs/laravel-permission)).
  - إدارة صفحات المحتوى والسلايدر الرئيسي.
  - إعدادات الموقع العامة (الاسم، الشعار، التواصل، الشبكات الاجتماعية).
  - **SEO كامل لكل صفحة**: عنوان ووصف Meta، Open Graph، Canonical، Robots، لكل منتج/تصنيف/براند/صفحة محتوى، بالإضافة لإعدادات SEO افتراضية على مستوى الموقع (Google Analytics، Facebook Pixel، تحقق Search Console)، مع `sitemap.xml` و `robots.txt` يتم توليدهما ديناميكيًا.
- صلاحيات دقيقة لكل قسم (عرض/إضافة/تعديل/حذف)، مع دور "Super Admin" يملك كل الصلاحيات تلقائيًا وأدوار جاهزة أخرى (مدير منتجات، مدير طلبات، محرر محتوى).

## التشغيل محليًا

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev      # في نافذة طرفية
php artisan serve # في نافذة أخرى
```

بعد التشغيل، سجّل الدخول إلى `/admin` بالحساب الظاهر في مخرجات `db:seed` (أو `ADMIN_EMAIL`/`ADMIN_PASSWORD` إن كانا معرّفين في `.env`).

## النشر (Deployment)

راجع [DEPLOY.md](./DEPLOY.md) لخطوات النشر على [Render](https://render.com) عبر ملف `render.yaml` الجاهز.

## الستاك التقني

- Laravel 11 (PHP 8.2+)
- Inertia.js + Vue 3 (Composition API) + Vite
- Tailwind CSS (مع دعم RTL كامل للعربية)
- MySQL (إنتاج) / SQLite (تطوير محلي)
- spatie/laravel-permission للأدوار والصلاحيات
- Docker (nginx + php-fpm + supervisord) للنشر
