<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
                    <span>📄</span> {{ __('التفاصيل الكاملة لطلبات التقديم والتسجيل') }}
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">عرض شامل ومستفيض لكافة بيانات الطلاب والتخصصات وسجل تاريخ القرارات.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('officer.dashboard') }}" class="px-4 py-2 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-100 font-extrabold text-xs rounded-xl transition flex items-center gap-1.5">
                    <span>←</span> العودة للوحة التحكم
                </a>
                <a href="{{ route('officer.export.print', ['status' => $statusFilter, 'major_id' => $majorId]) }}" target="_blank" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <span>🖨️</span> طباعة تقرير (PDF)
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

            {{-- 1. كروت التصفية الحية والتبديل بين الحالات --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="{{ route('officer.applications.detail') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-xs border transition {{ !$statusFilter ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-slate-200/80 dark:border-slate-700 hover:border-indigo-400' }}">
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400">إجمالي الطلبات</p>
                    <p class="text-3xl font-black text-slate-900 dark:text-slate-100 mt-2">{{ $stats['total'] }}</p>
                    <span class="text-[11px] text-indigo-600 dark:text-indigo-400 font-bold block mt-1">عرض جميع البيانات ←</span>
                </a>

                <a href="{{ route('officer.applications.detail', ['status' => 'pending']) }}" class="bg-amber-50/60 dark:bg-amber-950/40 p-5 rounded-2xl shadow-xs border transition {{ $statusFilter == 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-amber-200/80 dark:border-amber-800 hover:border-amber-400' }}">
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-300">⏳ قيد المراجعة</p>
                    <p class="text-3xl font-black text-amber-900 dark:text-amber-100 mt-2">{{ $stats['pending'] }}</p>
                    <span class="text-[11px] text-amber-700 dark:text-amber-300 font-bold block mt-1">عرض طلبات الانتظار ←</span>
                </a>

                <a href="{{ route('officer.applications.detail', ['status' => 'approved']) }}" class="bg-emerald-50/60 dark:bg-emerald-950/40 p-5 rounded-2xl shadow-xs border transition {{ $statusFilter == 'approved' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-emerald-200/80 dark:border-emerald-800 hover:border-emerald-400' }}">
                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300">🎉 المقبولة</p>
                    <p class="text-3xl font-black text-emerald-900 dark:text-emerald-100 mt-2">{{ $stats['approved'] }}</p>
                    <span class="text-[11px] text-emerald-700 dark:text-emerald-300 font-bold block mt-1">عرض المقبولين نهائياً ←</span>
                </a>

                <a href="{{ route('officer.applications.detail', ['status' => 'action_required']) }}" class="bg-orange-50/60 dark:bg-orange-950/40 p-5 rounded-2xl shadow-xs border transition {{ $statusFilter == 'action_required' ? 'border-orange-500 ring-2 ring-orange-500/20' : 'border-orange-200/80 dark:border-orange-800 hover:border-orange-400' }}">
                    <p class="text-xs font-bold text-orange-700 dark:text-orange-300">⚠️ تعديل مطلوب</p>
                    <p class="text-3xl font-black text-orange-900 dark:text-orange-100 mt-2">{{ $stats['action_required'] }}</p>
                    <span class="text-[11px] text-orange-700 dark:text-orange-300 font-bold block mt-1">عرض طلبات الإجراء ←</span>
                </a>

                <a href="{{ route('officer.applications.detail', ['status' => 'rejected']) }}" class="bg-rose-50/60 dark:bg-rose-950/40 p-5 rounded-2xl shadow-xs border transition {{ $statusFilter == 'rejected' ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-rose-200/80 dark:border-rose-800 hover:border-rose-400' }}">
                    <p class="text-xs font-bold text-rose-700 dark:text-rose-300">❌ المرفوضة</p>
                    <p class="text-3xl font-black text-rose-900 dark:text-rose-100 mt-2">{{ $stats['rejected'] }}</p>
                    <span class="text-[11px] text-rose-700 dark:text-rose-300 font-bold block mt-1">عرض الطلبات المرفوضة ←</span>
                </a>
            </div>

            {{-- 2. شريط البحث والفلترة حسب التخصص --}}
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700">
                <form action="{{ route('officer.applications.detail') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @if($statusFilter)
                        <input type="hidden" name="status" value="{{ $statusFilter }}">
                    @endif

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">بحث بالاسم أو البريد:</label>
                        <input type="text" name="search" placeholder="اكتب اسم الطالب أو إيميله..." value="{{ $searchQuery }}" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">فلترة بحسب التخصص:</label>
                        <select name="major_id" class="w-full text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 py-2.5">
                            <option value="">جميع التخصصات</option>
                            @foreach($majors as $m)
                                <option value="{{ $m->id }}" {{ $majorId == $m->id ? 'selected' : '' }}>{{ $m->name }} ({{ $m->faculty }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl transition shadow-xs cursor-pointer">
                            تطبيق الفلترة والبحث
                        </button>
                    </div>

                    <div>
                        <a href="{{ route('officer.applications.detail', ['status' => $statusFilter]) }}" class="w-full py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold text-xs rounded-xl transition flex items-center justify-center">
                            إعادة تعيين الفلاتر
                        </a>
                    </div>
                </form>
            </div>

            {{-- 3. البطاقات التفصيلية للطلبات المحددة (Detailed Cards List) --}}
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span>🔍</span> 
                        @if($statusFilter == 'approved')
                            قائمة الطلبات المقبولة نهائياً ({{ $applications->total() }})
                        @elseif($statusFilter == 'pending')
                            قائمة الطلبات قيد المراجعة والانتظار ({{ $applications->total() }})
                        @elseif($statusFilter == 'action_required')
                            قائمة الطلبات التي تتطلب تعديلاً من الطالب ({{ $applications->total() }})
                        @elseif($statusFilter == 'rejected')
                            قائمة الطلبات المرفوضة ({{ $applications->total() }})
                        @else
                            إجمالي جميع طلبات التقديم ({{ $applications->total() }})
                        @endif
                    </h3>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">عرض {{ $applications->firstItem() ?? 0 }} إلى {{ $applications->lastItem() ?? 0 }} من أصل {{ $applications->total() }}</span>
                </div>

                @forelse($applications as $app)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-6 space-y-6">
                        
                        <!-- Header & Badges -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 dark:border-slate-700 pb-4">
                            <div class="flex items-center gap-3">
                                <span class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-xl border border-indigo-200 dark:border-indigo-800">
                                    {{ mb_substr($app->user->name, 0, 1) }}
                                </span>
                                <div>
                                    <h4 class="text-base font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                                        {{ $app->user->name }}
                                        <span class="text-xs text-slate-400 font-normal">#طلب-{{ $app->id }}</span>
                                    </h4>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $app->user->email }} • قدم بتاريخ: {{ $app->created_at->format('Y-m-d H:i') }} ({{ $app->created_at->diffForHumans() }})</p>
                                </div>
                            </div>

                            <div>
                                @if($app->status == 'approved')
                                    <span class="px-4 py-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-extrabold text-xs rounded-full border border-emerald-200 dark:border-emerald-800 inline-flex items-center gap-1.5">
                                        <span>🎉</span> مقبول نهائياً
                                    </span>
                                @elseif($app->status == 'pending')
                                    <span class="px-4 py-1.5 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 font-extrabold text-xs rounded-full border border-amber-200 dark:border-amber-800 inline-flex items-center gap-1.5">
                                        <span>⏳</span> قيد المراجعة والانتظار
                                    </span>
                                @elseif($app->status == 'action_required')
                                    <span class="px-4 py-1.5 bg-orange-100 dark:bg-orange-950 text-orange-800 dark:text-orange-300 font-extrabold text-xs rounded-full border border-orange-200 dark:border-orange-800 inline-flex items-center gap-1.5">
                                        <span>⚠️</span> مطلوب تعديل من الطالب
                                    </span>
                                @else
                                    <span class="px-4 py-1.5 bg-rose-100 dark:bg-rose-950 text-rose-800 dark:text-rose-300 font-extrabold text-xs rounded-full border border-rose-200 dark:border-rose-800 inline-flex items-center gap-1.5">
                                        <span>❌</span> مرفوض
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Main Grid Data -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Box 1: Major & Faculty Details -->
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200/60 dark:border-slate-700 space-y-2">
                                <span class="text-[11px] font-bold text-slate-400 block uppercase">تفاصيل التخصص المطلوب</span>
                                <p class="text-sm font-black text-slate-900 dark:text-slate-100">{{ $app->major->name }}</p>
                                <p class="text-xs text-slate-600 dark:text-slate-300 font-semibold">{{ $app->major->faculty }}</p>
                                <div class="pt-2 border-t border-slate-200/60 dark:border-slate-600 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                    <span>الحد الأدنى: <strong>{{ $app->major->min_gpa }}%</strong></span>
                                    <span>المجموع المقبول: <strong>{{ $app->major->approvedApplicationsCount() }}/{{ $app->major->capacity }}</strong></span>
                                </div>
                            </div>

                            <!-- Box 2: GPA & Qualification -->
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200/60 dark:border-slate-700 space-y-2">
                                <span class="text-[11px] font-bold text-slate-400 block uppercase">معدل الطالب التأهيلي</span>
                                <p class="text-2xl font-black text-slate-900 dark:text-slate-100">{{ $app->high_school_gpa }}%</p>
                                @if($app->high_school_gpa >= $app->major->min_gpa)
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 text-[11px] font-extrabold rounded-md">
                                        ✓ يستوفي الحد الأدنى المطلوب (أعلى بـ {{ number_format($app->high_school_gpa - $app->major->min_gpa, 2) }}%)
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 bg-rose-100 text-rose-800 text-[11px] font-extrabold rounded-md">
                                        ⚠️ أقل من الحد الأدنى بـ {{ number_format($app->major->min_gpa - $app->high_school_gpa, 2) }}%
                                    </span>
                                @endif
                            </div>

                            <!-- Box 3: Notes & Action Reason -->
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200/60 dark:border-slate-700 space-y-2">
                                <span class="text-[11px] font-bold text-slate-400 block uppercase">ملاحظات وسبب القرار</span>
                                @if($app->rejection_reason)
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 leading-relaxed bg-white dark:bg-slate-800 p-2.5 rounded-lg border border-slate-200 dark:border-slate-600">
                                        "{{ $app->rejection_reason }}"
                                    </p>
                                @else
                                    <p class="text-xs text-slate-400 font-medium italic">لا توجد ملاحظات إضافية مسجلة.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Update Decision Form -->
                        <div class="p-4 bg-indigo-50/50 dark:bg-indigo-950/30 rounded-xl border border-indigo-100 dark:border-indigo-900/60 space-y-3">
                            <h5 class="text-xs font-extrabold text-indigo-950 dark:text-indigo-200 flex items-center gap-1.5">
                                <span>✏️</span> تعديل وتأكيد قرار القبول/الرفض:
                            </h5>

                            <form action="{{ route('officer.applications.updateStatus', $app->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center">
                                @csrf
                                @method('PATCH')

                                <div>
                                    <select name="status" class="w-full text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 py-2">
                                        <option value="approved" {{ $app->status == 'approved' ? 'selected' : '' }}>🟢 قبول الطلب</option>
                                        <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>🔴 رفض الطلب</option>
                                        <option value="action_required" {{ $app->status == 'action_required' ? 'selected' : '' }}>🟠 تعديل/إجراء مطلوب</option>
                                        <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>🟡 قيد الانتظار</option>
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <input type="text" name="rejection_reason" value="{{ $app->rejection_reason }}" placeholder="اكتب سبب القرار أو الملاحظة للطالب..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 py-2 px-3">
                                </div>

                                <div>
                                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl transition cursor-pointer shadow-xs">
                                        حفظ القرار وتحديث التاريخ
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Audit Trail History Timeline -->
                        @if($app->histories->count() > 0)
                            <div class="border-t border-slate-100 dark:border-slate-700 pt-4 space-y-2">
                                <h5 class="text-xs font-black text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                    <span>📜</span> سجل حركة القرارات والتغيرات الزمانية (Audit Trail):
                                </h5>

                                <div class="space-y-2">
                                    @foreach($app->histories as $hist)
                                        <div class="p-3 bg-slate-50 dark:bg-slate-700/40 rounded-xl border border-slate-200/60 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between text-xs gap-1">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                                <span class="font-bold text-slate-900 dark:text-slate-100">تغيرت الحالة إلى: {{ $hist->new_status }}</span>
                                                @if($hist->notes)
                                                    <span class="text-slate-500 dark:text-slate-400">("{{ $hist->notes }}")</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-400">
                                                بواسطة: {{ $hist->changedBy->name ?? 'النظام' }} • {{ $hist->created_at->format('Y-m-d H:i:s') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="p-12 text-center bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 space-y-3">
                        <span class="text-4xl block">🔍</span>
                        <h4 class="text-base font-bold text-slate-800 dark:text-slate-200">لا توجد طلبات متطابقة في هذا الفلتر</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">جرب تغيير حالة الفلتر من الكروت العلوية أو اختيار تخصص آخر.</p>
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
