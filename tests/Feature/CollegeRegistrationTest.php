<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Major;
use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CollegeRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Student']);
        Role::create(['name' => 'Admission_Officer']);
    }

    public function test_student_can_submit_application()
    {
        $student = User::factory()->create();
        $student->assignRole('Student');

        $major = Major::create([
            'name' => 'علوم الحاسوب',
            'faculty' => 'الحاسبات',
            'min_gpa' => 75.0,
            'capacity' => 2,
        ]);

        $response = $this->actingAs($student)->post(route('student.applications.store'), [
            'major_id' => $major->id,
            'high_school_gpa' => 85.0,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('applications', [
            'user_id' => $student->id,
            'major_id' => $major->id,
            'high_school_gpa' => 85.0,
            'status' => 'pending',
        ]);
    }

    public function test_student_cannot_apply_with_low_gpa()
    {
        $student = User::factory()->create();
        $student->assignRole('Student');

        $major = Major::create([
            'name' => 'طب الجراحة',
            'faculty' => 'الطب',
            'min_gpa' => 90.0,
            'capacity' => 10,
        ]);

        $response = $this->actingAs($student)->post(route('student.applications.store'), [
            'major_id' => $major->id,
            'high_school_gpa' => 70.0,
        ]);

        $response->assertSessionHasErrors(['high_school_gpa']);
    }

    public function test_officer_cannot_approve_when_capacity_is_full()
    {
        $officer = User::factory()->create();
        $officer->assignRole('Admission_Officer');

        $major = Major::create([
            'name' => 'الذكاء الاصطناعي',
            'faculty' => 'الحاسبات',
            'min_gpa' => 70.0,
            'capacity' => 1,
        ]);

        $student1 = User::factory()->create();
        $app1 = Application::create([
            'user_id' => $student1->id,
            'major_id' => $major->id,
            'high_school_gpa' => 95.0,
            'status' => 'approved',
        ]);

        $student2 = User::factory()->create();
        $app2 = Application::create([
            'user_id' => $student2->id,
            'major_id' => $major->id,
            'high_school_gpa' => 88.0,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($officer)->patch(route('officer.applications.updateStatus', $app2->id), [
            'status' => 'approved',
        ]);

        $response->assertSessionHasErrors(['general']);
        $this->assertEquals('pending', $app2->fresh()->status);
    }

    public function test_newly_registered_user_gets_student_role_automatically()
    {
        $response = $this->post('/register', [
            'name' => 'طالب جديد',
            'email' => 'newstudent@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'newstudent@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Student'));

        $dashboardResponse = $this->actingAs($user)->get('/dashboard');
        $dashboardResponse->assertRedirect(route('student.dashboard'));
    }
}
