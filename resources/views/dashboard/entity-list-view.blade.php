<div x-data="{
    getColumns() {
        const entityType = '{{ $entityType }}';
        const columnsMap = {
            'mosques': [
                { label: 'الاسم', field: 'name' },
                { label: 'النوع', field: 'type' },
                { label: 'الحي', field: 'neighborhood' },
                { label: 'المحافظة', field: 'province' }
            ],
            'provinces': [
                { label: 'الاسم', field: 'name' },
                { label: 'عدد الأحياء', field: 'neighborhoods_count' },
                { label: 'عدد المساجد', field: 'mosques_count' }
            ],
            'neighborhoods': [
                { label: 'الاسم', field: 'name' },
                { label: 'المحافظة', field: 'province' },
                { label: 'عدد المساجد', field: 'mosques_count' }
            ],
            'housing': [
                { label: 'اسم السكن', field: 'name' },
                { label: 'المسجد', field: 'mosque' }
            ]
        };
        return columnsMap[entityType] || [];
    },
    getFieldValue(item, field) {
        if (field === 'neighborhood') {
            return item.neighborhood?.name || item.neighborhood || '';
        }
        if (field === 'province') {
            return item.neighborhood?.province?.name || item.province?.name || item.province || '';
        }
        if (field === 'mosque') {
            return item.mosque?.name || item.mosque || '';
        }
        return item[field] || '';
    }
}">
    <div>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-4xl font-bold text-slate-800 mb-2">{{ $title }}</h1>
            <a href="{{ route('dashboard') }}" 
               class="text-blue-500 hover:text-blue-600 flex items-center gap-1"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                العودة للرئيسية
            </a>
            </div>
            <button
                @click="openModal('add', null, '{{ $entityType }}')"
                class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2 shadow-lg"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                إضافة جديد
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <template x-for="(col, idx) in getColumns()" :key="idx">
                                <th class="px-6 py-4 text-right text-sm font-semibold text-slate-700" x-text="col.label"></th>
                            </template>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="item in entities['{{ $entityType }}']" :key="item.id">
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <template x-for="(col, idx) in getColumns()" :key="idx">
                                    <td class="px-6 py-4 text-slate-700" x-text="getFieldValue(item, col.field)"></td>
                                </template>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button
                                            @click="openModal('view', item, '{{ $entityType }}')"
                                            class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                            title="عرض"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="openModal('edit', item, '{{ $entityType }}')"
                                            class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors"
                                            title="تعديل"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="openModal('delete', item, '{{ $entityType }}')"
                                            class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                            title="حذف"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

