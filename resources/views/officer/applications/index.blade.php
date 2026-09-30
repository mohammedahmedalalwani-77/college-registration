<x-app-layout>
    <div class="container mx-auto px-4 py-8 max-w-7xl" dir="rtl">
        <!-- الترويسة -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">إدارة ومعالجة طلبات الطلاب</h2>
            <p class="text-sm text-gray-500 mt-1">تصفح الطلبات، راجع الحالات، وتواصل مع الطلاب مباشرة.</p>
        </div>

        <!-- قسم الفلترة، البحث، والإحصائيات -->
        <div class="bg-white shadow-sm rounded-xl p-6 mb-8 border border-gray-200">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <!-- 1. القائمة المنسدلة للفلترة -->
                <div class="w-full md:w-1/3">
                    <label for="statusFilter" class="block mb-3 text-sm font-semibold text-gray-700">اختر حالة الطلبات لعرضها:</label>
                    <select id="statusFilter" 
                            class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-3" 
                            onchange="window.location.href=this.value;">
                        <option value="{{ route('officer.applications', 'all') }}" {{ $status == 'all' ? 'selected' : '' }}>إجمالي الطلبات</option>
                        <option value="{{ route('officer.applications', 'pending') }}" {{ $status == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="{{ route('officer.applications', 'approved') }}" {{ $status == 'approved' ? 'selected' : '' }}>المقبولين</option>
                        <option value="{{ route('officer.applications', 'action_required') }}" {{ $status == 'action_required' ? 'selected' : '' }}>تعديل مطلوب</option>
                        <option value="{{ route('officer.applications', 'rejected') }}" {{ $status == 'rejected' ? 'selected' : '' }}>المرفوضين</option>
                    </select>
                </div>

                <!-- 2. حقل البحث -->
                <div class="w-full md:w-1/2">
                    <label for="search" class="block mb-3 text-sm font-semibold text-gray-700">البحث عن طالب:</label>
                    <form action="{{ route('officer.applications', $status) }}" method="GET" class="flex relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               class="block w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-indigo-500 focus:border-indigo-500 p-3" 
                               placeholder="ابحث باسم الطالب أو رقم الطلب...">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-3 rounded-l-lg transition-colors border border-indigo-600 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                            بحث
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3. شريط عرض الإحصائيات (النتائج) -->
            <div class="mt-6 pt-6 border-t border-gray-100 flex flex-wrap gap-4">
                <div class="bg-green-50 text-green-700 px-5 py-3 rounded-lg flex items-center gap-3 border border-green-200">
                    <span class="font-semibold text-sm">عدد المقبولين:</span>
                    <span class="text-xl font-bold">{{ $approvedCount ?? 0 }}</span>
                </div>
                <div class="bg-red-50 text-red-700 px-5 py-3 rounded-lg flex items-center gap-3 border border-red-200">
                    <span class="font-semibold text-sm">عدد المرفوضين:</span>
                    <span class="text-xl font-bold">{{ $rejectedCount ?? 0 }}</span>
                </div>
                <div class="bg-gray-50 text-gray-700 px-5 py-3 rounded-lg flex items-center gap-3 border border-gray-200">
                    <span class="font-semibold text-sm">إجمالي الطلبات بالنظام:</span>
                    <span class="text-xl font-bold">{{ $totalCount ?? 0 }}</span>
                </div>
            </div>

        </div>

        <!-- جدول عرض البيانات -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-right text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">رقم الطلب</th>
                            <th scope="col" class="px-6 py-4 font-bold">اسم الطالب</th>
                            <th scope="col" class="px-6 py-4 font-bold">التخصص</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">الحالة</th>
                            <th scope="col" class="px-6 py-4 font-bold">تاريخ التقديم</th>
                            <th scope="col" class="px-6 py-4 font-bold text-center">إدارة (المراسلة والمعالجة)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($applications as $app)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#{{ $app->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $app->user->name ?? 'غير متوفر' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $app->major->name ?? 'غير متوفر' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($app->status == 'pending') 
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">قيد المراجعة</span>
                                    @elseif($app->status == 'approved') 
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">مقبول</span>
                                    @elseif($app->status == 'action_required') 
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">تعديل مطلوب</span>
                                    @elseif($app->status == 'rejected') 
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">مرفوض</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $app->created_at->format('Y-m-d') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center space-x-2 space-x-reverse">
                                    <!-- زر إرسال رسالة -->
                                    <button class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 transition-all">
                                        <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                        إرسال رسالة
                                    </button>
                                    
                                    <!-- زر التفاصيل -->
                                    <a href="{{ route('officer.applications.detail', ['search' => $app->user->email ?? '']) }}" class="inline-flex items-center px-3 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 transition-all">
                                        التفاصيل
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    لا توجد طلبات تطابق بحثك حالياً أو اسم الطالب غير موجود.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- روابط التقسيم (Pagination) -->
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    </div>
</x-app-layout>