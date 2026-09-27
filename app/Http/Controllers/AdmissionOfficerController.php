<?php

namespace App\Http\Controllers;

use App\Models\AdmissionSetting;
use App\Models\Application;
use App\Models\ApplicationHistory;
use App\Models\Major;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdmissionOfficerController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status');
        $searchQuery = $request->query('search');

        $query = Application::with(['user', 'major', 'histories.changedBy']);

        if ($statusFilter && in_array($statusFilter, ['pending', 'approved', 'rejected', 'action_required'])) {
            $query->where('status', $statusFilter);
        }

        if ($searchQuery) {
            $query->whereHas('user', function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('email', 'like', "%{$searchQuery}%");
            });
        }

        $applications = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
            'action_required' => Application::where('status', 'action_required')->count(),
        ];

        $majors = Major::withCount(['applications as approved_count' => function ($q) {
            $q->where('status', 'approved');
        }])->get();

        $admissionSetting = AdmissionSetting::current();

        return view('officer.dashboard', compact('applications', 'stats', 'majors', 'statusFilter', 'searchQuery', 'admissionSetting'));
    }

    public function updateAdmissionSettings(Request $request)
    {
        $request->validate([
            'is_open' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'announcement_message' => 'nullable|string|max:1000',
        ]);

        $setting = AdmissionSetting::current();
        $setting->update([
            'is_open' => $request->is_open,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'announcement_message' => $request->announcement_message,
        ]);

        return redirect()->back()->with('success', 'تم تحديث إعدادات ومواعيد فترة القبول والتسجيل بنجاح.');
    }

    public function updateStatus(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,action_required,pending',
            'rejection_reason' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $application) {
            $oldStatus = $application->status;

            if ($request->status === 'approved' && $application->status !== 'approved') {
                $major = Major::where('id', $application->major_id)->lockForUpdate()->first();
                if ($major->isFull()) {
                    return back()->withErrors(['general' => "تعذر قبول الطلب: التخصص ({$major->name}) مكتمل السعة الاستيعابية بالفعل ({$major->capacity} مقعد)."]);
                }
            }

            $application->update([
                'status' => $request->status,
                'rejection_reason' => $request->rejection_reason,
            ]);

            ApplicationHistory::create([
                'application_id' => $application->id,
                'changed_by_user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'notes' => $request->rejection_reason,
            ]);

            $application->user->notify(new ApplicationStatusNotification(
                $request->status,
                $application->major->name,
                $request->rejection_reason
            ));

            return redirect()->back()->with('success', 'تم تحديث حالة الطلب وإشعار الطالب وسجل الحركة بنجاح.');
        });
    }

    public function storeMajor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'min_gpa' => 'required|numeric|min:50|max:100',
            'capacity' => 'required|integer|min:1',
        ]);

        Major::create($request->only(['name', 'faculty', 'min_gpa', 'capacity']));

        return redirect()->back()->with('success', 'تم إضافة التخصص الجديد بنجاح.');
    }

    public function updateMajor(Request $request, Major $major)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'faculty' => 'required|string|max:255',
            'min_gpa' => 'required|numeric|min:50|max:100',
            'capacity' => 'required|integer|min:1',
        ]);

        $major->update($request->only(['name', 'faculty', 'min_gpa', 'capacity']));

        return redirect()->back()->with('success', 'تم تحديث التخصص بنجاح.');
    }

    public function destroyMajor(Major $major)
    {
        if ($major->applications()->count() > 0) {
            return back()->withErrors(['general' => 'لا يمكن حذف تخصص مرتبط بطلبات تسجيل قادمة أو سابقة.']);
        }

        $major->delete();

        return redirect()->back()->with('success', 'تم حذف التخصص بنجاح.');
    }
}