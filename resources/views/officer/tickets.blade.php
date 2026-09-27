<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
                <span>🎫</span> {{ __('إدارة تذاكر دعم واستفسارات الطلاب') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1 bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 rounded-full border border-purple-200 dark:border-purple-800">
                لوحة الموظف
            </span>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/80 border-r-4 border-emerald-500 text-emerald-800 dark:text-emerald-200 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-slate-100">تذاكر الاستفسارات القادمة من الطلاب:</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">مراجعة والرد على أسئلة الطلاب وحل مشكلاتهم.</p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('officer.tickets.index') }}" class="px-3 py-1.5 text-xs font-bold rounded-lg border transition {{ !$status ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border-slate-200 dark:border-slate-600' }}">الكل</a>
                        <a href="{{ route('officer.tickets.index', ['status' => 'open']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg border transition {{ $status == 'open' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-amber-50 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800' }}">قيد الانتظار</a>
                        <a href="{{ route('officer.tickets.index', ['status' => 'answered']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg border transition {{ $status == 'answered' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800' }}">تم الرد</a>
                        <a href="{{ route('officer.tickets.index', ['status' => 'closed']) }}" class="px-3 py-1.5 text-xs font-bold rounded-lg border transition {{ $status == 'closed' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-600' }}">مغلقة</a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900/60">
                            <tr>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">عنوان التذكرة</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">اسم الطالب والبريد</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">الحالة</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 dark:text-slate-400 uppercase">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/60 transition">
                                    <td class="px-6 py-4">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="font-extrabold text-indigo-700 dark:text-indigo-400 hover:underline text-sm block">
                                            {{ $ticket->subject }}
                                        </a>
                                        @if($ticket->lastMessage)
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 truncate max-w-md">{{ $ticket->lastMessage->message }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <p class="font-bold text-slate-900 dark:text-slate-100 text-xs">{{ $ticket->user->name }}</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-400">{{ $ticket->user->email }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($ticket->status == 'open')
                                            <span class="px-3 py-1 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-xs font-bold rounded-full border border-amber-200 dark:border-amber-800">⏳ قيد الانتظار</span>
                                        @elseif($ticket->status == 'answered')
                                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-full border border-emerald-200 dark:border-emerald-800">💬 تم الرد</span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-full border border-slate-300 dark:border-slate-600">🔒 مغلقة</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold rounded-xl transition shadow-xs">
                                            فتح والرد ←
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 font-bold text-sm">لا توجد تذاكر دعم استفسارات حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-slate-700">
                    {{ $tickets->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
