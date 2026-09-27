<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إنشاء حساب طالب جديد - بوابة القبول الجامعي</title>
    
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
            <h1 class="text-2xl font-black text-white">تسجيل حساب طالب جديد</h1>
            <p class="text-xs text-slate-400 font-semibold">أدخل بياناتك لإنشاء حسابك والانتقال للتقديم</p>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-300 mb-1.5">الاسم الثلاثي أو الكامل:</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       placeholder="محمد أحمد علي" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-medium" />
                @error('name')
                    <span class="text-xs text-rose-400 block mt-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-300 mb-1.5">البريد الإلكتروني:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       placeholder="student@example.com" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-medium" />
                @error('email')
                    <span class="text-xs text-rose-400 block mt-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-300 mb-1.5">كلمة المرور:</label>
                <input id="password" type="password" name="password" required
                       placeholder="••••••••" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-medium" />
                @error('password')
                    <span class="text-xs text-rose-400 block mt-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-300 mb-1.5">تأكيد كلمة المرور:</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       placeholder="••••••••" 
                       class="w-full px-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-slate-100 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-medium" />
                @error('password_confirmation')
                    <span class="text-xs text-rose-400 block mt-1 font-bold">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" 
                        class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-extrabold text-sm rounded-xl transition shadow-lg cursor-pointer">
                    تأكيد التسجيل وإنشاء الحساب
                </button>
            </div>
        </form>

        <div class="pt-4 border-t border-slate-700/60 text-center text-xs text-slate-400">
            مسجل بالفعل في النظام؟ 
            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-extrabold mr-1 underline">
                تسجيل الدخول
            </a>
        </div>
    </div>

</body>
</html>
