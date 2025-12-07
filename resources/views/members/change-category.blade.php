<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تغيير الفئة - {{ $member->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0; min-height: 100vh; width: 100%;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; direction: rtl;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    @include('components.nav')
    
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">تغيير الفئة</h1>
            <a href="{{ route('members.show', $member) }}" class="text-blue-500 hover:text-blue-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                العودة لتفاصيل المنسوب
            </a>
        </div>

        @if($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- معلومات المنسوب -->
        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-slate-800 mb-2">معلومات المنسوب</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-600">الاسم:</span>
                    <span class="font-semibold text-slate-800">{{ $member->name }}</span>
                </div>
                <div>
                    <span class="text-slate-600">المسجد:</span>
                    <span class="font-semibold text-slate-800">{{ $member->mosque->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-600">الفئة الحالية:</span>
                    <span class="font-semibold text-slate-800 text-lg">{{ $member->category }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('members.change-category.store', $member) }}" method="POST">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">الفئة الجديدة *</label>
                    <select
                        name="new_category"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    >
                        <option value="">اختر الفئة</option>
                        <option value="أ" {{ old('new_category') == 'أ' ? 'selected' : '' }}>أ</option>
                        <option value="ب" {{ old('new_category') == 'ب' ? 'selected' : '' }}>ب</option>
                        <option value="ج" {{ old('new_category') == 'ج' ? 'selected' : '' }}>ج</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">سبب التغيير *</label>
                    <textarea
                        name="reason"
                        required
                        rows="4"
                        placeholder="اكتب سبب تغيير الفئة..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                    >{{ old('reason') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ route('members.show', $member) }}" 
                   class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    حفظ التغيير
                </button>
            </div>
        </form>
    </div>
</body>
</html>

