<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نقل المنسوب - {{ $member->name }}</title>
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
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6"
      x-data="{ changeCategory: {{ old('change_category') ? 'true' : 'false' }} }">
    @include('components.nav')
    
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">نقل المنسوب</h1>
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

        <!-- معلومات المنسوب الحالية -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-blue-900 mb-2">المعلومات الحالية</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-blue-700">الاسم:</span>
                    <span class="font-semibold text-blue-900">{{ $member->name }}</span>
                </div>
                <div>
                    <span class="text-blue-700">المسجد الحالي:</span>
                    <span class="font-semibold text-blue-900">{{ $member->mosque->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-blue-700">الفئة الحالية:</span>
                    <span class="font-semibold text-blue-900">{{ $member->category }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('members.transfer.store', $member) }}" method="POST">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">المسجد الجديد *</label>
                    <select
                        name="to_mosque_id"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                        <option value="">اختر المسجد الجديد</option>
                        @foreach($mosques as $mosque)
                            @if($mosque->id != $member->mosque_id)
                                <option value="{{ $mosque->id }}" {{ old('to_mosque_id') == $mosque->id ? 'selected' : '' }}>
                                    {{ $mosque->name }} ({{ $mosque->type }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ النقل *</label>
                    <input
                        type="date"
                        name="transfer_date"
                        value="{{ old('transfer_date', now()->format('Y-m-d')) }}"
                        required
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">سبب النقل *</label>
                    <textarea
                        name="reason"
                        required
                        rows="4"
                        placeholder="اكتب سبب نقل المنسوب..."
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >{{ old('reason') }}</textarea>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input
                            type="checkbox"
                            name="change_category"
                            value="1"
                            x-model="changeCategory"
                            class="w-4 h-4 text-orange-600 border-slate-300 rounded focus:ring-orange-500"
                        />
                        <span class="text-sm font-medium text-orange-900">تغيير الفئة أثناء النقل</span>
                    </label>
                    <div x-show="changeCategory" x-cloak class="mt-3">
                        <label class="block text-sm font-medium text-slate-700 mb-2">الفئة الجديدة *</label>
                        <select
                            name="new_category"
                            x-bind:required="changeCategory"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="">اختر الفئة</option>
                            <option value="أ" {{ old('new_category') == 'أ' ? 'selected' : '' }}>أ</option>
                            <option value="ب" {{ old('new_category') == 'ب' ? 'selected' : '' }}>ب</option>
                            <option value="ج" {{ old('new_category') == 'ج' ? 'selected' : '' }}>ج</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ route('members.show', $member) }}" 
                   class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors">
                    إلغاء
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    تنفيذ النقل
                </button>
            </div>
        </form>
    </div>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>

