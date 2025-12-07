<div class="mb-6 flex items-center gap-4 bg-white p-4 rounded-xl shadow-sm">
    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span class="text-slate-700 font-medium">تصفية حسب:</span>
    <div class="flex gap-2">
        <button
            @click="timeFilter = 'شهري'"
            :class="timeFilter === 'شهري' ? 'bg-blue-500 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
            class="px-4 py-2 rounded-lg transition-all"
        >
            شهري
        </button>
        <button
            @click="timeFilter = 'سنوي'"
            :class="timeFilter === 'سنوي' ? 'bg-blue-500 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
            class="px-4 py-2 rounded-lg transition-all"
        >
            سنوي
        </button>
    </div>
</div>

