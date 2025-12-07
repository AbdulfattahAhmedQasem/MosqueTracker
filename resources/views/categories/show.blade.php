<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفاصيل الفئة - {{ $category->name }}</title>
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
            <h1 class="text-4xl font-bold text-slate-800 mb-2">تفاصيل الفئة</h1>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- أزرار الإجراءات -->
        <div class="mb-6 flex gap-3 flex-wrap">
            <a href="{{ route('categories.edit', $category) }}"
               class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل البيانات
            </a>
        </div>

        <!-- معلومات الفئة -->
        <div class="bg-slate-50 rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">المعلومات الأساسية</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-600 mb-1">اسم الفئة</p>
                    <p class="font-semibold text-slate-800">{{ $category->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">عدد المنسوبين</p>
                    <p class="font-semibold text-slate-800">{{ $category->members->count() }}</p>
                </div>
                @if($category->description)
                <div class="col-span-2">
                    <p class="text-sm text-slate-600 mb-1">الوصف</p>
                    <p class="font-semibold text-slate-800">{{ $category->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- قائمة المنسوبين -->
        @if($category->members->count() > 0)
        <div class="bg-white border border-slate-200 rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">المنسوبين</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الاسم</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المسجد</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->members as $member)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-slate-700 font-medium">{{ $member->name }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $member->mosque->name ?? '-' }}</td>
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

