<div x-show="showModal" 
     x-cloak
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
     x-transition:enter="ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="closeModal()">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        <div class="flex items-center justify-between p-6 border-b border-slate-200 sticky top-0 bg-white">
            <h2 class="text-2xl font-bold text-slate-800" 
                x-text="modalType === 'add' && currentView === 'members' ? 'إضافة منسوب جديد' :
                        modalType === 'edit' && currentView === 'members' ? 'تعديل بيانات المنسوب' :
                        modalType === 'view' ? 'عرض التفاصيل' :
                        modalType === 'delete' ? 'تأكيد الحذف' :
                        modalType === 'transfer' ? 'نقل منسوب' :
                        modalType === 'changeCategory' ? 'تغيير فئة المنسوب' :
                        modalType === 'transferHistory' ? 'سجل التحويلات' :
                        modalType === 'add' ? 'إضافة جديد' :
                        modalType === 'edit' ? 'تعديل' :
                        ''"></h2>
            <button
                @click="closeModal()"
                class="text-slate-400 hover:text-slate-600 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <template x-if="modalType === 'delete'">
                <div>
                    <p class="text-lg text-slate-700 mb-6">
                        هل أنت متأكد من حذف هذا العنصر؟ لا يمكن التراجع عن هذا الإجراء.
                    </p>
                    <div class="flex gap-3 justify-end">
                        <button
                            @click="closeModal()"
                            class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                        >
                            إلغاء
                        </button>
                        <button
                            @click="handleDelete(currentView, selectedEntity?.id)"
                            class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                        >
                            حذف
                        </button>
                    </div>
                </div>
            </template>

            <template x-if="modalType === 'view' && currentView === 'members'">
                @include('members.components.member-details-view')
            </template>

            <template x-if="modalType === 'transfer'">
                @include('members.components.transfer-form')
            </template>

            <template x-if="modalType === 'changeCategory'">
                @include('members.components.category-change-form')
            </template>

            <template x-if="modalType === 'transferHistory'">
                @include('members.components.transfer-history-view')
            </template>

            <template x-if="(modalType === 'add' || modalType === 'edit') && currentView === 'members'">
                @include('members.components.member-form')
            </template>

            <template x-if="modalType === 'add' && currentView === 'housing'">
                @include('housing.components.housing-form')
            </template>

            <template x-if="modalType !== 'delete' && modalType !== 'view' && modalType !== 'transfer' && modalType !== 'changeCategory' && modalType !== 'transferHistory' && !((modalType === 'add' || modalType === 'edit') && currentView === 'members') && modalType !== 'add' && currentView !== 'housing'">
                <div>
                    <div class="space-y-4 mb-6">
                        <template x-if="selectedEntity">
                            <template x-for="(value, key) in selectedEntity" :key="key">
                                <template x-if="key !== 'id'">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2" x-text="key"></label>
                                        <input 
                                            type="text" 
                                            :value="value" 
                                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                        />
                                    </div>
                                </template>
                            </template>
                        </template>
                    </div>
                    <div class="flex gap-3 justify-end">
                        <button 
                            @click="closeModal()" 
                            class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                        >
                            إلغاء
                        </button>
                        <button 
                            @click="closeModal()" 
                            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                        >
                            حفظ
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

