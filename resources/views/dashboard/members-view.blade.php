<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-4xl font-bold text-slate-800 mb-2">إدارة المنسوبين</h1>
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
            @click="openModal('add', null, 'members')"
            class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2 shadow-lg"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            إضافة منسوب جديد
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الاسم</th>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المسجد</th>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">السكن</th>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الفئة</th>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">المهنة</th>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">رقم الهاتف</th>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الحالة</th>
                        <th class="px-4 py-4 text-right text-sm font-semibold text-slate-700">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="member in entities.members" :key="member.id">
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4 text-slate-700 font-medium" x-text="member.name"></td>
                            <td class="px-4 py-4 text-slate-700" x-text="member.mosque?.name || member.mosque"></td>
                            <td class="px-4 py-4 text-slate-700" x-text="member.housing?.name || member.housing || '-'"></td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium" x-text="member.category"></span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium" x-text="member.profession"></span>
                            </td>
                            <td class="px-4 py-4 text-slate-700" x-text="member.phone"></td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium"
                                      :class="member.status === 'نشط' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                      x-text="member.status">
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex gap-1">
                                    <button
                                        @click="openModal('view', member, 'members')"
                                        class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="عرض"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="openModal('edit', member, 'members')"
                                        class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors"
                                        title="تعديل"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="handleTransfer(member)"
                                        class="p-2 text-purple-500 hover:bg-purple-50 rounded-lg transition-colors"
                                        title="نقل"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="handleCategoryChange(member)"
                                        class="p-2 text-orange-500 hover:bg-orange-50 rounded-lg transition-colors"
                                        title="تغيير الفئة"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="viewTransferHistory(member)"
                                        class="p-2 text-cyan-500 hover:bg-cyan-50 rounded-lg transition-colors"
                                        title="سجل التحويلات"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="openModal('delete', member, 'members')"
                                        class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                        title="حذف"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

