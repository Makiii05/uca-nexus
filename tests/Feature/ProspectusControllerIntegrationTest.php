<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Prospectus;
use App\Models\Curriculum;
use App\Models\AcademicTerm;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Department;
use App\Models\Program;

class ProspectusControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $registrarUser;
    protected Department $department;
    protected Program $program;
    protected Curriculum $curriculum;
    protected AcademicTerm $academicTerm;
    protected Subject $subject;
    protected Level $level;

    protected function setUp(): void
    {
        parent::setUp();

        // Create registrar user for authentication
        $this->registrarUser = User::create([
            'name' => 'Test Registrar',
            'email' => 'registrar@test.com',
            'type' => 'registrar',
            'password' => bcrypt('password123'),
        ]);

        // Create test data
        $this->department = Department::create([
            'code' => 'DEPT001',
            'description' => 'Test Department',
            'status' => 'active',
        ]);

        $this->program = Program::create([
            'code' => 'PROG001',
            'description' => 'Test Program',
            'department_id' => $this->department->id,
            'status' => 'active',
        ]);

        $this->curriculum = Curriculum::create([
            'curriculum' => 'Curriculum 2025',
            'department_id' => $this->department->id,
            'status' => 'active',
        ]);

        $this->academicTerm = AcademicTerm::create([
            'code' => 'AT001',
            'description' => 'First Semester',
            'type' => 'semester',
            'department_id' => $this->department->id,
            'academic_year' => '2025-2026',
            'start_date' => '2025-08-01',
            'end_date' => '2025-12-31',
            'status' => 'active',
        ]);

        $this->subject = Subject::create([
            'code' => 'SUBJ001',
            'description' => 'Test Subject',
            'unit' => 3,
            'lech' => 3,
            'labh' => 0,
            'lecu' => 3,
            'labu' => 0,
            'type' => 'lecture',
            'status' => 'active',
        ]);

        $this->level = Level::create([
            'code' => 'LVL001',
            'description' => 'First Year',
            'program_id' => $this->program->id,
            'order' => 1,
        ]);
    }

    /**
     * INTEGRATION TEST 1: Test full prospectus creation workflow
     * Tests creating a prospectus through the API endpoint with all relationships
     */
    public function test_create_prospectus_successfully_creates_record_with_relationships(): void
    {
        $response = $this->actingAs($this->registrarUser)
            ->post(route('registrar.prospectus.create'), [
                'curriculum' => $this->curriculum->id,
                'academic_term' => $this->academicTerm->id,
                'level' => $this->level->id,
                'subject' => $this->subject->id,
                'status' => 'active',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Prospectus created successfully',
        ]);

        // Verify database record was created
        $this->assertDatabaseHas('prospectuses', [
            'curriculum_id' => $this->curriculum->id,
            'academic_term_id' => $this->academicTerm->id,
            'level_id' => $this->level->id,
            'subject_id' => $this->subject->id,
            'status' => 'active',
        ]);

        // Verify response contains loaded relationships
        $responseData = $response->json();
        $this->assertArrayHasKey('prospectus', $responseData);
        $this->assertArrayHasKey('curriculum', $responseData['prospectus']);
        $this->assertArrayHasKey('academic_term', $responseData['prospectus']);
        $this->assertArrayHasKey('level', $responseData['prospectus']);
        $this->assertArrayHasKey('subject', $responseData['prospectus']);
    }

    /**
     * INTEGRATION TEST 2: Test prospectus update and delete workflow
     * Tests the complete lifecycle of updating and deleting a prospectus
     */
    public function test_update_and_delete_prospectus_lifecycle(): void
    {
        // First, create a prospectus
        $prospectus = Prospectus::create([
            'curriculum_id' => $this->curriculum->id,
            'academic_term_id' => $this->academicTerm->id,
            'level_id' => $this->level->id,
            'subject_id' => $this->subject->id,
            'status' => 'active',
        ]);

        // Create a second subject for update test
        $newSubject = Subject::create([
            'code' => 'SUBJ002',
            'description' => 'Updated Subject',
            'unit' => 4,
            'lech' => 4,
            'labh' => 0,
            'lecu' => 4,
            'labu' => 0,
            'type' => 'lecture',
            'status' => 'active',
        ]);

        // Test UPDATE operation
        $updateResponse = $this->actingAs($this->registrarUser)
            ->post(route('registrar.prospectus.update', $prospectus->id), [
                'curriculum' => $this->curriculum->id,
                'academic_term' => $this->academicTerm->id,
                'level' => $this->level->id,
                'subject' => $newSubject->id, // Changed subject
                'status' => 'inactive', // Changed status
            ]);

        $updateResponse->assertStatus(200);
        $updateResponse->assertJson([
            'success' => true,
            'message' => 'Prospectus updated successfully',
        ]);

        // Verify database was updated
        $this->assertDatabaseHas('prospectuses', [
            'id' => $prospectus->id,
            'subject_id' => $newSubject->id,
            'status' => 'inactive',
        ]);

        // Test DELETE operation
        $deleteResponse = $this->actingAs($this->registrarUser)
            ->post(route('registrar.prospectus.delete', $prospectus->id));

        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson([
            'success' => true,
            'message' => 'Prospectus deleted successfully',
        ]);

        // Verify record was deleted
        $this->assertDatabaseMissing('prospectuses', [
            'id' => $prospectus->id,
        ]);
    }

    /**
     * INTEGRATION TEST 3: Test search prospectus with filters
     * Tests searching prospectuses by department and academic year
     */
    public function test_search_prospectus_filters_by_department_and_academic_year(): void
    {
        // Create prospectus with known data
        Prospectus::create([
            'curriculum_id' => $this->curriculum->id,
            'academic_term_id' => $this->academicTerm->id,
            'level_id' => $this->level->id,
            'subject_id' => $this->subject->id,
            'status' => 'active',
        ]);

        // Create another department with different prospectus
        $otherDepartment = Department::create([
            'code' => 'DEPT002',
            'description' => 'Other Department',
            'status' => 'active',
        ]);

        $otherCurriculum = Curriculum::create([
            'curriculum' => 'Other Curriculum',
            'department_id' => $otherDepartment->id,
            'status' => 'active',
        ]);

        $otherProgram = Program::create([
            'code' => 'PROG002',
            'description' => 'Other Program',
            'department_id' => $otherDepartment->id,
            'status' => 'active',
        ]);

        $otherLevel = Level::create([
            'code' => 'LVL002',
            'description' => 'Second Year',
            'program_id' => $otherProgram->id,
            'order' => 2,
        ]);

        Prospectus::create([
            'curriculum_id' => $otherCurriculum->id,
            'academic_term_id' => $this->academicTerm->id,
            'level_id' => $otherLevel->id,
            'subject_id' => $this->subject->id,
            'status' => 'active',
        ]);

        // Search for first department's prospectuses
        // Note: The validation expects department as string (ID cast to string)
        $response = $this->actingAs($this->registrarUser)
            ->post(route('registrar.prospectus.search'), [
                'department' => (string) $this->department->id,
                'academic_year' => '2025-2026',
            ]);

        $response->assertStatus(200);
        $response->assertViewHas('prospectuses');
        
        $prospectuses = $response->viewData('prospectuses');
        
        // Should only return prospectuses from the first department
        $this->assertEquals(1, $prospectuses->count());
        $this->assertEquals($this->curriculum->id, $prospectuses->first()->curriculum_id);
    }

    /**
     * INTEGRATION TEST 4: Test getLevelsByDepartment API endpoint
     * Tests that levels are correctly filtered by department
     */
    public function test_get_levels_by_department_returns_correct_levels(): void
    {
        // Create another department with its own levels
        $otherDepartment = Department::create([
            'code' => 'DEPT002',
            'description' => 'Other Department',
            'status' => 'active',
        ]);

        $otherProgram = Program::create([
            'code' => 'PROG002',
            'description' => 'Other Program',
            'department_id' => $otherDepartment->id,
            'status' => 'active',
        ]);

        Level::create([
            'code' => 'LVL002',
            'description' => 'Other Level',
            'program_id' => $otherProgram->id,
            'order' => 1,
        ]);

        // Get levels for first department
        $response = $this->actingAs($this->registrarUser)
            ->get(route('registrar.api.levels', $this->department->id));

        $response->assertStatus(200);
        
        $levels = $response->json();
        
        // Should only return levels from the first department's programs
        $this->assertCount(1, $levels);
        $this->assertEquals('LVL001', $levels[0]['code']);
    }

    /**
     * INTEGRATION TEST 5: Test authentication requirement for protected routes
     * Tests that unauthenticated users cannot access prospectus routes
     */
    public function test_unauthenticated_users_cannot_access_prospectus_routes(): void
    {
        // Test without authentication
        $response = $this->post(route('registrar.prospectus.create'), [
            'curriculum' => $this->curriculum->id,
            'academic_term' => $this->academicTerm->id,
            'level' => $this->level->id,
            'subject' => $this->subject->id,
            'status' => 'active',
        ]);

        // Should redirect to login or return 401/403
        $this->assertTrue(
            $response->status() === 302 || 
            $response->status() === 401 || 
            $response->status() === 403
        );
    }
}
