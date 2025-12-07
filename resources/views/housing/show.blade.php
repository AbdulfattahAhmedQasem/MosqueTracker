<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تفاصيل السكن - {{ $housing->name }}</title>
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
            <h1 class="text-4xl font-bold text-slate-800 mb-2">تفاصيل السكن</h1>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- أزرار الإجراءات -->
        <div class="mb-6 flex gap-3 flex-wrap">
            <a href="{{ route('housing.edit', $housing) }}"
               class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل البيانات
            </a>
        </div>

        <!-- معلومات السكن -->
        <div class="bg-slate-50 rounded-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">المعلومات الأساسية</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-slate-600 mb-1">اسم السكن</p>
                    <p class="font-semibold text-slate-800">{{ $housing->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-600 mb-1">المسجد</p>
                    <p class="font-semibold text-slate-800">{{ $housing->mosque->name ?? '-' }}</p>
                </div>
                @if($housing->member)
                <div>
                    <p class="text-sm text-slate-600 mb-1">المنسوب</p>
                    <p class="font-semibold text-slate-800">
                        <a href="{{ route('members.show', $housing->member) }}" class="text-blue-600 hover:text-blue-700">
                            {{ $housing->member->name }}
                        </a>
                    </p>
                </div>
                @else
                <div>
                    <p class="text-sm text-slate-600 mb-1">المنسوب</p>
                    <p class="font-semibold text-slate-400">غير محجوز</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>

