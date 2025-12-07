<form @submit.prevent="handleHousingSubmit($event)"
      x-data="{
          handleHousingSubmit(event) {
              const formData = {
                  name: event.target.elements.name.value,
                  mosque: event.target.elements.mosque.value
              };
              // سيتم إضافة handleHousingSubmit في Alpine.js
              alert('سيتم إضافة وظيفة حفظ السكن لاحقاً');
              closeModal();
          }
      }">
    <div class="space-y-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">اسم السكن *</label>
            <input
                type="text"
                name="name"
                required
                placeholder="مثال: سكن النور 1"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">المسجد *</label>
            <select
                name="mosque"
                required
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">اختر المسجد</option>
                <template x-for="mosque in entities.mosques" :key="mosque.id">
                    <option :value="mosque.name" x-text="mosque.name"></option>
                </template>
            </select>
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
            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
        >
            حفظ
        </button>
    </div>
</form>

