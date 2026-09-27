<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-800 leading-tight flex items-center gap-2">
                <span>🎫</span> {{ __('تذاكر الدعم والاستفسارات') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-200/60">
                الدعم الفني والقبول
            </span>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-r-4 border-emerald-500 text-emerald-800 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Create New Ticket Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                    <span>✏️</span> فتح تذكرة استفسار جديدة
                </h3>

                <form action="{{ route('tickets.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">عنوان الاستفسار:</label>
                        <input type="text" name="subject" placeholder="مثال: استفسار حول حالة التعديل المطلوب" class="w-full text-sm rounded-xl border-slate-300 py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">تفاصيل ومحتوى الرسالة:</label>
                        <textarea name="message" rows="4" placeholder="اكتب تفاصيل سؤالك أو استفسارك هنا لموظف القبول..." class="w-full text-sm rounded-xl border-slate-300 py-2.5 px-3 focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
                    </div>

                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition cursor-pointer">
                        🚀 إرسال التذكرة
                    </button>
                </form>
            </div>

            <!-- Previous Tickets List -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4">
                <h3 class="text-lg font-black text-slate-900">سجل التذاكر السابقة:</h3>

                <div class="space-y-3">
                    @forelse($tickets as $ticket)
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="block p-4 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-slate-100/80 transition shadow-2xs">
                            <div class="flex items-center justify-between">
                                <h4 class="font-extrabold text-slate-900 text-base">{{ $ticket->subject }}</h4>
                                @if($ticket->status == 'open')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">⏳ قيد الانتظار</span>
                                @elseif($ticket->status == 'answered')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">💬 تم الرد من الموظف</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-200 text-slate-700 text-xs font-bold rounded-full">🔒 مغلقة</span>
                                @endif
                            </div>
                            @if($ticket->lastMessage)
                                <p class="text-xs text-slate-500 mt-2 truncate">آخر رسالة: {{ $ticket->lastMessage->message }}</p>
                            @endif
                            <span class="text-[10px] text-slate-400 mt-2 block">{{ $ticket->created_at->diffForHumans() }}</span>
                        </a>
                    @empty
                        <p class="text-xs text-slate-400 font-bold text-center py-6">لا توجد لديك تذاكر استفسارات حالياً.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
