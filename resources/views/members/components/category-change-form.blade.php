<form @submit.prevent="handleCategoryChangeSubmit($event)">
    <div class="space-y-4 mb-6">
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-700 mb-1">المنسوب</p>
            <p class="font-bold text-blue-900" x-text="selectedMember?.name"></p>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">الفئة الحالية</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.category"></p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                الفئة الجديدة *
            </label>
            <select
                x-model="formData.newCategory"
                required
                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">اختر الفئة</option>
                <option value="أ">أ</option>
                <option value="ب">ب</option>
                <option value="ج">ج</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                سبب التغيير *
            </label>
            <textarea
                x-model="formData.reason"
                required
                rows="3"
                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="اكتب سبب تغيير الفئة..."
            ></textarea>
        </div>
    </div>

    <div class="flex gap-3 justify-end">
        <button
            type="button"
            @click="closeModal()"
            class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
        >
            إلغاء
        </button>
        <button
            type="submit"
            class="px-6 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            حفظ التغيير
        </button>
    </div>
</form>

