<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Major;
use App\Models\Application;
use App\Models\Document;

$student = User::where('email', 'student@college.edu')->first();
$major = Major::first();

if ($student && $major) {
    $application = Application::create([
        'user_id' => $student->id,
        'major_id' => $major->id,
        'high_school_gpa' => 88.5,
        'status' => 'pending',
    ]);

    Document::create([
        'application_id' => $application->id,
        'document_type' => 'شهادة_الثانوية_العامة.pdf',
        'file_path' => 'documents/sample.pdf',
    ]);

    echo "Successfully created application #{$application->id} for {$student->name} in Major: {$major->name}\n";
} else {
    echo "Student or Major not found!\n";
}
