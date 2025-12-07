مرجع سريع - نظام الصلاحيات
📂 الملفات الرئيسية في MosqueTracker
MosqueTracker/
├── app/
│   ├── Models/
│   │   └── User.php                          # يستخدم HasRoles trait
│   └── Http/
│       └── Controllers/
│           └── AuthController.php            # تسجيل الدخول/الخروج
│
├── database/
│   └── seeders/
│       └── RolesAndPermissionsSeeder.php     # ⭐ الملف الأهم
│
├── routes/
│   └── web.php                               # حماية المسارات
│
├── resources/views/
│   ├── auth/
│   │   └── login.blade.php                   # صفحة تسجيل الدخول
│   ├── components/
│   │   └── nav.blade.php                     # شريط التنقل
│   ├── errors/
│   │   └── 403.blade.php                     # صفحة الوصول المرفوض
│   └── {resource}/
│       └── index.blade.php                   # صفحات الموارد
│
└── bootstrap/
    └── app.php                               # تسجيل middleware
⚡ أوامر سريعة
# مسح كاش الصلاحيات
php artisan permission:cache-reset
# تشغيل Seeder
php artisan db:seed --class=RolesAndPermissionsSeeder
# إعادة تشغيل كل شيء (يحذف البيانات!)
php artisan migrate:fresh --seed
# مسح جميع الكاش
php artisan config:clear
php artisan cache:clear
php artisan route:clear
# فتح Tinker للتجربة
php artisan tinker
🔑 الصلاحيات الحالية في النظام
الموارد والصلاحيات
المورد	الصلاحيات
mosques	view, create, edit, delete
members	view, create, edit, delete
housings	view, create, edit, delete
neighborhoods	view, create, edit, delete
provinces	view, create, edit, delete
categories	view, create, edit, delete
professions	view, create, edit, delete
الأدوار والصلاحيات
الدور	الصلاحيات
super-admin	جميع الصلاحيات
data-entry	view + create + edit (بدون delete)
reviewer	view فقط
👤 المستخدمين التجريبيين
الدور	البريد	كلمة المرور
Super Admin	
admin@example.com
password
Data Entry	
entry@example.com
password
Reviewer	
reviewer@example.com
password
📝 كود سريع للنسخ
إضافة صلاحيات جديدة في Seeder
// في RolesAndPermissionsSeeder.php
$permissions = [
    // ... الصلاحيات الموجودة
    'view reports', 'create reports', 'edit reports', 'delete reports',
];
// توزيع على data-entry
$role1->givePermissionTo('view reports');
$role1->givePermissionTo('create reports');
$role1->givePermissionTo('edit reports');
// توزيع على reviewer
$role2->givePermissionTo('view reports');
حماية المسارات
// في routes/web.php
Route::middleware('auth')->group(function () {
    Route::resource('reports', ReportController::class);
});
فحص الصلاحيات في Blade
{{-- زر الإضافة --}}
@can('create reports')
    <a href="{{ route('reports.create') }}">إضافة</a>
@endcan
{{-- زر التعديل --}}
@can('edit reports')
    <a href="{{ route('reports.edit', $report) }}">تعديل</a>
@endcan
{{-- زر الحذف --}}
@can('delete reports')
    <form action="{{ route('reports.destroy', $report) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">حذف</button>
    </form>
@endcan
فحص الصلاحيات في Controller
public function edit(Report $report)
{
    $this->authorize('edit reports');
    
    return view('reports.edit', compact('report'));
}
🎯 قائمة مرجعية سريعة
عند إضافة مورد جديد:

 إضافة الصلاحيات في 
RolesAndPermissionsSeeder.php
 توزيع الصلاحيات على الأدوار
 تشغيل php artisan db:seed
 حماية المسارات في 
routes/web.php
 إضافة @can في صفحة Index
 إضافة @can في صفحة Edit
 اختبار مع كل دور
🔧 استكشاف الأخطاء السريع
المشكلة: "There is no permission named X"
php artisan tinker
Permission::create(['name' => 'اسم_الصلاحية']);
المشكلة: الصلاحيات لا تعمل
php artisan permission:cache-reset
php artisan config:clear
php artisan cache:clear
المشكلة: Super Admin لا يملك كل الصلاحيات
أضف في 
app/Providers/AppServiceProvider.php
:

use Illuminate\Support\Facades\Gate;
public function boot(): void
{
    Gate::before(function ($user, $ability) {
        return $user->hasRole('super-admin') ? true : null;
    });
}
📚 الملفات الإرشادية
الدليل الشامل: 
permissions_complete_guide.md

شرح مفصل للنظام
خطوات إنشاء نظام من الصفر
جميع الملفات والتفاصيل
الأمثلة العملية: 
permissions_examples.md

سيناريوهات واقعية
أكواد جاهزة للنسخ
حالات استخدام متقدمة
المرجع السريع: permissions_quick_reference.md (هذا الملف)

نظرة سريعة
أوامر مفيدة
قوائم مرجعية
🎓 نصائح مهمة
دائماً امسح الكاش بعد تغيير الصلاحيات
اختبر مع جميع الأدوار قبل النشر
استخدم @can في Blade لإخفاء العناصر
احمِ المسارات بـ middleware
أنشئ صفحة 403 مخصصة
📞 روابط مفيدة
Spatie Permission Docs
Laravel Authorization
آخر تحديث: 2025-12-05