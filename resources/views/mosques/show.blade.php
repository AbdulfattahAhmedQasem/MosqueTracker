<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفاصيل المسجد - {{ $mosque->name }}</title>
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
    
    <div class="bg-white rounded-xl shadow-lg p-6 max-w-6xl mx-auto">
        <div class="mb-6">
            <h1 class="text-4xl font-bold text-slate-800 mb-2">تفاصيل المسجد</h1>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- أزرار الإجراءات -->
        <div class="mb-6 flex gap-3 flex-wrap">
            @if($mosque->members->count() > 0)
            <a href="{{ route('mosques.members.export', $mosque) }}"
               class="bg-indigo-500 text-white px-6 py-2 rounded-lg hover:bg-indigo-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                تصدير منسوبي المسجد
            </a>
            @endif
            <a href="{{ route('mosques.edit', $mosque) }}"
               class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل البيانات
            </a>
        </div>

        <!-- معلومات المسجد -->
        <div class="bg-slate-50 rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">المعلومات الأساسية</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-600 mb-1">اسم المسجد</p>
                    <p class="font-semibold text-slate-800">{{ $mosque->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">نوع المسجد</p>
                    <p class="font-semibold text-slate-800">{{ $mosque->type }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">المحافظة</p>
                    <p class="font-semibold text-slate-800">{{ $mosque->neighborhood->province->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">الحي</p>
                    <p class="font-semibold text-slate-800">{{ $mosque->neighborhood->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">عدد السكن</p>
                    <p class="font-semibold text-slate-800">{{ $mosque->housings->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">عدد المنسوبين</p>
                    <p class="font-semibold text-slate-800">{{ $mosque->members->count() }}</p>
                </div>
            </div>
        </div>

        <!-- قائمة السكن -->
        @if($mosque->housings->count() > 0)
        <div class="bg-white border border-slate-200 rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">السكن المرتبط</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">اسم السكن</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المنسوب</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mosque->housings as $housing)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-slate-700 font-medium">{{ $housing->name }}</td>
                                <td class="px-4 py-4 text-slate-700">
                                    @if($housing->member)
                                        <a href="{{ route('members.show', $housing->member) }}" class="text-blue-600 hover:text-blue-700">
                                            {{ $housing->member->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">غير محجوز</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('housing.show', $housing) }}"
                                       class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="عرض">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- قائمة المنسوبين -->
        @if($mosque->members->count() > 0)
        <div class="bg-white border border-slate-200 rounded-lg p-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">المنسوبين</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الاسم</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الفئة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المهنة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الحالة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mosque->members as $member)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-slate-700 font-medium">{{ $member->name }}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">{{ $member->category }}</span>
                                </td>
                                <td class="px-4 py-4 text-slate-700">{{ $member->profession }}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $member->status === 'نشط' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $member->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('members.show', $member) }}"
                                       class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="عرض">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</body>
</html>

