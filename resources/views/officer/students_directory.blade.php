<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
                    <span>👨‍🎓</span> {{ __('السجل الشامل لبيانات الطلاب') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">عرض كامل ومفصل لكافة الملفات والبيانات الخاصة بالطلاب المتقدمين.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('officer.dashboard') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 font-extrabold text-xs rounded-xl transition flex items-center gap-1.5">
                    <span>←</span> العودة للوحة التحكم
                </a>
                <a href="{{ route('officer.export.print', ['status' => $statusFilter]) }}" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <span>🖨️</span> طباعة السجل (PDF)
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border-r-4 border-emerald-500 text-emerald-800 dark:text-emerald-300 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            {{-- 1. القائمة المنسدلة البارزة للتنقل المباشر بين الحالات والبيانات --}}
            <div class="bg-gradient-to-r from-indigo-900 via-slate-900 to-blue-900 p-6 rounded-3xl shadow-xl text-white space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-indigo-800/80 pb-4">
                    <div>
                        <span class="px-3 py-1 bg-indigo-500/30 text-indigo-300 text-xs font-bold rounded-full border border-indigo-400/40">
                            🎯 اختيار فئة التصفية والبيانات
                        </span>
                        <h3 class="text-xl font-black mt-2 text-white">اختر فئة الطلبات من القائمة المنسدلة لفتح البيانات الخاصة بها:</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center pt-2">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-indigo-200 mb-2">📋 القائمة المنسدلة الرئيسية (الانتقال المباشر للفئة):</label>
                        <select onchange="if(this.value) window.location.href = this.value;" class="w-full text-sm font-black rounded-2xl border-2 border-indigo-400/50 bg-slate-800 text-white py-3 px-4 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition cursor-pointer shadow-lg">
                            <option value="{{ route('officer.students.directory') }}" {{ !$statusFilter ? 'selected' : '' }}>
                                📊 إجمالي الطلبات ({{ $stats['total'] }} طالب) — عرض البيانات الكلية
                            </option>
                            <option value="{{ route('officer.students.directory', ['status' => 'pending']) }}" {{ $statusFilter == 'pending' ? 'selected' : '' }}>
                                ⏳ قيد المراجعة ({{ $stats['pending'] }} طالب) — فتح صفحة طلبات الانتظار
                            </option>
                            <option value="{{ route('officer.students.directory', ['status' => 'approved']) }}" {{ $statusFilter == 'approved' ? 'selected' : '' }}>
                                🎉 المقبولين نهائياً ({{ $stats['approved'] }} طالب) — فتح صفحة المقبولين
                            </option>
                            <option value="{{ route('officer.students.directory', ['status' => 'action_required']) }}" {{ $statusFilter == 'action_required' ? 'selected' : '' }}>
                                ⚠️ تعديل مطلوب ({{ $stats['action_required'] }} طالب) — فتح صفحة الإجراء المطلوب
                            </option>
                            <option value="{{ route('officer.students.directory', ['status' => 'rejected']) }}" {{ $statusFilter == 'rejected' ? 'selected' : '' }}>
                                ❌ المرفوضين ({{ $stats['rejected'] }} طالب) — فتح صفحة المرفوضين
                            </option>
                        </select>
                    </div>

                    <div class="bg-indigo-950/60 p-4 rounded-2xl border border-indigo-800/60 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-indigo-300 font-bold block">الفئة المعروضة حالياً:</span>
                            <span class="text-sm font-black text-white">
                                @if($statusFilter == 'approved')
                                    🟢 المقبولين نهائياً
                                @elseif($statusFilter == 'pending')
                                    ⏳ قيد المراجعة
                                @elseif($statusFilter == 'action_required')
                                    🟠 تعديل مطلوب
                                @elseif($statusFilter == 'rejected')
                                    🔴 المرفوضين
                                @else
                                    📊 جميع الطلبات
                                @endif
                            </span>
                        </div>
                        <span class="text-2xl font-black bg-indigo-600 px-3 py-1 rounded-xl text-white">
                            {{ $applications->total() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. شريط البحث الفوري باسم الطالب أو البريد --}}
            <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700">
                <form action="{{ route('officer.students.directory') }}" method="GET" class="flex flex-col md:flex-row items-center gap-3">
                    @if($statusFilter)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif
                    <div class="flex-grow w-full">
                        <input type="text" name="search" value="{{ $searchQuery }}" placeholder="ابحث باسم الطالب أو البريد الإلكتروني..." class="w-full text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                    </div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-indigo-600 text-white font-extrabold text-xs rounded-xl hover:bg-indigo-700 transition cursor-pointer shadow-xs">
                        بحث في السجل
                    </button>
                    @if($searchQuery || $statusFilter)
                        <a href="{{ route('officer.students.directory') }}" class="w-full md:w-auto px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl hover:bg-slate-200 transition text-center">
                            إعادة تعيين
                        </a>
                    @endif
                </form>
            </div>

            {{-- 3. قائمة ملفات الطلاب الكاملة مع كامل البيانات والشريط التفاعلي --}}
            <div class="space-y-6">
                @forelse($applications as $app)
                    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-6 space-y-6">
                        
                        <!-- Top Banner Profile -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-700 pb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white font-black text-2xl flex items-center justify-center shadow-md">
                                    {{ mb_substr($app->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                        {{ $app->user->name }}
                                        <span class="px-2.5 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs rounded-lg font-bold">طالب مُمكّن</span>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $app->user->email }} • رقم الملف التراكمي: #{{ $app->id }}</p>
                                </div>
                            </div>

                            <div>
                                @if($app->status == 'approved')
                                    <span class="px-4 py-2 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-extrabold text-xs rounded-full border border-emerald-300 dark:border-emerald-800 inline-flex items-center gap-2 shadow-xs">
                                        <span>🟢</span> مقبول نهائياً في الجامعة
                                    </span>
                                @elseif($app->status == 'pending')
                                    <span class="px-4 py-2 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-extrabold text-xs rounded-full border border-amber-300 dark:border-amber-800 inline-flex items-center gap-2 shadow-xs">
                                        <span>⏳</span> قيد الانتظار والمراجعة
                                    </span>
                                @elseif($app->status == 'action_required')
                                    <span class="px-4 py-2 bg-orange-100 dark:bg-orange-950 text-orange-800 dark:text-orange-300 font-extrabold text-xs rounded-full border border-orange-300 dark:border-orange-800 inline-flex items-center gap-2 shadow-xs">
                                        <span>⚠️</span> مطلوب تعديل إجراء
                                    </span>
                                @else
                                    <span class="px-4 py-2 bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 font-extrabold text-xs rounded-full border border-rose-300 dark:border-rose-800 inline-flex items-center gap-2 shadow-xs">
                                        <span>🔴</span> طلب مرفوض
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Data Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Academic Major -->
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-200/60 dark:border-slate-700 space-y-1.5">
                                <span class="text-[11px] font-bold text-slate-400 block uppercase">التخصص الأكاديمي المطلوب</span>
                                <h4 class="text-sm font-black text-slate-900 dark:text-slate-100">{{ $app->major->name }}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 font-semibold">{{ $app->major->faculty }}</p>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block pt-1 border-t dark:border-slate-600">
                                    الحد الأدنى: <strong>{{ $app->major->min_gpa }}%</strong> | المقاعد: <strong>{{ $app->major->approvedApplicationsCount() }}/{{ $app->major->capacity }}</strong>
                                </span>
                            </div>

                            <!-- High School Result -->
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-200/60 dark:border-slate-700 space-y-1.5">
                                <span class="text-[11px] font-bold text-slate-400 block uppercase">معدل الثانوية العامة</span>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-slate-900 dark:text-slate-100">{{ $app->high_school_gpa }}%</span>
                                    @if($app->high_school_gpa >= $app->major->min_gpa)
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-extrabold rounded-md">مؤهل 100%</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-800 text-[10px] font-extrabold rounded-md">أقل من المطلوب</span>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block pt-1 border-t dark:border-slate-600">
                                    تاريخ تقديم الطلب: {{ $app->created_at->format('Y-m-d H:i') }}
                                </span>
                            </div>

                            <!-- Decision Notes -->
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/40 rounded-2xl border border-slate-200/60 dark:border-slate-700 space-y-1.5">
                                <span class="text-[11px] font-bold text-slate-400 block uppercase">ملاحظات وسبب القرار</span>
                                @if($app->rejection_reason)
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 bg-white dark:bg-slate-800 p-2 rounded-lg border border-slate-200 dark:border-slate-600">
                                        "{{ $app->rejection_reason }}"
                                    </p>
                                @else
                                    <p class="text-xs text-slate-400 font-medium italic py-1">لا توجد ملاحظات مسجلة.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Full Audit Trail Log Accordion -->
                        @if($app->histories->count() > 0)
                            <div class="border-t border-slate-100 dark:border-slate-700 pt-4" x-data="{ open: true }">
                                <button @click="open = !open" class="text-xs font-extrabold text-indigo-600 dark:text-indigo-400 flex items-center gap-1.5 hover:underline cursor-pointer">
                                    <span>📜</span> سجل حركة التغييرات والقرارات التاريخية للطلب ({{ $app->histories->count() }})
                                </button>

                                <div x-show="open" class="mt-3 space-y-2">
                                    @foreach($app->histories as $hist)
                                        <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200/60 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-1">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                                <span class="font-extrabold text-slate-900 dark:text-slate-100">الحالة الجديدة: {{ $hist->new_status }}</span>
                                                @if($hist->notes)
                                                    <span class="text-slate-500 dark:text-slate-400">("{{ $hist->notes }}")</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-400 font-semibold">
                                                بواسطة: {{ $hist->changedBy->name ?? 'النظام' }} • {{ $hist->created_at->format('Y-m-d H:i:s') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="p-12 text-center bg-white dark:bg-slate-800 rounded-3xl border border-slate-200/80 dark:border-slate-700 space-y-3">
                        <span class="text-4xl block">🔍</span>
                        <h4 class="text-base font-bold text-slate-800 dark:text-slate-200">لا توجد سجلات طلاب حالية في هذه الفئة المحددة</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">اختر فئة أخرى من القائمة المنسدلة بالأعلى لعرض بياناتها.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700">
                {{ $applications->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
