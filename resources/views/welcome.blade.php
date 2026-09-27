<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>بوابة القبول والتسجيل الجامعي الموحدة</title>

        <!-- Google Fonts: Cairo -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Cairo', system-ui, -apple-system, sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col selection:bg-indigo-500 selection:text-white">
        
        <!-- Header / Navigation -->
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-3">
                <span class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-blue-600 text-white flex items-center justify-center shadow-lg text-2xl">🎓</span>
                <div>
                    <h1 class="font-extrabold text-xl text-white tracking-wide">بوابة القبول الجامعي</h1>
                    <p class="text-xs text-indigo-400 font-semibold">نظام التقديم والتسجيل الموحد</p>
                </div>
            </div>

            <nav class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg transition duration-200">
                        الذهاب إلى لوحة التحكم ←
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-slate-300 hover:text-white text-sm font-bold transition">
                        تسجيل الدخول
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-sm font-bold rounded-xl shadow-lg transition duration-200">
                            إنشاء حساب طالب جديد
                        </a>
                    @endif
                @endauth
            </nav>
        </header>

        <!-- Main Hero Section -->
        <main class="flex-grow flex items-center justify-center py-16 px-6">
            <div class="max-w-4xl mx-auto text-center space-y-8">
                
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-indigo-950 border border-indigo-800 text-indigo-300 text-xs font-bold shadow-inner">
                    ✨ مرحباً بك في العام الأكاديمي الجديد
                </span>

                <h2 class="text-4xl md:text-6xl font-black text-white leading-tight tracking-tight">
                    قدم طلب التحاقك بالجامعة <br>
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-blue-400 to-emerald-400">بخطوات بسيطة وفورية</span>
                </h2>

                <p class="text-slate-400 text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                    منصة إلكترونية ذكية تتيح للطلاب التقديم على كافة التخصصات الكلية، متابعة حالة الطلب في الوقت الفعلي، ومراجعة قرار لجنة القبول فور صدوره.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white text-base font-extrabold rounded-2xl shadow-xl hover:shadow-indigo-500/20 transition">
                            دخول لوحة تقديم الطلبات
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-base font-extrabold rounded-2xl shadow-xl hover:shadow-indigo-500/20 transition">
                            ابدأ التقديم الآن (تسجيل جديد)
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-800 hover:bg-slate-700 text-slate-200 text-base font-bold rounded-2xl border border-slate-700 transition">
                            لديك حساب بالفعل؟ سجل دخولك
                        </a>
                    @endauth
                </div>

                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-16 text-right">
                    <div class="p-6 rounded-2xl bg-slate-800/60 border border-slate-700/80 space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-900/80 text-indigo-400 flex items-center justify-center text-xl font-bold">⚡</div>
                        <h3 class="text-lg font-bold text-white">تقديم فوري ومباشر</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">أدخل معدل الثانوية العامة واختر التخصص ليتم تسجيل طلبك فورا في قاعدة البيانات.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-800/60 border border-slate-700/80 space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-900/80 text-blue-400 flex items-center justify-center text-xl font-bold">📊</div>
                        <h3 class="text-lg font-bold text-white">شفافية السعة الاستيعابية</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">عرض شفاف للحد الأدنى للمعدلات والسعة الاستيعابية والمقاعد الشاغرة المتبقية في كل تخصص.</p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-800/60 border border-slate-700/80 space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-900/80 text-emerald-400 flex items-center justify-center text-xl font-bold">🎯</div>
                        <h3 class="text-lg font-bold text-white">متابعة لحظية للحالة</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">تابع حالة ملفك (قيد المراجعة، مقبول، مرفوض، أو يتطلب إجراء) أولاً بأول من لوحتك الخاصة.</p>
                    </div>
                </div>

            </div>
        </main>

        <footer class="py-6 border-t border-slate-800 text-center text-xs text-slate-500">
            جميع الحقوق محفوظة &copy; {{ date('Y') }} - بوابة القبول والتسجيل الجامعي
        </footer>

    </body>
</html>
