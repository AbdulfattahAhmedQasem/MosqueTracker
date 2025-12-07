<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة المنسوبين - نظام إدارة المساجد</title>
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
    
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-slate-800 mb-2">إدارة المنسوبين</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('members.export.all') }}"
                   class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    تصدير الكل
                </a>
                @if(request()->hasAny(['name', 'mosque_id', 'neighborhood_id', 'province_id', 'category_id', 'profession_id', 'status', 'date_from', 'date_to']))
                <a href="{{ route('members.export', request()->query()) }}"
                   class="bg-purple-500 text-white px-6 py-3 rounded-lg hover:bg-purple-600 transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    تصدير المفلتر
                </a>
                @endif
                @can('create members')
                <a href="{{ route('members.create') }}"
                   class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إضافة منسوب جديد
                </a>
                @endcan
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- زر إظهار/إخفاء الفلترة -->
        <div class="mb-4">
            <button type="button" 
                    id="toggleFilterBtn"
                    onclick="toggleFilters()"
                    class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-300 transition-all flex items-center gap-2">
                <svg id="filterIcon" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <span id="filterText">إظهار البحث والفلترة</span>
            </button>
        </div>

        <!-- نموذج البحث والفلترة -->
        <div id="filterForm" class="bg-white rounded-xl shadow-lg p-6 mb-6 hidden">
            <h2 class="text-2xl font-bold text-slate-800 mb-4">البحث والفلترة</h2>
            <form method="GET" action="{{ route('members.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- البحث بالاسم -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">الاسم</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ $filters['name'] ?? '' }}"
                               placeholder="ابحث بالاسم..."
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- الفلترة حسب المسجد -->
                    <div>
                        <label for="mosque_id" class="block text-sm font-medium text-slate-700 mb-2">المسجد</label>
                        <select id="mosque_id" 
                                name="mosque_id" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع المساجد</option>
                            @foreach($mosques as $mosque)
                                <option value="{{ $mosque->id }}" {{ ($filters['mosque_id'] ?? '') == $mosque->id ? 'selected' : '' }}>
                                    {{ $mosque->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الفلترة حسب الحي -->
                    <div>
                        <label for="neighborhood_id" class="block text-sm font-medium text-slate-700 mb-2">الحي</label>
                        <select id="neighborhood_id" 
                                name="neighborhood_id" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع الأحياء</option>
                            @foreach($neighborhoods as $neighborhood)
                                <option value="{{ $neighborhood->id }}" {{ ($filters['neighborhood_id'] ?? '') == $neighborhood->id ? 'selected' : '' }}>
                                    {{ $neighborhood->name }} - {{ $neighborhood->province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الفلترة حسب المحافظة -->
                    <div>
                        <label for="province_id" class="block text-sm font-medium text-slate-700 mb-2">المحافظة</label>
                        <select id="province_id" 
                                name="province_id" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع المحافظات</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}" {{ ($filters['province_id'] ?? '') == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الفلترة حسب الفئة -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-2">الفئة</label>
                        <select id="category_id" 
                                name="category_id" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع الفئات</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الفلترة حسب المهنة -->
                    <div>
                        <label for="profession_id" class="block text-sm font-medium text-slate-700 mb-2">المهنة</label>
                        <select id="profession_id" 
                                name="profession_id" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع المهن</option>
                            @foreach($professions as $profession)
                                <option value="{{ $profession->id }}" {{ ($filters['profession_id'] ?? '') == $profession->id ? 'selected' : '' }}>
                                    {{ $profession->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الفلترة حسب الحالة -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-2">حالة المنسوب</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">جميع الحالات</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ ($filters['status'] ?? '') == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- الفلترة حسب تاريخ التعيين من -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-slate-700 mb-2">من تاريخ</label>
                        <input type="date" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ $filters['date_from'] ?? '' }}"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- الفلترة حسب تاريخ التعيين إلى -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-slate-700 mb-2">إلى تاريخ</label>
                        <input type="date" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ $filters['date_to'] ?? '' }}"
                               class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- أزرار البحث والمسح -->
                <div class="flex gap-3 mt-6">
                    <button type="submit" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        بحث
                    </button>
                    <a href="{{ route('members.index') }}" 
                       class="bg-slate-200 text-slate-700 px-6 py-2 rounded-lg hover:bg-slate-300 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        مسح الفلاتر
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="text-slate-700">
                    <span class="font-semibold">عدد النتائج:</span>
                    <span class="text-blue-600 font-bold">{{ $members->count() }}</span>
                    @if(request()->hasAny(['name', 'mosque_id', 'neighborhood_id', 'province_id', 'category', 'profession', 'status', 'date_from', 'date_to']))
                        <span class="text-sm text-slate-500 mr-2">(نتائج مفلترة)</span>
                    @endif
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الاسم</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المسجد</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">السكن</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الفئة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المهنة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الحالة</th>
                            <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-slate-700 font-medium">{{ $member->name }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $member->mosque->name ?? '-' }}</td>
                                <td class="px-4 py-4 text-slate-700">{{ $member->housing->name ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">{{ $member->category->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">{{ $member->profession->name ?? '-' }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $member->status === 'نشط' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $member->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex gap-1">
                                        <a href="{{ route('members.show', $member) }}"
                                           class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="عرض">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        @can('edit members')
                                        <a href="{{ route('members.edit', $member) }}"
                                           class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors" title="تعديل">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('members.transfer', $member) }}"
                                           class="p-2 text-purple-500 hover:bg-purple-50 rounded-lg transition-colors" title="نقل المنسوب">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                            </svg>
                                        </a>
                                        @endcan
                                        @can('delete members')
                                        <form action="{{ route('members.destroy', $member) }}" method="POST" class="inline"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المنسوب؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="حذف">
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
                                <td colspan="7" class="px-4 py-8 text-center text-slate-500">لا يوجد منسوبين مسجلين</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const filterForm = document.getElementById('filterForm');
            const filterText = document.getElementById('filterText');
            const filterIcon = document.getElementById('filterIcon');
            
            if (filterForm.classList.contains('hidden')) {
                filterForm.classList.remove('hidden');
                filterText.textContent = 'إخفاء البحث والفلترة';
                filterIcon.classList.add('rotate-180');
            } else {
                filterForm.classList.add('hidden');
                filterText.textContent = 'إظهار البحث والفلترة';
                filterIcon.classList.remove('rotate-180');
            }
        }

        // إظهار النموذج تلقائياً إذا كانت هناك فلاتر نشطة
        document.addEventListener('DOMContentLoaded', function() {
            const hasActiveFilters = {{ request()->hasAny(['name', 'mosque_id', 'neighborhood_id', 'province_id', 'category_id', 'profession_id', 'status', 'date_from', 'date_to']) ? 'true' : 'false' }};
            
            if (hasActiveFilters) {
                const filterForm = document.getElementById('filterForm');
                const filterText = document.getElementById('filterText');
                const filterIcon = document.getElementById('filterIcon');
                
                filterForm.classList.remove('hidden');
                filterText.textContent = 'إخفاء البحث والفلترة';
                filterIcon.classList.add('rotate-180');
            }
        });
    </script>
</body>
</html>

