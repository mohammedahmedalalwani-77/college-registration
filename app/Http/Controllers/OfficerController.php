<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;

class OfficerController extends Controller
{
    public function applications(Request $request, $status = 'all')
    {
        $query = Application::with(['user', 'major']);

        // 1. تطبيق فلتر الحالة إذا لم يكن 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // 2. تطبيق البحث الدقيق (بالاسم، البريد، أو رقم الطلب) واستثناء البقية
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // جلب النتائج مع التقسيم والاحتفاظ ببيانات البحث
        $applications = $query->paginate(10)->withQueryString();

        // 3. جلب الإحصائيات الحقيقية من قاعدة البيانات مباشرة
        $approvedCount = Application::where('status', 'approved')->count();
        $rejectedCount = Application::where('status', 'rejected')->count();
        $totalCount = Application::count();

        return view('officer.applications.index', compact(
            'applications', 
            'status', 
            'approvedCount', 
            'rejectedCount', 
            'totalCount'
        ));
    }
}