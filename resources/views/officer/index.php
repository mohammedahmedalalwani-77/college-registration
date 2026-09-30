@extends('layouts.app') <!-- افترض أن هذا هو القالب الرئيسي لديك -->

@section('content')
<div class="container">
    <h2 class="mb-4">إدارة طلبات الطلاب</h2>

    <!-- القائمة المنسدلة للفلترة -->
    <div class="row mb-4">
        <div class="col-md-4">
            <label for="statusFilter">اختر حالة الطلبات:</label>
            <select id="statusFilter" class="form-select" onchange="location = this.value;">
                <option value="{{ route('officer.applications', 'all') }}" {{ $status == 'all' ? 'selected' : '' }}>إجمالي الطلبات</option>
                <option value="{{ route('officer.applications', 'pending') }}" {{ $status == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="{{ route('officer.applications', 'approved') }}" {{ $status == 'approved' ? 'selected' : '' }}>المقبولة</option>
                <option value="{{ route('officer.applications', 'modification') }}" {{ $status == 'modification' ? 'selected' : '' }}>تعديل مطلوب</option>
                <option value="{{ route('officer.applications', 'rejected') }}" {{ $status == 'rejected' ? 'selected' : '' }}>المرفوضة</option>
            </select>
        </div>
    </div>

    <!-- جدول عرض البيانات -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>اسم الطالب</th>
                    <th>الحالة</th>
                    <th>تاريخ التقديم</th>
                    <th>الإجراءات (المعالجات)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr>
                        <td>{{ $app->id }}</td>
                        <td>{{ $app->student_name }}</td> <!-- استبدل بأسماء الأعمدة الصحيحة في قاعدتك -->
                        <td>
                            <!-- عرض الحالة بشكل جميل -->
                            @if($app->status == 'pending') <span class="badge bg-warning">قيد المراجعة</span>
                            @elseif($app->status == 'approved') <span class="badge bg-success">مقبول</span>
                            @elseif($app->status == 'modification') <span class="badge bg-info">تعديل مطلوب</span>
                            @elseif($app->status == 'rejected') <span class="badge bg-danger">مرفوض</span>
                            @endif
                        </td>
                        <td>{{ $app->created_at->format('Y-m-d') }}</td>
                        <td>
                            <!-- زر عرض التفاصيل -->
                            <a href="#" class="btn btn-sm btn-primary">عرض التفاصيل</a>
                            <!-- سيتم إضافة زر الإشعارات/الرسائل هنا لاحقاً -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">لا توجد طلبات بهذه الحالة حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- روابط التقسيم (Pagination) -->
    <div class="d-flex justify-content-center mt-3">
        {{ $applications->links() }}
    </div>
</div>
@endsection