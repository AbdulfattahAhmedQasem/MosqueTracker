<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - غير مصرح</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            width: 100%;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            direction: rtl;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center p-6">
    
    <div class="text-center max-w-2xl">
        <!-- Error Icon -->
        <div class="flex justify-center mb-8">
            <div class="w-32 h-32 bg-red-100 rounded-full flex items-center justify-center">
                <svg class="w-20 h-20 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>

        <!-- Error Code -->
        <h1 class="text-8xl font-bold text-slate-800 mb-4">403</h1>
        
        <!-- Error Message -->
        <h2 class="text-3xl font-bold text-slate-700 mb-4">غير مصرح بالوصول</h2>
        <p class="text-lg text-slate-600 mb-8">
            عذراً، ليس لديك الصلاحيات الكافية للوصول إلى هذه الصفحة.
            <br>
            يرجى التواصل مع المسؤول إذا كنت تعتقد أن هذا خطأ.
        </p>

        <!-- Action Buttons -->
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="{{ route('dashboard') }}" 
               class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                العودة للرئيسية
            </a>
            <button onclick="history.back()" 
                    class="px-6 py-3 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                رجوع
            </button>
        </div>

        <!-- Additional Info -->
        <div class="mt-12 p-6 bg-white rounded-xl shadow-lg">
            <h3 class="text-lg font-semibold text-slate-800 mb-3">معلومات إضافية</h3>
            <div class="text-right text-sm text-slate-600 space-y-2">
                <p><strong>المستخدم:</strong> {{ auth()->user()->name ?? 'غير معروف' }}</p>
                <p><strong>البريد الإلكتروني:</strong> {{ auth()->user()->email ?? 'غير معروف' }}</p>
                @if(auth()->check())
                    <p><strong>الأدوار:</strong> 
                        @foreach(auth()->user()->roles as $role)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ $role->name }}</span>
                        @endforeach
                    </p>
                @endif
            </div>
        </div>
    </div>

</body>
</html>
