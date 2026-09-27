<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-800 dark:text-slate-100 leading-tight flex items-center gap-2">
                <span>💬</span> {{ $ticket->subject }}
            </h2>
            <a href="{{ Auth::user()->hasRole('Admission_Officer') ? route('officer.tickets.index') : route('tickets.index') }}" class="text-xs font-bold px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-lg transition">
                ← العودة للتذاكر
            </a>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/80 border-r-4 border-emerald-500 text-emerald-800 dark:text-emerald-200 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Ticket Header Info -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <span class="text-xs text-slate-400 dark:text-slate-400 font-bold block mb-1">صاحب التذكرة: {{ $ticket->user->name }} ({{ $ticket->user->email }})</span>
                    <h3 class="text-lg font-black text-slate-900 dark:text-slate-100">{{ $ticket->subject }}</h3>
                </div>

                <div class="flex items-center gap-2">
                    @if($ticket->status == 'open')
                        <span class="px-3 py-1 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-xs font-bold rounded-full border border-amber-200 dark:border-amber-800">⏳ قيد الانتظار</span>
                    @elseif($ticket->status == 'answered')
                        <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 text-xs font-bold rounded-full border border-emerald-200 dark:border-emerald-800">💬 تم الرد</span>
                    @else
                        <span class="px-3 py-1 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-xs font-bold rounded-full border border-slate-300 dark:border-slate-600">🔒 مغلقة</span>
                    @endif

                    @if(Auth::user()->hasRole('Admission_Officer'))
                        <form action="{{ route('officer.tickets.updateStatus', $ticket->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $ticket->status == 'closed' ? 'open' : 'closed' }}">
                            <button type="submit" class="px-3 py-1 bg-slate-800 dark:bg-slate-700 text-white text-xs font-bold rounded-lg hover:bg-slate-900 dark:hover:bg-slate-600 transition cursor-pointer">
                                {{ $ticket->status == 'closed' ? 'إعادة فتح التذكرة' : 'إغلاق التذكرة' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Thread Messages -->
            <div class="space-y-4">
                @foreach($ticket->messages as $msg)
                    <div class="p-5 rounded-2xl border shadow-2xs {{ $msg->user->hasRole('Admission_Officer') ? 'bg-indigo-50/60 dark:bg-indigo-950/40 border-indigo-200/80 dark:border-indigo-800 mr-8' : 'bg-white dark:bg-slate-800 border-slate-200/80 dark:border-slate-700 ml-8' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-extrabold text-xs text-slate-900 dark:text-slate-100 flex items-center gap-1.5">
                                {{ $msg->user->name }}
                                @if($msg->user->hasRole('Admission_Officer'))
                                    <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 text-[10px] font-bold rounded-full">موظف قبول</span>
                                @endif
                            </span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-400 font-medium">{{ $msg->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <p class="text-xs text-slate-800 dark:text-slate-200 leading-relaxed font-medium">{{ $msg->message }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            @if($ticket->status != 'closed')
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 p-6 space-y-4">
                    <h4 class="text-sm font-black text-slate-900 dark:text-slate-100">إضافة رد جديد:</h4>
                    <form action="{{ route('tickets.reply', $ticket->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <textarea name="message" rows="3" placeholder="اكتب ردك هنا..." class="w-full text-xs rounded-xl border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl transition shadow-xs cursor-pointer">
                            💬 إرسال الرد
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
