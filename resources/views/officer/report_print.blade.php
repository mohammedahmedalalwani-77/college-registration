<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تقرير الطلاب المقبولين الرسمية - {{ date('Y-m-d') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Cairo', sans-serif; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white text-slate-900 p-8">

    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Print Header Bar -->
        <div class="no-print flex items-center justify-between p-4 bg-slate-100 rounded-xl mb-6 border">
            <span class="text-sm font-bold text-slate-700">📄 معاينة التقرير الرسمي للطباعة أو الحفظ كـ PDF</span>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-5 py-2 bg-indigo-600 text-white font-bold text-xs rounded-lg hover:bg-indigo-700 transition cursor-pointer">
                    🖨️ طباعة أو تصدير PDF
                </button>
                <button onclick="window.close()" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold text-xs rounded-lg hover:bg-slate-300">
                    إغلاق
                </button>
            </div>
        </div>

        <!-- Official Letterhead -->
        <div class="border-b-2 border-slate-900 pb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900">عمادة القبول والتسجيل الجامعي</h1>
                <p class="text-sm font-bold text-slate-600">تقرير الطلاب المقبولين رسمياً للعام الأكاديمي {{ date('Y') }}</p>
                @if($selectedMajor)
                    <p class="text-xs font-bold text-indigo-700 mt-1">التخصص: {{ $selectedMajor->name }} ({{ $selectedMajor->faculty }})</p>
                @endif
            </div>
            <div class="text-left text-xs font-bold text-slate-500">
                <p>تاريخ الاستخراج: {{ date('Y-m-d') }}</p>
                <p>إجمالي المقبولين: {{ $applications->count() }} طالب</p>
            </div>
        </div>

        <!-- Official Student Table -->
        <table class="w-full text-xs text-right border-collapse border border-slate-300">
            <thead>
                <tr class="bg-slate-100 border-b border-slate-300">
                    <th class="p-3 border border-slate-300 font-black">#</th>
                    <th class="p-3 border border-slate-300 font-black">اسم الطالب</th>
                    <th class="p-3 border border-slate-300 font-black">البريد الإلكتروني</th>
                    <th class="p-3 border border-slate-300 font-black">التخصص المقبول فيه</th>
                    <th class="p-3 border border-slate-300 font-black">معدل الثانوية</th>
                    <th class="p-3 border border-slate-300 font-black">تاريخ الاعتماد</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $index => $app)
                    <tr class="border-b border-slate-200">
                        <td class="p-3 border border-slate-300 font-bold">{{ $index + 1 }}</td>
                        <td class="p-3 border border-slate-300 font-bold text-sm">{{ $app->user->name }}</td>
                        <td class="p-3 border border-slate-300">{{ $app->user->email }}</td>
                        <td class="p-3 border border-slate-300 font-semibold">{{ $app->major->name }}</td>
                        <td class="p-3 border border-slate-300 font-extrabold text-sm">{{ $app->high_school_gpa }}%</td>
                        <td class="p-3 border border-slate-300 text-slate-600">{{ $app->updated_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-slate-500 font-bold">لا يوجد طلاب مقبولين في هذا التقرير.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Signatures Footer -->
        <div class="pt-12 flex items-center justify-between text-xs font-bold text-slate-700">
            <div>
                <p>توقيع موظف القبول والتسجيل:</p>
                <p class="mt-8 border-b border-slate-400 w-48"></p>
            </div>
            <div>
                <p>ختم واعتماد الكلية:</p>
                <p class="mt-8 border-b border-slate-400 w-48"></p>
            </div>
        </div>
    </div>

</body>
</html>
