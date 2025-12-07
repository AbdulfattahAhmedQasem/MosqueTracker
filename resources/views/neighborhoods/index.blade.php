<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة الأحياء - نظام إدارة المساجد</title>
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
<body class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
    <!-- Navigation Menu -->
    @include('components.nav')

    <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-slate-800 mb-2">إدارة الأحياء</h1>
            </div>
            @can('create neighborhoods')
                <a href="{{ route('neighborhoods.create') }}"
                   class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة حي جديد
                </a>
            @endcan
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الاسم</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المحافظة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">عدد المساجد</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">عدد المنسوبين</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($neighborhoods as $neighborhood)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-slate-700 font-medium">{{ $neighborhood->name }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $neighborhood->province->name ?? '-' }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $neighborhood->mosques_count ?? 0 }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $neighborhood->members_count ?? 0 }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex gap-1">
                                        <a href="{{ route('neighborhoods.show', $neighborhood) }}"
                                           class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="عرض">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @can('edit neighborhoods')
                                            <a href="{{ route('neighborhoods.edit', $neighborhood) }}"
                                               class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors"
                                               title="تعديل">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('delete neighborhoods')
                                            <form action="{{ route('neighborhoods.destroy', $neighborhood) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الحي؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="حذف">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                                    لا توجد أحياء مسجلة
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

