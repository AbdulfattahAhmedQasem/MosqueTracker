<form @submit.prevent="handleTransferSubmit($event)">
    <div class="space-y-4 mb-6">
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-blue-700 mb-1">المنسوب</p>
            <p class="font-bold text-blue-900" x-text="selectedMember?.name"></p>
        </div>

        <div class="p-4 bg-slate-50 border border-slate-200 rounded-lg">
            <p class="text-sm text-slate-600 mb-1">المسجد الحالي</p>
            <p class="font-semibold text-slate-800" x-text="selectedMember?.mosque?.name || selectedMember?.mosque"></p>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                المسجد الجديد *
            </label>
            <select
                name="toMosque"
                required
                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">اختر المسجد</option>
                <template x-for="mosque in entities.mosques" :key="mosque.id">
                    <template x-if="(mosque.name || mosque) !== (selectedMember?.mosque?.name || selectedMember?.mosque)">
                        <option :value="mosque.name || mosque" x-text="mosque.name || mosque"></option>
                    </template>
                </template>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                سبب النقل *
            </label>
            <textarea
                name="reason"
                required
                rows="3"
                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="اكتب سبب النقل..."
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
            class="px-6 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            تنفيذ النقل
        </button>
    </div>
</form>

