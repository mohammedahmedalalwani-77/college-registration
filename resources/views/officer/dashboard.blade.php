<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
                <span>👨‍💼</span> {{ __('لوحة موظف القبول والتسجيل') }}
            </h2>
            
            <!-- Export Buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('officer.export.csv', ['status' => $statusFilter, 'major_id' => request('major_id')]) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <span>📊</span> تصدير ملف Excel (CSV)
                </a>
                <a href="{{ route('officer.export.print', ['status' => $statusFilter, 'major_id' => request('major_id')]) }}" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                    <span>🖨️</span> تقرير رسمي للطباعة (PDF)
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

            @if($errors->any())
                <div class="p-4 bg-rose-50 dark:bg-rose-950/60 border-r-4 border-rose-500 text-rose-800 dark:text-rose-300 rounded-xl shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-sm mb-2 text-rose-900 dark:text-rose-200">
                        <span>⚠️</span> تعذر تنفيذ الإجراء:
                    </div>
                    <ul class="list-disc pr-6 text-xs space-y-1 text-rose-700 dark:text-rose-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. كروت الإحصائيات الفورية --}}
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <a href="{{ route('officer.applications.detail') }}" class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-xs border border-slate-200/80 dark:border-slate-700 hover:border-indigo-400 hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">إجمالي الطلبات</p>
                        <span class="text-xs text-indigo-600 dark:text-indigo-400 font-bold group-hover:translate-x-1 transition duration-200">تفاصيل ←</span>
                    </div>
                    <p class="text-3xl font-black text-slate-900 dark:text-slate-100 mt-2">{{ $stats['total'] }}</p>
                </a>
                <a href="{{ route('officer.applications.detail', ['status' => 'pending']) }}" class="bg-amber-50/60 dark:bg-amber-950/40 p-5 rounded-2xl shadow-xs border border-amber-200/80 dark:border-amber-800 hover:border-amber-400 hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-amber-700 dark:text-amber-300">⏳ قيد المراجعة</p>
                        <span class="text-xs text-amber-700 dark:text-amber-300 font-bold group-hover:translate-x-1 transition duration-200">تفاصيل ←</span>
                    </div>
                    <p class="text-3xl font-black text-amber-900 dark:text-amber-100 mt-2">{{ $stats['pending'] }}</p>
                </a>
                <a href="{{ route('officer.applications.detail', ['status' => 'approved']) }}" class="bg-emerald-50/60 dark:bg-emerald-950/40 p-5 rounded-2xl shadow-xs border border-emerald-200/80 dark:border-emerald-800 hover:border-emerald-400 hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300">🎉 المقبولة</p>
                        <span class="text-xs text-emerald-700 dark:text-emerald-300 font-bold group-hover:translate-x-1 transition duration-200">تفاصيل ←</span>
                    </div>
                    <p class="text-3xl font-black text-emerald-900 dark:text-emerald-100 mt-2">{{ $stats['approved'] }}</p>
                </a>
                <a href="{{ route('officer.applications.detail', ['status' => 'action_required']) }}" class="bg-orange-50/60 dark:bg-orange-950/40 p-5 rounded-2xl shadow-xs border border-orange-200/80 dark:border-orange-800 hover:border-orange-400 hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-orange-700 dark:text-orange-300">⚠️ تعديل مطلوب</p>
                        <span class="text-xs text-orange-700 dark:text-orange-300 font-bold group-hover:translate-x-1 transition duration-200">تفاصيل ←</span>
                    </div>
                    <p class="text-3xl font-black text-orange-900 dark:text-orange-100 mt-2">{{ $stats['action_required'] }}</p>
                </a>
                <a href="{{ route('officer.applications.detail', ['status' => 'rejected']) }}" class="bg-rose-50/60 dark:bg-rose-950/40 p-5 rounded-2xl shadow-xs border border-rose-200/80 dark:border-rose-800 hover:border-rose-400 hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-rose-700 dark:text-rose-300">❌ المرفوضة</p>
                        <span class="text-xs text-rose-700 dark:text-rose-300 font-bold group-hover:translate-x-1 transition duration-200">تفاصيل ←</span>
                    </div>
                    <p class="text-3xl font-black text-rose-900 dark:text-rose-100 mt-2">{{ $stats['rejected'] }}</p>
                </a>
            </div>

            {{-- 2. كرت مواعيد ومواسم التقديم (Admission Season Settings) --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <span>⏰</span> إعدادات فترات ومواعيد التقديم بالجامعة
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">التحكم في فتح وإغلاق استقبال الطلبات وتحديد تاريخ البداية والنهاية.</p>
                    </div>
                    <div>
                        @if($admissionSetting->isCurrentlyOpen())
                            <span class="px-3.5 py-1.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 text-xs font-extrabold rounded-full inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> استقبال الطلبات مفتوح الان
                            </span>
                        @else
                            <span class="px-3.5 py-1.5 bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 text-xs font-extrabold rounded-full inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span> فترة التقديم مغلقة
                            </span>
                        @endif
                    </div>
                </div>

                <form action="{{ route('officer.admissionSettings.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">حالة استقبال الطلبات:</label>
                        <select name="is_open" class="w-full text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 py-2.5">
                            <option value="1" {{ $admissionSetting->is_open ? 'selected' : '' }}>🟢 مفتوح لاستقبال الطلبات</option>
                            <option value="0" {{ !$admissionSetting->is_open ? 'selected' : '' }}>🔴 مغلق (إيقاف المؤقت)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">تاريخ بداية استقبال الطلبات:</label>
                        <input type="date" name="start_date" value="{{ $admissionSetting->start_date ? $admissionSetting->start_date->format('Y-m-d') : '' }}" class="w-full text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 py-2.5">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">تاريخ نهاية استقبال الطلبات:</label>
                        <input type="date" name="end_date" value="{{ $admissionSetting->end_date ? $admissionSetting->end_date->format('Y-m-d') : '' }}" class="w-full text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 py-2.5">
                    </div>

                    <div>
                        <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold rounded-xl transition cursor-pointer shadow-xs">
                            حفظ إعدادات الفترة
                        </button>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">رسالة التنويه للطلاب في الواجهة:</label>
                        <input type="text" name="announcement_message" value="{{ $admissionSetting->announcement_message }}" placeholder="مثال: مرحباً بكم، التقديم مفتوح حتى تاريخ نهاية الشهر..." class="w-full text-xs font-medium rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 py-2.5 px-3">
                    </div>
                </form>
            </div>

            {{-- 3. جدول طلبات التسجيل المقدمة مع تصفية وبحث وتقسيم صفحات --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden">
                <div class="p-6 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                            <span>📋</span> طلبات التسجيل المقدمة
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">إدارة ومراجعة الطلبات المقدمة وتسجيل حركة التعديل.</p>
                    </div>
                    
                    {{-- شريط البحث والفلترة --}}
                    <form action="{{ route('officer.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2">
                        @if($statusFilter)
                            <input type="hidden" name="status" value="{{ $statusFilter }}">
                        @endif
                        <input type="text" name="search" placeholder="ابحث باسم الطالب أو البريد..." value="{{ $searchQuery }}" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-indigo-500 focus:border-indigo-500 w-64 py-2 px-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition shadow-xs cursor-pointer">بحث</button>
                        @if($searchQuery || $statusFilter)
                            <a href="{{ route('officer.dashboard') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-200 transition">إعادة تعيين</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50/80 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">طالب التسجيل</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">التخصص والسعة</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">معدل الطالب</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">الحالة والقرار والتسجيل</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($applications as $app)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/40 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="font-extrabold text-slate-900 dark:text-slate-100 text-sm">{{ $app->user->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $app->user->email }}</p>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $app->major->name }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            مقبول: <strong class="text-slate-800 dark:text-slate-100">{{ $app->major->approved_count ?? $app->major->approvedApplicationsCount() }}</strong> / {{ $app->major->capacity }}
                                            @if($app->major->isFull())
                                                <span class="text-rose-600 font-extrabold mr-1">[مكتمل المقاعد]</span>
                                            @endif
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-base font-black text-slate-900 dark:text-slate-100">{{ $app->high_school_gpa }}%</span>
                                        @if($app->high_school_gpa < $app->major->min_gpa)
                                            <span class="block text-xs font-bold text-rose-500 mt-0.5">⚠️ أقل من الأدنى ({{ $app->major->min_gpa }}%)</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <form action="{{ route('officer.applications.updateStatus', $app->id) }}" method="POST" class="space-y-2 max-w-sm">
                                            @csrf
                                            @method('PATCH')
                                            
                                            <div class="flex items-center gap-2">
                                                <select name="status" class="text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 py-1.5 px-3 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                                                    <option value="approved" {{ $app->status == 'approved' ? 'selected' : '' }}>🟢 قبول الطلب</option>
                                                    <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>🔴 رفض الطلب</option>
                                                    <option value="action_required" {{ $app->status == 'action_required' ? 'selected' : '' }}>🟠 تعديل/إجراء مطلوب</option>
                                                    <option value="pending" {{ $app->status == 'pending' ? 'selected' : '' }}>🟡 قيد الانتظار</option>
                                                </select>

                                                <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white text-xs font-extrabold rounded-xl hover:bg-indigo-700 transition cursor-pointer shadow-2xs">
                                                    حفظ القرار
                                                </button>
                                            </div>

                                            <input type="text" name="rejection_reason" placeholder="سبب الرفض أو التعديل المطلوب (إن وجد)..." value="{{ $app->rejection_reason }}" class="text-xs rounded-xl border-slate-300 dark:border-slate-600 py-1.5 px-3 w-full bg-slate-50 dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:bg-white dark:focus:bg-slate-800">
                                        </form>

                                        <!-- Audit Trail Log Toggle -->
                                        @if($app->histories->count() > 0)
                                            <div class="mt-2 text-[11px] text-slate-500 dark:text-slate-400" x-data="{ open: false }">
                                                <button @click="open = !open" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline cursor-pointer">
                                                    📜 عرض سجل حركة الحالات ({{ $app->histories->count() }})
                                                </button>
                                                <div x-show="open" class="mt-2 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl border dark:border-slate-600 text-xs space-y-1">
                                                    @foreach($app->histories as $hist)
                                                        <div class="border-b dark:border-slate-600 pb-1 last:border-0">
                                                            <span class="font-bold text-slate-800 dark:text-slate-200">{{ $hist->new_status }}</span> 
                                                            <span class="text-slate-400">بواسطة {{ $hist->changedBy->name ?? 'النظام' }} - {{ $hist->created_at->format('Y-m-d H:i') }}</span>
                                                            @if($hist->notes)
                                                                <p class="text-slate-600 dark:text-slate-300 italic font-normal">"{{ $hist->notes }}"</p>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-bold text-sm">لا توجد طلبات تسجيل مطابقة للفلتر أو البحث حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="p-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800">
                    {{ $applications->links() }}
                </div>
            </div>

            {{-- 4. قسم إدارة تخصصات الكلية (CRUD Majors) --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-slate-100 flex items-center gap-2">
                        <span>🏛️</span> إدارة التخصصات والسعات الاستيعابية
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">إضافة وتعديل التخصصات وتحديث شروط أدنى معدل قبول وحجم المقاعد.</p>
                </div>

                {{-- نموذج إضافة تخصص جديد --}}
                <form action="{{ route('officer.majors.store') }}" method="POST" class="p-5 bg-slate-50 dark:bg-slate-700/50 rounded-2xl border border-slate-200/80 dark:border-slate-700 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">اسم التخصص:</label>
                        <input type="text" name="name" placeholder="مثال: هندسة البرمجيات" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 py-2.5" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">الكلية:</label>
                        <input type="text" name="faculty" placeholder="مثال: كلية الحاسبات" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 py-2.5" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">أدنى معدل قبول (%):</label>
                        <input type="number" step="0.01" min="50" max="100" name="min_gpa" placeholder="75.0" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 py-2.5" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">السعة (عدد المقاعد):</label>
                        <input type="number" min="1" name="capacity" placeholder="50" class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 py-2.5" required>
                    </div>
                    <div>
                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold rounded-xl transition cursor-pointer shadow-xs">
                            + إضافة تخصص جديد
                        </button>
                    </div>
                </form>

                {{-- قائمة التخصصات الحالية --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($majors as $major)
                        <div class="p-5 border border-slate-200/80 dark:border-slate-700 rounded-2xl bg-white dark:bg-slate-800 shadow-2xs space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 pb-3">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 dark:text-slate-100 text-base">{{ $major->name }}</h4>
                                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $major->faculty }}</p>
                                </div>
                                <form action="{{ route('officer.majors.destroy', $major->id) }}" method="POST" onsubmit="return confirm('هل أنت تأكد من حذف التخصص؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-bold px-2.5 py-1 bg-rose-50 dark:bg-rose-950 rounded-lg transition">حذف</button>
                                </form>
                            </div>

                            <form action="{{ route('officer.majors.update', $major->id) }}" method="POST" class="space-y-3">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $major->name }}">
                                <input type="hidden" name="faculty" value="{{ $major->faculty }}">

                                <div class="flex items-center justify-between gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <span>الحد الأدنى للمعدل (%):</span>
                                    <input type="number" step="0.01" min="50" max="100" name="min_gpa" value="{{ $major->min_gpa }}" class="w-24 text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 py-1.5 px-2 text-center">
                                </div>

                                <div class="flex items-center justify-between gap-2 text-xs font-bold text-slate-700 dark:text-slate-300">
                                    <span>إجمالي السعة الاستيعابية:</span>
                                    <input type="number" min="1" name="capacity" value="{{ $major->capacity }}" class="w-24 text-xs font-bold rounded-xl border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 py-1.5 px-2 text-center">
                                </div>

                                <div class="pt-2 flex items-center justify-between text-xs text-slate-600 dark:text-slate-400 border-t border-slate-100 dark:border-slate-700">
                                    <span>المقبولين: <strong class="text-slate-900 dark:text-slate-100">{{ $major->approved_count }}</strong></span>
                                    <span>المتبقي: <strong class="{{ $major->availableCapacity() == 0 ? 'text-rose-600 font-extrabold' : 'text-emerald-600 font-extrabold' }}">{{ $major->availableCapacity() }} مقعد</strong></span>
                                </div>

                                <button type="submit" class="w-full py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition cursor-pointer">
                                    تحديث بيانات التخصص
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>
    </div>
</x-app-layout>