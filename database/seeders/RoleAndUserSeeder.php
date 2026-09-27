<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Major;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        $studentRole = Role::firstOrCreate(['name' => 'Student']);
        $officerRole = Role::firstOrCreate(['name' => 'Admission_Officer']);

        // حساب موظف القبول
        $officer = User::updateOrCreate(
            ['email' => 'officer@college.edu'],
            [
                'name' => 'موظف القبول والتسجيل',
                'password' => Hash::make('password123'),
            ]
        );
        $officer->syncRoles([$officerRole]);

        // حساب طالب للتجربة
        $student = User::updateOrCreate(
            ['email' => 'student@college.edu'],
            [
                'name' => 'محمد أحمد',
                'password' => Hash::make('password123'),
            ]
        );
        $student->syncRoles([$studentRole]);

        // إضافة تخصصات الكلية
        Major::updateOrCreate(
            ['name' => 'علوم الحاسوب'],
            [
                'faculty' => 'كلية الحاسبات وتكنولوجيا المعلومات',
                'min_gpa' => 75.0,
                'capacity' => 100,
            ]
        );

        Major::updateOrCreate(
            ['name' => 'تقنية المعلومات'],
            [
                'faculty' => 'كلية الحاسبات وتكنولوجيا المعلومات',
                'min_gpa' => 70.0,
                'capacity' => 120,
            ]
        );
    }
}