<form @submit.prevent="handleMemberFormSubmit($event)"
      x-data="{
          selectedMosque: formData?.mosque || formData?.mosque?.name || '',
          get availableHousing() {
              if (!this.selectedMosque || !entities.housing) return [];
              return entities.housing.filter(h => {
                  const mosqueName = h.mosque?.name || h.mosque;
                  return mosqueName === this.selectedMosque;
              });
          },
          handleMosqueChange(event) {
              this.selectedMosque = event.target.value;
              // إعادة تعيين السكن عند تغيير المسجد
              formData.housing = '';
          },
          handleMemberFormSubmit(event) {
              const formData = {
                  name: event.target.elements.name.value,
                  mosque: event.target.elements.mosque.value,
                  housing: event.target.elements.housing.value || null,
                  category: event.target.elements.category.value,
                  profession: event.target.elements.profession.value,
                  employeeNumber: event.target.elements.employeeNumber.value,
                  phone: event.target.elements.phone.value,
                  nationalId: event.target.elements.nationalId.value,
                  appointmentDecision: event.target.elements.appointmentDecision.value || '',
                  appointmentDate: event.target.elements.appointmentDate.value,
                  status: event.target.elements.status.value
              };
              submitMemberForm(formData);
          }
      }"
      x-init="
          if (formData && formData.mosque) {
              selectedMosque = formData.mosque?.name || formData.mosque || '';
          }
      ">
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">الاسم الكامل *</label>
            <input
                type="text"
                name="name"
                required
                :value="formData?.name || ''"
                placeholder="مثال: أحمد محمد علي"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">المسجد *</label>
            <select 
                name="mosque" 
                required 
                x-model="selectedMosque"
                @change="handleMosqueChange($event)"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">اختر المسجد</option>
                <template x-for="mosque in entities.mosques" :key="mosque.id">
                    <option :value="mosque.name" x-text="`${mosque.name} (${mosque.type})`"></option>
                </template>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">السكن (اختياري)</label>
            <select 
                name="housing"
                :value="formData?.housing?.name || formData?.housing || ''"
                :disabled="!selectedMosque || availableHousing.length === 0"
                :class="!selectedMosque || availableHousing.length === 0 ? 'bg-slate-100 cursor-not-allowed' : ''"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="" x-text="!selectedMosque ? 'اختر المسجد أولاً' : (availableHousing.length === 0 ? 'لا يوجد سكن متاح لهذا المسجد' : 'بدون سكن')"></option>
                <template x-for="h in availableHousing" :key="h.id">
                    <option :value="h.name" x-text="h.name"></option>
                </template>
            </select>
            <template x-if="selectedMosque && availableHousing.length > 0">
                <p class="text-xs text-slate-500 mt-1">
                    <span x-text="availableHousing.length"></span> سكن متاح لهذا المسجد
                </p>
            </template>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">الفئة *</label>
            <select 
                name="category" 
                required 
                :value="formData?.category || ''"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">اختر الفئة</option>
                <option value="أ">أ</option>
                <option value="ب">ب</option>
                <option value="ج">ج</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">المهنة *</label>
            <select 
                name="profession" 
                required 
                :value="formData?.profession || ''"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">اختر المهنة</option>
                <option value="إمام">إمام</option>
                <option value="خطيب">خطيب</option>
                <option value="مؤذن">مؤذن</option>
                <option value="عامل نظافة">عامل نظافة</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">الرقم الوظيفي *</label>
            <input
                type="text"
                name="employeeNumber"
                required
                :value="formData?.employee_number || formData?.employeeNumber || ''"
                placeholder="E-2024-001"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">رقم الهاتف *</label>
            <input
                type="tel"
                name="phone"
                required
                :value="formData?.phone || ''"
                placeholder="05xxxxxxxx"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">رقم الهوية *</label>
            <input
                type="text"
                name="nationalId"
                required
                :value="formData?.national_id || formData?.nationalId || ''"
                placeholder="1234567890"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">قرار التعيين</label>
            <input
                type="text"
                name="appointmentDecision"
                :value="formData?.appointment_decision || formData?.appointmentDecision || ''"
                placeholder="قرار رقم 123/2024"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">تاريخ التعيين *</label>
            <input
                type="date"
                name="appointmentDate"
                required
                :value="formData?.appointment_date || formData?.appointmentDate || ''"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">حالة المنسوب *</label>
            <select 
                name="status" 
                required 
                :value="formData?.status || 'نشط'"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="نشط">نشط</option>
                <option value="غير نشط">غير نشط</option>
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

