<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
                <span>🎓</span> {{ __('لوحة تقديم الطلبات ومتابعة القبول') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1 bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 rounded-full border border-indigo-200/60 dark:border-indigo-800">
                حساب طالب
            </span>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Hero Title Card -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-8 shadow-xl border border-indigo-900/60 relative overflow-hidden">
                <div class="relative z-10 space-y-2">
                    <span class="inline-block px-3.5 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-xs font-bold border border-indigo-500/30">
                        ✨ بوابة القبول والتسجيل الموحدة
                    </span>
                    <h1 class="text-2xl md:text-4xl font-black text-white leading-tight tracking-tight">
                        قدم طلبك والتحق بالجامعة بخطوات بسيطة وفورية 🎓
                    </h1>
                    <p class="text-slate-300 text-xs md:text-sm font-medium pt-1">
                        اختر التخصص الجامعي المرغوب، أدخل معدل الثانوية العامة، واطلب الالتحاق فوراً لمراجعة ملفك من لجنة القبول.
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/60 border-r-4 border-emerald-500 text-emerald-800 dark:text-emerald-300 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 bg-rose-50 dark:bg-rose-950/60 border-r-4 border-rose-500 text-rose-800 dark:text-rose-300 rounded-xl shadow-xs">
                    <div class="flex items-center gap-2 font-bold text-sm mb-2 text-rose-900 dark:text-rose-200">
                        <span>⚠️</span> يرجى تصحيح الأخطاء التالية:
                    </div>
                    <ul class="list-disc pr-6 text-xs space-y-1 text-rose-700 dark:text-rose-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Admission Season Announcement Banner -->
            <div class="p-5 rounded-2xl border shadow-xs {{ $admissionSetting->isCurrentlyOpen() ? 'bg-indigo-900 text-white border-indigo-800' : 'bg-rose-900 text-white border-rose-800' }}">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">⏰</span>
                        <div>
                            <h3 class="font-black text-base">
                                {{ $admissionSetting->isCurrentlyOpen() ? 'موسم التقديم مفتوح حالياً' : 'فترة التقديم والتعديل مغلقة حالياً' }}
                            </h3>
                            <p class="text-xs text-indigo-200 dark:text-rose-200 mt-0.5">
                                {{ $admissionSetting->announcement_message }}
                            </p>
                        </div>
                    </div>
                    @if($admissionSetting->end_date)
                        <div class="text-left text-xs bg-black/20 px-3 py-1.5 rounded-xl border border-white/10">
                            <span>آخر موعد: {{ $admissionSetting->end_date->format('Y-m-d') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            @if($application)
                {{-- بطاقة عرض تفاصيل الطلب المقدم --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 bg-slate-900 text-white flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-indigo-300 font-bold uppercase tracking-wider block mb-1">طلب التسجيل الحالي #{{ $application->id }}</span>
                            <h3 class="text-2xl font-extrabold text-white flex items-center gap-2">
                                🏛️ {{ $application->major->name }}
                            </h3>
                            <p class="text-xs text-slate-300 mt-1">{{ $application->major->faculty }}</p>
                        </div>
                        
                        <div>
                            @if($application->status == 'pending')
                                <span class="px-4 py-2 bg-amber-500/20 text-amber-300 border border-amber-500/30 text-xs font-extrabold rounded-full inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> قيد المراجعة والتدقيق
                                </span>
                            @elseif($application->status == 'approved')
                                <span class="px-4 py-2 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-extrabold rounded-full inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> 🎉 تم قبول طلبك بنجاح!
                                </span>
                            @elseif($application->status == 'rejected')
                                <span class="px-4 py-2 bg-rose-500/20 text-rose-300 border border-rose-500/30 text-xs font-extrabold rounded-full inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rose-400"></span> ❌ تم رفض الطلب
                                </span>
                            @else
                                <span class="px-4 py-2 bg-orange-500/20 text-orange-300 border border-orange-500/30 text-xs font-extrabold rounded-full inline-flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-orange-400 animate-pulse"></span> ⚠️ يتطلب تعديل البيانات
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700 shadow-2xs">
                            <span class="text-xs font-bold text-slate-400 block mb-1">معدل الثانوية العامة المسجل:</span>
                            <span class="text-2xl font-black text-slate-800 dark:text-slate-100">{{ $application->high_school_gpa }}%</span>
                        </div>

                        <div class="p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700 shadow-2xs">
                            <span class="text-xs font-bold text-slate-400 block mb-1">الحد الأدنى المطلوب للتخصص:</span>
                            <span class="text-2xl font-black text-indigo-700 dark:text-indigo-400">{{ $application->major->min_gpa }}%</span>
                        </div>
                    </div>

                    @if($application->rejection_reason)
                        <div class="p-4 m-6 mb-0 bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-900 dark:text-rose-200 text-sm">
                            <strong class="font-extrabold block text-xs text-rose-700 dark:text-rose-300 mb-1">💬 ملاحظة لجنة القبول والتسجيل:</strong>
                            <p class="font-semibold">{{ $application->rejection_reason }}</p>
                        </div>
                    @endif

                    {{-- نموذج تحديث بيانات الطلب --}}
                    @if($admissionSetting->isCurrentlyOpen())
                        <div class="p-6 border-t border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800">
                            <h4 class="text-base font-bold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                                <span>✏️</span> تعديل بيانات الطلب والمعدل
                            </h4>
                            
                            <form action="{{ route('student.applications.update', $application->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">اختر التخصص المرغوب:</label>
                                        <select name="major_id" class="w-full text-sm rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 py-2.5" required>
                                            @foreach($majors as $major)
                                                <option value="{{ $major->id }}" {{ $application->major_id == $major->id ? 'selected' : '' }} {{ $major->isFull() && $application->major_id != $major->id ? 'disabled' : '' }}>
                                                    {{ $major->name }} - {{ $major->faculty }} (أدنى حد: {{ $major->min_gpa }}% | المقاعد المتاحة: {{ $major->availableCapacity() }})
                                                    {{ $major->isFull() && $application->major_id != $major->id ? ' - [مكتمل]' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">معدل الثانوية العامة (%):</label>
                                        <input type="number" step="0.01" min="50" max="100" name="high_school_gpa" value="{{ old('high_school_gpa', $application->high_school_gpa) }}" class="w-full text-sm rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 py-2.5 font-bold" required>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm rounded-xl shadow-md transition flex items-center justify-center gap-2 cursor-pointer">
                                        <span>🔄</span> تحديث وإعادة إرسال الطلب
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

            @else
                {{-- نموذج تقديم طلب جديد --}}
                @if($admissionSetting->isCurrentlyOpen())
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-8">
                        <div class="mb-6 pb-4 border-b border-slate-100 dark:border-slate-700">
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">خطوة التقديم الأولى</span>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-slate-100 mt-1">تقديم طلب تسجيل جديد بالجامعة</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">يرجى اختيار التخصص المناسب لمعدلك وإدخال معدل الثانوية العامة بدقة.</p>
                        </div>

                        <form action="{{ route('student.applications.store') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">التخصص الجامعي المرغوب:</label>
                                    <select name="major_id" class="w-full text-sm rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 py-3" required>
                                        <option value="">-- اضغط لاختيار التخصص من القائمة --</option>
                                        @foreach($majors as $major)
                                            <option value="{{ $major->id }}" {{ $major->isFull() ? 'disabled' : '' }}>
                                                {{ $major->name }} - {{ $major->faculty }} (الحد الأدنى: {{ $major->min_gpa }}% | المقاعد المتاحة: {{ $major->availableCapacity() }} مقعد)
                                                {{ $major->isFull() ? ' - [مكتمل السعة]' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">معدل الثانوية العامة النهائي (%):</label>
                                    <input type="number" step="0.01" min="50" max="100" name="high_school_gpa" placeholder="مثال: 88.5" class="w-full text-sm rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 py-3 font-bold" required>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-black text-base rounded-xl shadow-lg transition flex items-center justify-center gap-2 cursor-pointer">
                                    <span>🚀</span> حفظ وإرسال طلب التسجيل
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>