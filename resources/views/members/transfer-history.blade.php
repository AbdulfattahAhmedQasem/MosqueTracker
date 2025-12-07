<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>سجل التحويلات - {{ $member->name }}</title>
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
            <h1 class="text-4xl font-bold text-slate-800 mb-2">سجل التحويلات</h1>
            <a href="{{ route('members.show', $member) }}" class="text-blue-500 hover:text-blue-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                العودة لتفاصيل المنسوب
            </a>
        </div>

        <!-- معلومات المنسوب -->
        <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-slate-600">الاسم:</span>
                    <span class="font-semibold text-slate-800">{{ $member->name }}</span>
                </div>
                <div>
                    <span class="text-slate-600">المسجد الحالي:</span>
                    <span class="font-semibold text-slate-800">{{ $member->mosque->name ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-slate-600">الفئة الحالية:</span>
                    <span class="font-semibold text-slate-800">{{ $member->category->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        @if($transferHistories->isEmpty())
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-8 text-center">
                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-slate-500">لا يوجد سجل تحويلات لهذا المنسوب</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">التاريخ</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">من مسجد</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">إلى مسجد</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الفئة القديمة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الفئة الجديدة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">نفذ بواسطة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">السبب</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transferHistories as $history)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-slate-700">
                                    {{ $history->transfer_date->format('Y-m-d') }}
                                    <div class="text-xs text-slate-500">
                                        {{ $history->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-700">{{ $history->from_mosque }}</td>
                                <td class="px-4 py-4 text-slate-700 font-medium">{{ $history->to_mosque }}</td>
                                <td class="px-4 py-4">
                                    @if($history->old_category)
                                        <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-medium">
                                            {{ $history->old_category }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($history->new_category)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                            {{ $history->new_category }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-700">{{ $history->transferred_by }}</td>
                                <td class="px-4 py-4 text-slate-600 text-sm max-w-xs">
                                    <div class="truncate" title="{{ $history->reason }}">
                                        {{ $history->reason }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>

