<?php

namespace App\Http\Controllers;

use App\Models\AdmissionSetting;
use App\Models\Application;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentApplicationController extends Controller
{
    public function index()
    {
        $majors = Major::all();
        $application = Application::with('major')
            ->where('user_id', Auth::id())
            ->first();
        $admissionSetting = AdmissionSetting::current();

        return view('student.dashboard', compact('majors', 'application', 'admissionSetting'));
    }

    public function store(Request $request)
    {
        $admissionSetting = AdmissionSetting::current();
        if (!$admissionSetting->isCurrentlyOpen()) {
            return back()->withErrors(['general' => 'عذراً، فترة التقديم مغلقة حالياً ولا يمكن استقبال طلبات جديدة.']);
        }

        $existingApp = Application::where('user_id', Auth::id())->first();
        if ($existingApp) {
            return back()->withErrors(['general' => 'لديك طلب تسجيل مقدم بالفعل.']);
        }

        $request->validate([
            'major_id' => 'required|exists:majors,id',
            'high_school_gpa' => 'required|numeric|min:50|max:100',
        ], [
            'major_id.required' => 'يرجى اختيار التخصص المرغوب.',
            'high_school_gpa.required' => 'يرجى إدخال معدل الثانوية العامة.',
            'high_school_gpa.numeric' => 'يرجى إدخال رقم صحيح للمعدل.',
            'high_school_gpa.min' => 'المعدل الأدنى للتقديم هو 50%.',
            'high_school_gpa.max' => 'المعدل الأقصى هو 100%.',
        ]);

        $major = Major::findOrFail($request->major_id);

        if ($request->high_school_gpa < $major->min_gpa) {
            return back()->withErrors(['high_school_gpa' => 'معدلك أقل من الحد الأدنى المطلوب لهذا التخصص (' . $major->min_gpa . '%).']);
        }

        if ($major->isFull()) {
            return back()->withErrors(['major_id' => 'عذراً، هذا التخصص ممتلئ حالياً ولا يستقبل طلبات إضافية.']);
        }

        Application::create([
            'user_id' => Auth::id(),
            'major_id' => $request->major_id,
            'high_school_gpa' => $request->high_school_gpa,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم تقديم طلبك بنجاح للكلية وهو قيد المراجعة الان!');
    }

    public function update(Request $request, Application $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        $admissionSetting = AdmissionSetting::current();
        if (!$admissionSetting->isCurrentlyOpen()) {
            return back()->withErrors(['general' => 'عذراً، فترة التقديم والتعديل مغلقة حالياً.']);
        }

        if (!in_array($application->status, ['action_required', 'pending', 'rejected'])) {
            return back()->withErrors(['general' => 'لا يمكنك تعديل الطلب في حالته الحالية.']);
        }

        $request->validate([
            'major_id' => 'required|exists:majors,id',
            'high_school_gpa' => 'required|numeric|min:50|max:100',
        ]);

        $major = Major::findOrFail($request->major_id);

        if ($request->high_school_gpa < $major->min_gpa) {
            return back()->withErrors(['high_school_gpa' => 'معدلك أقل من الحد الأدنى المطلوب لهذا التخصص (' . $major->min_gpa . '%).']);
        }

        $application->update([
            'major_id' => $request->major_id,
            'high_school_gpa' => $request->high_school_gpa,
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'تم تحديث البيانات وإعادة إرسال طلبك للمراجعة بنجاح!');
    }
}