<div class="space-y-4" 
     x-data="documentsManager()"
     x-init="init({{ json_encode($documents ?? []) }}, {{ json_encode($member ?? null) }})">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-800">إدارة الوثائق</h3>
            <p class="text-slate-600 text-sm mt-1">
                المنسوب: <span class="font-semibold" x-text="member?.name"></span>
            </p>
        </div>
        <button
            @click="showUploadForm = !showUploadForm"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-all flex items-center gap-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span x-text="showUploadForm ? 'إلغاء الرفع' : 'رفع وثيقة جديدة'"></span>
        </button>
    </div>

    <template x-if="showUploadForm">
        <div class="bg-white border border-slate-200 rounded-lg p-6">
            <h4 class="text-lg font-semibold text-slate-800 mb-4">رفع وثيقة جديدة</h4>
            @include('members.components.document-upload-form', ['member' => $member ?? null])
            <div @close-upload-form.window="showUploadForm = false"></div>
        </div>
    </template>

    <!-- قائمة الوثائق -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-lg font-semibold text-slate-800">
                الوثائق (<span x-text="filteredDocuments.length"></span>)
            </h4>
        </div>

        <template x-if="filteredDocuments.length > 0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="document in filteredDocuments" :key="document.id">
                    <div class="bg-white border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                         x-data="{
                             formatDate(dateString) {
                                 if (!dateString) return '';
                                 const date = new Date(dateString);
                                 return date.toLocaleDateString('ar-SA', {
                                     year: 'numeric',
                                     month: 'long',
                                     day: 'numeric'
                                 });
                             },
                             formatFileSize(bytes) {
                                 if (!bytes) return '';
                                 if (bytes < 1024) return bytes + ' B';
                                 if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(2) + ' KB';
                                 return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
                             },
                             getDocumentTypeColor(type) {
                                 const colors = {
                                     'طي_القيد': 'bg-blue-100 text-blue-700',
                                     'تعيين_السكن': 'bg-green-100 text-green-700',
                                     'عقد_السكن': 'bg-purple-100 text-purple-700',
                                     'الغياب': 'bg-orange-100 text-orange-700',
                                     'أخرى': 'bg-slate-100 text-slate-700'
                                 };
                                 return colors[type] || colors['أخرى'];
                             },
                             getDocumentTypeLabel(type) {
                                 const labels = {
                                     'طي_القيد': 'وثيقة طي القيد',
                                     'تعيين_السكن': 'وثيقة تعيين السكن',
                                     'عقد_السكن': 'عقد السكن',
                                     'الغياب': 'وثيقة الغياب',
                                     'أخرى': 'وثيقة أخرى'
                                 };
                                 return labels[type] || 'وثيقة';
                             }
                         }">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-start gap-3 flex-1">
                                <div class="p-2 bg-slate-100 rounded-lg">
                                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <a
                                        :href="'{{ route('documents.show', ':id') }}'.replace(':id', document.id)"
                                        target="_blank"
                                        class="font-semibold text-slate-800 mb-1 hover:text-blue-600 transition-colors cursor-pointer block"
                                        x-text="document.document_name"
                                        title="عرض الوثيقة"
                                    ></a>
                                    <span class="inline-block px-2 py-1 rounded-full text-xs font-medium"
                                          :class="getDocumentTypeColor(document.document_type)"
                                          x-text="getDocumentTypeLabel(document.document_type)">
                                    </span>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <a
                                    :href="'{{ route('documents.show', ':id') }}'.replace(':id', document.id)"
                                    target="_blank"
                                    class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors"
                                    title="عرض"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                <a
                                    :href="'{{ route('documents.download', ':id') }}'.replace(':id', document.id)"
                                    class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors"
                                    title="تحميل"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                                <button
                                    @click="handleDocumentDelete(document.id)"
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                    title="حذف"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2 text-sm text-slate-600">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>تاريخ الرفع: <span x-text="formatDate(document.upload_date)"></span></span>
                            </div>
                            
                            <template x-if="document.file_name">
                                <div>
                                    <span class="font-medium">الملف: </span>
                                    <span x-text="document.file_name"></span>
                                    <template x-if="document.file_size">
                                        <span class="text-slate-500"> (<span x-text="formatFileSize(document.file_size)"></span>)</span>
                                    </template>
                                </div>
                            </template>
                            
                            <template x-if="document.notes">
                                <div class="mt-2 p-2 bg-slate-50 rounded text-slate-700">
                                    <span class="font-medium">ملاحظات: </span>
                                    <span x-text="document.notes"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        <template x-if="filteredDocuments.length === 0">
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-8 text-center">
                <p class="text-slate-500">لا توجد وثائق مرفوعة لهذا المنسوب</p>
            </div>
        </template>
    </div>
</div>

