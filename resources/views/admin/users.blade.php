<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-800 leading-tight flex items-center gap-2">
                <span>👥</span> {{ __('إدارة المستخدمين والأدوار والترقيات') }}
            </h2>
            <span class="text-xs font-semibold px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full border border-indigo-200/60">
                لوحة التحكم الإدارية
            </span>
        </div>
    </x-slot>

    <div class="py-8" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border-r-4 border-emerald-500 text-emerald-800 rounded-xl shadow-xs flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">قائمة المستخدمين في النظام:</h3>
                        <p class="text-xs text-slate-500 mt-0.5">ترقية الطلاب إلى موظفي قبول أو تعديل الصلاحيات الفورية.</p>
                    </div>

                    <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="text" name="search" placeholder="ابحث باسم المستخدم أو البريد..." value="{{ $search }}" class="text-xs rounded-xl border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 w-64 py-2 px-3">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition cursor-pointer">بحث</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 uppercase">المستخدم</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 uppercase">البريد الإلكتروني</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 uppercase">الرتبة الحالية</th>
                                <th class="px-6 py-4 text-right text-xs font-extrabold text-slate-500 uppercase">تعديل الرتبة والترقية</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @foreach($users as $user)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-900 text-sm">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($user->hasRole('Admission_Officer'))
                                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-bold rounded-full">👨‍💼 موظف قبول</span>
                                        @elseif($user->hasRole('Student'))
                                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-bold rounded-full">🎓 طالب</span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-100 text-slate-800 text-xs font-bold rounded-full">مستخدم عادي</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST" class="flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="text-xs font-bold rounded-xl border-slate-300 py-1.5 px-3 bg-slate-50">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                        {{ $role->name == 'Admission_Officer' ? 'موظف قبول' : ($role->name == 'Student' ? 'طالب' : $role->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 text-white text-xs font-extrabold rounded-xl hover:bg-indigo-700 transition cursor-pointer">
                                                حفظ الترقيات
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
