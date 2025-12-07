<form action="{{ route('documents.store', $member ?? 'member') }}" 
      method="POST" 
      enctype="multipart/form-data"
      class="space-y-4"
      x-data="{
          documentName: '',
          documentType: '',
          notes: '',
          selectedFile: null,
          fileName: '',
          uploadDate: new Date().toISOString().split('T')[0],
          documentTypes: [
              { value: 'طي_القيد', label: 'وثيقة طي القيد' },
              { value: 'تعيين_السكن', label: 'وثيقة تعيين السكن' },
              { value: 'عقد_السكن', label: 'عقد السكن' },
              { value: 'الغياب', label: 'وثيقة الغياب' },
              { value: 'أخرى', label: 'وثيقة أخرى' }
          ],
          handleFileChange(event) {
              const file = event.target.files[0];
              if (file) {
                  this.selectedFile = file;
                  this.fileName = file.name;
              }
          },
          clearFile() {
              this.selectedFile = null;
              this.fileName = '';
              const fileInput = $refs.fileInput;
              if (fileInput) {
                  fileInput.value = '';
              }
          },
          formatFileSize(bytes) {
              if (!bytes) return '';
              return (bytes / 1024).toFixed(2) + ' KB';
          },
          handleSubmit(event) {
              if (!this.documentType || !this.selectedFile) {
                  event.preventDefault();
                  alert('يرجى اختيار نوع الوثيقة ورفع الملف');
                  return false;
              }
              return true;
          }
      }"
      @submit="handleSubmit($event)">
    @csrf
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">
            نوع الوثيقة *
        </label>
        <select
            name="document_type"
            x-model="documentType"
            required
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
            <option value="">اختر نوع الوثيقة</option>
            <template x-for="type in documentTypes" :key="type.value">
                <option :value="type.value" x-text="type.label"></option>
            </template>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">
            اسم الوثيقة (اختياري)
        </label>
        <input
            type="text"
            name="document_name"
            x-model="documentName"
            placeholder="مثال: وثيقة طي القيد - 2024"
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
        <p class="text-xs text-slate-500 mt-1">
            إذا تركتها فارغة، سيتم استخدام نوع الوثيقة كاسم
        </p>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">
            رفع الملف *
        </label>
        <div class="flex items-center gap-2">
            <label class="flex-1 cursor-pointer">
                <div class="flex items-center justify-center gap-2 px-4 py-3 border-2 border-dashed border-slate-300 rounded-lg hover:border-blue-500 transition-colors">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <span class="text-slate-600" x-text="fileName || 'اختر ملف للرفع'"></span>
                </div>
                <input
                    type="file"
                    name="file"
                    x-ref="fileInput"
                    @change="handleFileChange($event)"
                    required
                    class="hidden"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                />
            </label>
            <template x-if="fileName">
                <button
                    type="button"
                    @click="clearFile()"
                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </template>
        </div>
        <template x-if="selectedFile">
            <p class="text-xs text-slate-500 mt-1">
                الملف: <span x-text="fileName"></span> (<span x-text="formatFileSize(selectedFile.size)"></span>)
            </p>
        </template>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">
            تاريخ الرفع *
        </label>
        <input
            type="date"
            name="upload_date"
            x-model="uploadDate"
            required
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-2">
            ملاحظات
        </label>
        <textarea
            name="notes"
            x-model="notes"
            rows="3"
            placeholder="أضف أي ملاحظات حول الوثيقة..."
            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        ></textarea>
    </div>

    <div class="flex gap-3 justify-end pt-4 border-t border-slate-200">
        <button
            type="button"
            @click="$dispatch('close-upload-form')"
            class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
        >
            إلغاء
        </button>
        <button
            type="submit"
            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            رفع الوثيقة
        </button>
    </div>
</form>

