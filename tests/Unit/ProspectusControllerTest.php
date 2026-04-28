<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ProspectusController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Prospectus;
use App\Models\Curriculum;
use App\Models\AcademicTerm;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Department;
use App\Models\Program;

class ProspectusControllerTest extends TestCase
{
    use RefreshDatabase;

    protected ProspectusController $controller;
    protected Department $department;
    protected Program $program;
    protected Curriculum $curriculum;
    protected AcademicTerm $academicTerm;
    protected Subject $subject;
    protected Level $level;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ProspectusController();
        
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
     * UNIT TEST 1: Test validation rules for search request
     * Tests that the search validation requires department and academic_year
     */
    public function test_search_request_validation_requires_department_and_academic_year(): void
    {
        $request = new Request([]);
        
        $this->expectException(ValidationException::class);
        
        // Use reflection to access private method
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateSearchRequest');
        $method->setAccessible(true);
        
        $method->invoke($this->controller, $request);
    }

    /**
     * UNIT TEST 2: Test validation rules for prospectus request
     * Tests that prospectus creation validation requires all fields
     */
    public function test_prospectus_request_validation_requires_all_fields(): void
    {
        $request = new Request([
            'curriculum' => '', // Empty required field
            'academic_term' => '',
            'level' => '',
            'subject' => '',
            'status' => '',
        ]);
        
        $this->expectException(ValidationException::class);
        
        // Use reflection to access private method
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateProspectusRequest');
        $method->setAccessible(true);
        
        $method->invoke($this->controller, $request);
    }

    /**
     * UNIT TEST 3: Test prospectus model relationships
     * Tests that all relationships are properly defined on the Prospectus model
     */
    public function test_prospectus_model_has_correct_relationships(): void
    {
        $prospectus = Prospectus::create([
            'curriculum_id' => $this->curriculum->id,
            'academic_term_id' => $this->academicTerm->id,
            'level_id' => $this->level->id,
            'subject_id' => $this->subject->id,
            'status' => 'active',
        ]);

        // Test curriculum relationship
        $this->assertInstanceOf(Curriculum::class, $prospectus->curriculum);
        $this->assertEquals($this->curriculum->id, $prospectus->curriculum->id);

        // Test academicTerm relationship
        $this->assertInstanceOf(AcademicTerm::class, $prospectus->academicTerm);
        $this->assertEquals($this->academicTerm->id, $prospectus->academicTerm->id);

        // Test level relationship
        $this->assertInstanceOf(Level::class, $prospectus->level);
        $this->assertEquals($this->level->id, $prospectus->level->id);

        // Test subject relationship
        $this->assertInstanceOf(Subject::class, $prospectus->subject);
        $this->assertEquals($this->subject->id, $prospectus->subject->id);
    }

    /**
     * UNIT TEST 4: Test prospectus fillable attributes
     * Ensures that the model's fillable array contains expected fields
     */
    public function test_prospectus_model_has_correct_fillable_attributes(): void
    {
        $prospectus = new Prospectus();
        $fillable = $prospectus->getFillable();

        $expectedFillable = [
            'curriculum_id',
            'subject_id',
            'academic_term_id',
            'level_id',
            'status',
        ];

        foreach ($expectedFillable as $field) {
            $this->assertContains($field, $fillable, "Field '{$field}' should be fillable");
        }
    }

    /**
     * UNIT TEST 5: Test status validation only accepts valid values
     * Tests that status field only accepts 'active' or 'inactive'
     */
    public function test_status_validation_only_accepts_active_or_inactive(): void
    {
        $request = new Request([
            'curriculum' => $this->curriculum->id,
            'academic_term' => $this->academicTerm->id,
            'level' => $this->level->id,
            'subject' => $this->subject->id,
            'status' => 'invalid_status', // Invalid status
        ]);

        $this->expectException(ValidationException::class);

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('validateProspectusRequest');
        $method->setAccessible(true);

        $method->invoke($this->controller, $request);
    }
}
