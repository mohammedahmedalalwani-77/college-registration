<nav x-data="{ open: false }" class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 shadow-xs sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6">
                <!-- Logo & Title -->
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-indigo-700 dark:text-indigo-400 font-extrabold text-lg hover:text-indigo-800 transition">
                        <span class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white flex items-center justify-center shadow-md text-xl">🎓</span>
                        <span>بوابة القبول الجامعي</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 space-x-reverse sm:-my-px sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="font-bold text-sm">
                        {{ __('لوحة التحكم الرئيسية') }}
                    </x-nav-link>

                    @if(Auth::user()->hasRole('Admission_Officer'))
                        <x-nav-link :href="route('officer.students.directory')" :active="request()->routeIs('officer.students.directory')" class="font-bold text-sm text-indigo-700 dark:text-indigo-400">
                            👨‍🎓 {{ __('دليل بيانات الطلاب') }}
                        </x-nav-link>
                        <x-nav-link :href="route('officer.tickets.index')" :active="request()->routeIs('officer.tickets.*')" class="font-bold text-sm">
                            🎫 {{ __('تذاكر استفسارات الطلاب') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="font-bold text-sm text-purple-700 dark:text-purple-400">
                            👥 {{ __('إدارة المستخدمين والأدوار') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="font-bold text-sm">
                            🎫 {{ __('تذاكر الدعم والاستفسارات') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Right Controls: Dark Mode Toggle, Notification Bell & Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                
                <!-- Dark Mode Toggle Button -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600 transition cursor-pointer" title="تغيير مظهر الصفحة (الوضع الداكن/الفاتح)">
                    <span x-show="!darkMode" class="text-lg">🌙</span>
                    <span x-show="darkMode" class="text-lg" style="display: none;">☀️</span>
                </button>

                <!-- Notification Bell Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = ! open" class="relative p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600 transition cursor-pointer">
                        <span class="text-lg">🔔</span>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white dark:border-slate-800 animate-pulse">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <div x-show="open" @click.outside="open = false" class="absolute left-0 mt-2 w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 py-2 z-50 text-right space-y-1" style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                            <span class="font-black text-xs text-slate-800 dark:text-slate-200">الإشعارات والتنبيهات</span>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.markRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:underline font-bold">تعيين الكل كمقروء</button>
                                </form>
                            @endif
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-slate-50 dark:divide-slate-700">
                            @forelse(Auth::user()->notifications->take(5) as $notification)
                                <div class="p-3 text-xs {{ $notification->unread() ? 'bg-indigo-50/50 dark:bg-indigo-950/40 font-semibold' : 'text-slate-600 dark:text-slate-400' }}">
                                    <p class="font-bold text-slate-900 dark:text-slate-100">{{ $notification->data['title'] ?? 'إشعار جديد' }}</p>
                                    <p class="text-slate-600 dark:text-slate-300 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <p class="p-4 text-center text-xs text-slate-400 font-medium">لا توجد إشعارات حالية.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="left" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-sm leading-4 font-semibold rounded-xl text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-700 hover:bg-slate-100 dark:hover:bg-slate-600 focus:outline-none transition shadow-2xs cursor-pointer">
                            <div class="flex items-center gap-2">
                                <span>{{ Auth::user()->name }}</span>
                                @if(Auth::user()->hasRole('Admission_Officer'))
                                    <span class="px-2 py-0.5 text-xs bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300 font-bold rounded-full">موظف قبول</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 font-bold rounded-full">طالب</span>
                                @endif
                            </div>

                            <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-right font-medium">
                            ⚙️ {{ __('تعديل الملف الشخصي') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-right text-red-600 hover:bg-red-50 font-medium">
                                🚪 {{ __('تسجيل الخروج') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-right font-bold">
                {{ __('لوحة التحكم') }}
            </x-responsive-nav-link>
            @if(Auth::user()->hasRole('Admission_Officer'))
                <x-responsive-nav-link :href="route('officer.students.directory')" :active="request()->routeIs('officer.students.directory')" class="text-right font-bold text-indigo-700">
                    👨‍🎓 {{ __('دليل بيانات الطلاب') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('officer.tickets.index')" :active="request()->routeIs('officer.tickets.*')" class="text-right font-bold">
                    🎫 {{ __('تذاكر الاستفسارات') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="text-right font-bold text-purple-700">
                    👥 {{ __('إدارة المستخدمين') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="text-right font-bold">
                    🎫 {{ __('تذاكر الدعم والاستفسارات') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-3 border-t border-slate-200 dark:border-slate-700">
            <div class="px-4">
                <div class="font-bold text-base text-slate-800 dark:text-slate-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-right">
                    ⚙️ {{ __('تعديل الملف الشخصي') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-right text-red-600">
                        🚪 {{ __('تسجيل الخروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
