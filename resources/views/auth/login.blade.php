<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - بوابة القبول الجامعي</title>
    
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
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-6 selection:bg-indigo-500 selection:text-white">

    <div class="w-full max-w-md bg-slate-800/90 border border-slate-700/80 p-8 rounded-3xl shadow-2xl space-y-6">
        
        <!-- Header & Logo -->
        <div class="text-center space-y-2">
            <a href="/" class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-blue-600 text-white text-3xl shadow-lg mb-2">
                🎓
            </a>
            <h1 class="text-2xl font-black text-white">تسجيل الدخول للنظام</h1>
            <p class="text-xs text-slate-400 font-semibold">أدخل بيانات حسابك للوصول إلى لوحة الخدمات</p>
        </div>

        @if (session('status'))
            <div class="p-3 bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-xs rounded-xl text-center font-bold">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-300 mb-1.5">البريد الإلكتروني:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       placeholder="student@college.edu" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-medium" />
                @error('email')
                    <span class="text-xs text-rose-400 block mt-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-300 mb-1.5">كلمة المرور:</label>
                <input id="password" type="password" name="password" required 
                       placeholder="••••••••" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-medium" />
                @error('password')
                    <span class="text-xs text-rose-400 block mt-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer text-slate-300">
                    <input id="remember_me" type="checkbox" name="remember" 
                           class="rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                    <span>تذكر بيانات دخولي</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-indigo-400 hover:text-indigo-300 font-bold transition">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-extrabold text-sm rounded-xl transition shadow-lg cursor-pointer">
                    دخول إلى الحساب
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-700/60 text-center text-xs text-slate-400">
            ليس لديك حساب طالب حتى الآن؟ 
            <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-extrabold mr-1 underline">
                إنشاء حساب جديد
            </a>
        </div>
    </div>

</body>
</html>