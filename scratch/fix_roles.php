<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

$studentRole = Role::firstOrCreate(['name' => 'Student']);
$officerRole = Role::firstOrCreate(['name' => 'Admission_Officer']);

$users = User::all();
foreach ($users as $user) {
    if ($user->roles->count() === 0) {
        if ($user->email === 'officer@college.edu') {
            $user->assignRole($officerRole);
            echo "Assigned Admission_Officer to {$user->email}\n";
        } else {
            $user->assignRole($studentRole);
            echo "Assigned Student to {$user->email}\n";
        }
    } else {
        echo "User {$user->email} already has role: " . $user->roles->pluck('name')->implode(', ') . "\n";
    }
}
