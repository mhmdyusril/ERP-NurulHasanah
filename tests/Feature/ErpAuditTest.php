<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\BillingCategory;
use App\Models\Payment;
use App\Models\Saving;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErpAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $guru;
    protected Classes $classA;
    protected Student $student1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@ranurulhasanah.sch.id'],
            [
                'name' => 'Admin Sekolah Test',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );
        $this->admin->role = 'admin';
        $this->admin->save();

        $this->guru = User::firstOrCreate(
            ['email' => 'guru_test@ranurulhasanah.sch.id'],
            [
                'name' => 'Ibu Guru Test',
                'role' => 'guru',
                'password' => bcrypt('password'),
            ]
        );
        $this->guru->role = 'guru';
        $this->guru->save();

        $this->classA = Classes::firstOrCreate(
            ['nama_kelas' => 'Kelas A - Mekah'],
            ['wali_kelas_id' => $this->guru->id]
        );

        $this->student1 = Student::firstOrCreate(
            ['nis' => '20269991'],
            [
                'nama' => 'Ahmad Fauzi',
                'jenis_kelamin' => 'L',
                'status' => 'Aktif',
                'class_id' => $this->classA->id,
            ]
        );
    }

    /**
     * TEST 1: RBAC Security - Guru cannot access Admin-only modules
     */
    public function test_guru_forbidden_from_admin_only_modules(): void
    {
        $this->actingAs($this->guru);

        // Teachers management is admin-only
        $response1 = $this->get('/teachers');
        $response1->assertStatus(403);

        // Reports finance is admin-only
        $response2 = $this->get('/reports');
        $response2->assertStatus(403);

        // Payments management is admin-only
        $response3 = $this->get('/payments');
        $response3->assertStatus(403);
    }

    /**
     * TEST 2: RBAC Security - Admin has full access to all modules
     */
    public function test_admin_has_access_to_all_modules(): void
    {
        $this->actingAs($this->admin);

        $this->get('/students')->assertStatus(200);
        $this->get('/attendances')->assertStatus(200);
        $this->get('/attendances/scan')->assertStatus(200);
        $this->get('/students/cards')->assertStatus(200);
        $this->get('/reports')->assertStatus(200);
        $this->get('/payments')->assertStatus(200);
        $this->get('/savings')->assertStatus(200);
    }

    /**
     * TEST 3: Ported Feature - Cetak Kartu Siswa with QR Code
     */
    public function test_students_cards_generator_renders_correctly(): void
    {
        $this->withoutExceptionHandling();
        $this->actingAs($this->guru);

        $response = $this->get(route('students.cards'));
        $response->assertStatus(200);
        $response->assertSee('Ahmad Fauzi');
        $response->assertSee('20269991');
        $response->assertSee('Kelas A - Mekah');
        $response->assertSee('data-code="20269991"', false);
    }

    /**
     * TEST 4: Ported Feature - Positive QR Code Attendance Scan
     */
    public function test_attendance_qr_scan_positive(): void
    {
        $this->actingAs($this->guru);

        $response = $this->postJson(route('attendances.scan.process'), [
            'code' => '20269991',
            'status' => 'Hadir'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                 ])
                 ->assertJsonPath('data.student.nama', 'Ahmad Fauzi')
                 ->assertJsonPath('data.attendance.status', 'Hadir');

        $this->assertDatabaseHas('attendances', [
            'student_id' => $this->student1->id,
            'status' => 'Hadir'
        ]);
    }

    /**
     * TEST 5: Ported Feature - Negative QR Code Attendance Scan (Invalid NIS)
     */
    public function test_attendance_qr_scan_negative_invalid_code(): void
    {
        $this->actingAs($this->guru);

        $response = $this->postJson(route('attendances.scan.process'), [
            'code' => '99999999',
            'status' => 'Hadir'
        ]);

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Kartu siswa tidak terdaftar (Kode: 99999999)'
                 ]);
    }

    /**
     * TEST 6: Student Management - Create and Update Status
     */
    public function test_student_lifecycle_management(): void
    {
        $this->actingAs($this->admin);

        // Create new student
        $nis = '2026' . rand(1000, 9999);
        $response = $this->post(route('students.store'), [
            'nama' => 'Fatimah Zahra',
            'nis' => $nis,
            'jenis_kelamin' => 'P',
            'class_id' => $this->classA->id,
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', [
            'nis' => $nis,
            'nama' => 'Fatimah Zahra'
        ]);

        // Change status to Lulus
        $student = Student::where('nis', $nis)->first();
        $this->patch(route('students.updateStatus', $student->id), [
            'status' => 'Lulus'
        ])->assertRedirect(route('students.index'));

        $this->assertEquals('Lulus', $student->fresh()->status);
    }

    /**
     * TEST 7: Finance & SPP Payments
     */
    public function test_payment_recording_and_reports(): void
    {
        $this->actingAs($this->admin);

        $category = BillingCategory::create([
            'name' => 'SPP Bulanan',
            'default_amount' => 150000
        ]);

        $response = $this->post(route('payments.store'), [
            'student_id' => $this->student1->id,
            'billing_category_id' => $category->id,
            'amount' => 150000,
            'payment_date' => now()->format('Y-m-d'),
            'status' => 'lunas',
            'notes' => 'Pembayaran SPP Oktober'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('payments', [
            'student_id' => $this->student1->id,
            'amount' => 150000,
            'status' => 'lunas'
        ]);
    }

    /**
     * TEST 8: Savings (Tabungan Siswa)
     */
    public function test_student_savings_flow(): void
    {
        $this->actingAs($this->guru);

        $response = $this->post(route('savings.store'), [
            'student_id' => $this->student1->id,
            'type' => 'Setor',
            'amount' => 50000,
            'date' => now()->format('Y-m-d'),
            'notes' => 'Tabungan awal'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('savings', [
            'student_id' => $this->student1->id,
            'type' => 'Setor',
            'amount' => 50000
        ]);
    }
}
