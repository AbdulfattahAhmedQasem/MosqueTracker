<div class="space-y-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-4xl font-bold text-slate-800 mb-2">لوحة الإحصائيات</h1>
                <p class="text-slate-600 text-lg">نظرة شاملة على بيانات المساجد والمنسوبين</p>
            </div>
            <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-lg shadow-sm border border-slate-200">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm text-slate-600" x-text="new Date().toLocaleDateString('ar-SA', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="(stat, index) in mainStats" :key="index">
            <div class="group relative bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 cursor-pointer overflow-hidden border border-slate-100"
                 @click="navigateToRoute(stat.entity)">
                <!-- Gradient Background Effect -->
                <div :class="[stat.color, 'absolute top-0 right-0 w-full h-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300']"></div>
                
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-slate-500 text-sm font-medium mb-2" x-text="stat.label"></p>
                            <p class="text-4xl font-bold text-slate-800 mb-1" x-text="new Intl.NumberFormat('ar-SA').format(stat.value)"></p>
                            <p class="text-xs text-slate-400">إجمالي</p>
                        </div>
                        <div :class="[stat.color, 'p-4 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300']">
                            <!-- Users Icon -->
                            <template x-if="stat.icon === 'Users'">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </template>
                            <!-- MapPin Icon -->
                            <template x-if="stat.icon === 'MapPin'">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </template>
                            <!-- Building Icon -->
                            <template x-if="stat.icon === 'Building2'">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </template>
                            <!-- Briefcase Icon -->
                            <template x-if="stat.icon === 'Briefcase'">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </template>
                            <!-- Tag Icon -->
                            <template x-if="stat.icon === 'Tag'">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Footer Link -->
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-500 group-hover:text-slate-700 transition-colors">عرض التفاصيل</span>
                            <svg class="w-4 h-4 text-blue-500 transform group-hover:translate-x-[-4px] transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

