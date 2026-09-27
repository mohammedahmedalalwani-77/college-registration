<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Major;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function exportCsv(Request $request): StreamedResponse
    {
        $majorId = $request->query('major_id');
        $status = $request->query('status', 'approved');

        $query = Application::with(['user', 'major'])->where('status', $status);

        if ($majorId) {
            $query->where('major_id', $majorId);
        }

        $applications = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="accepted_students_' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($applications) {
            $handle = fopen('php://output', 'w');
            // BOM for UTF-8 Excel support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($handle, ['رقم الطلب', 'اسم الطالب', 'البريد الإلكتروني', 'التخصص', 'الكلية', 'معدل الثانوية', 'حالة الطلب', 'تاريخ القبول']);

            foreach ($applications as $app) {
                fputcsv($handle, [
                    $app->id,
                    $app->user->name,
                    $app->user->email,
                    $app->major->name,
                    $app->major->faculty,
                    $app->high_school_gpa . '%',
                    $app->status,
                    $app->updated_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    public function printReport(Request $request)
    {
        $majorId = $request->query('major_id');
        $status = $request->query('status', 'approved');

        $query = Application::with(['user', 'major'])->where('status', $status);
        $selectedMajor = null;

        if ($majorId) {
            $query->where('major_id', $majorId);
            $selectedMajor = Major::find($majorId);
        }

        $applications = $query->latest()->get();

        return view('officer.report_print', compact('applications', 'selectedMajor', 'status'));
    }
}
