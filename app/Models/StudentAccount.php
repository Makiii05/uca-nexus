<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class StudentAccount extends Model
{
    protected $fillable = [
        'student_id',
        'account_status',
        'password',
        'examination_permit',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the student that owns this account.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Check if the account is active.
     */
    public function isActive(): bool
    {
        return $this->account_status === 'on';
    }

    /**
     * Verify the password.
     */
    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Create a new account for a student with default password.
     */
    public static function createForStudent(int $studentId, string $password = '123'): self
    {
        return self::create([
            'student_id' => $studentId,
            'account_status' => 'off',
            'password' => $password,
        ]);
    }
}
