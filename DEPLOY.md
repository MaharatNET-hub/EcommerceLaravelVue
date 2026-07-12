# النشر على Render

هذا المشروع جاهز للنشر على [Render](https://render.com) عبر ملف `render.yaml` (Blueprint).

## ما الذي يوفره Blueprint

- **shop-app**: خدمة ويب تبني الصورة من `Dockerfile` (nginx + php-fpm + قائمة انتظار + مجدول المهام عبر supervisord)، مع قرص دائم لحفظ الصور المرفوعة (`storage/app/public`).
- **shop-mysql**: خدمة خاصة تشغّل MySQL 8 (Render لا يوفر قاعدة بيانات MySQL مُدارة مجانًا، لذلك تم استضافتها ذاتيًا) مع قرص دائم لحفظ البيانات.

عند أول تشغيل للحاوية، ينفّذ `docker/entrypoint.sh` تلقائيًا:
1. `php artisan migrate --force`
2. `php artisan db:seed --class=ProductionEssentialsSeeder --force` (يُنشئ الأدوار/الصلاحيات، حساب المشرف، الإعدادات، وبيانات تجريبية للمتجر — آمن للتشغيل المتكرر).

## خطوات النشر

1. ادخل إلى [dashboard.render.com](https://dashboard.render.com) وسجّل الدخول.
2. اختر **New > Blueprint**.
3. اربط حساب GitHub وحدد هذا المستودع (`MaharatNET-hub/EcommerceLaravelVue`) والفرع الذي يحتوي هذا الكود.
4. سيكتشف Render ملف `render.yaml` تلقائيًا ويعرض الخدمتين (`shop-app` و `shop-mysql`) — اضغط **Apply**.
5. بعد اكتمال أول Deploy:
   - افتح خدمة `shop-app` في Render، وفي تبويب **Environment** اضبط `APP_URL` على الرابط الذي أعطاك إياه Render (مثل `https://shop-app-xxxx.onrender.com`)، ثم أعد النشر (Manual Deploy) ليُطبّق الرابط في الروابط المولّدة و SEO.
   - في نفس التبويب ستجد `ADMIN_EMAIL` و `ADMIN_PASSWORD` (تم توليدها تلقائيًا) — استخدمها لتسجيل الدخول إلى لوحة التحكم على `/admin`.
6. إن أردت ربط نطاق مخصص، أضِفه من تبويب **Settings > Custom Domains** في خدمة `shop-app`.

## ملاحظات مهمة

- الخطة الافتراضية في `render.yaml` هي `starter` (مدفوعة على أغلب الحسابات الآن بعد إلغاء الخطة المجانية للخدمات الدائمة على Render) — يمكنك تغييرها حسب حسابك.
- الدفع عند الاستلام (COD) هو طريقة الدفع الوحيدة المفعّلة حاليًا؛ لإضافة بوابة دفع إلكتروني (Stripe/PayPal/HyperPay...) يلزم إضافة مفاتيح API الخاصة بها كمتغيرات بيئة وربطها بمنطق `CheckoutController`.
- البريد الإلكتروني (تأكيد الطلبات، إعادة تعيين كلمة المرور) يستخدم حاليًا `MAIL_MAILER=log`؛ لتفعيل إرسال بريد حقيقي أضف بيانات SMTP كمتغيرات بيئة في خدمة `shop-app`.
- لتشغيل المشروع محليًا للتطوير: `composer install && npm install && php artisan migrate --seed && npm run dev` (مع `php artisan serve` في نافذة أخرى).
