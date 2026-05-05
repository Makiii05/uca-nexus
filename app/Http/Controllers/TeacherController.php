<?php

namespace App\Http\Controllers;

use App\Models\TeacherAccount;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    public function showTeacher()
    {
        $teachers = Teacher::with('account')
            ->orderBy('created_at', 'asc')
            ->orderBy('status', 'asc')
            ->get();

        return view('registrar.teacher', [
            'teachers' => $teachers,
        ]);
    }

    public function createTeacher(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:teachers,code',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email',
            'status' => 'required|in:active,inactive',
        ]);

        DB::transaction(function () use ($validated) {
            $teacher = Teacher::create($validated);

            TeacherAccount::create([
                'teacher_id' => $teacher->id,
                'password' => Hash::make('123'),
                'status' => 'off',
            ]);
        });

        return redirect()->route('registrar.teacher')->with('success', 'Teacher created successfully');
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:teachers,code,' . $teacher->id,
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email,' . $teacher->id,
            'status' => 'required|in:active,inactive',
        ]);

        $teacher->update($validated);

        return redirect()->route('registrar.teacher')->with('success', 'Teacher updated successfully');
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('registrar.teacher')->with('success', 'Teacher deleted successfully');
    }

    public function toggleTeacherAccountStatus($id)
    {
        $teacher = Teacher::with('account')->findOrFail($id);

        if (!$teacher->account) {
            TeacherAccount::create([
                'teacher_id' => $teacher->id,
                'password' => Hash::make('123'),
                'status' => 'on',
            ]);

            return redirect()
                ->route('registrar.teacher')
                ->with('success', 'Teacher portal account opened. Default password is 123.');
        }

        $newStatus = $teacher->account->status === 'on' ? 'off' : 'on';
        $teacher->account->update(['status' => $newStatus]);

        $message = $newStatus === 'on'
            ? 'Teacher portal account opened.'
            : 'Teacher portal account closed.';

        return redirect()->route('registrar.teacher')->with('success', $message);
    }

    /**
     * Import teachers from CSV.
     * Expected headers: code, first_name, middle_name (optional), last_name, email, status (optional)
     */
    public function importTeachers(Request $request)
    {
        $request->validate([
            'teachers_csv' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('teachers_csv');
        if (!$file) {
            return redirect()->route('registrar.teacher')->with('error', 'No file uploaded.');
        }

        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return redirect()->route('registrar.teacher')->with('error', 'Unable to read the uploaded file.');
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return redirect()->route('registrar.teacher')->with('error', 'CSV header row is missing.');
        }

        $header = array_map(function ($h) {
            $h = (string) $h;
            $h = preg_replace('/^\xEF\xBB\xBF/', '', $h); // strip UTF-8 BOM
            return strtolower(trim($h));
        }, $header);

        $requiredHeaders = ['code', 'first_name', 'last_name', 'email'];
        foreach ($requiredHeaders as $req) {
            if (!in_array($req, $header, true)) {
                fclose($handle);
                return redirect()->route('registrar.teacher')->with(
                    'error',
                    'Invalid CSV format. Required headers: ' . implode(', ', $requiredHeaders)
                );
            }
        }

        $imported = 0;
        $skipped = 0;
        $rowErrors = [];
        $rowNumber = 1; // header is row 1

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }

            $data = [];
            foreach ($header as $idx => $key) {
                $data[$key] = isset($row[$idx]) ? trim((string) $row[$idx]) : null;
            }

            $payload = [
                'code' => $data['code'] ?? null,
                'first_name' => $data['first_name'] ?? null,
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'] ?? null,
                'status' => strtolower($data['status'] ?? 'active'),
            ];

            $validator = Validator::make($payload, [
                'code' => 'required|string|max:255|unique:teachers,code',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:teachers,email',
                'status' => 'nullable|in:active,inactive',
            ]);

            if ($validator->fails()) {
                $skipped++;
                $rowErrors[] = 'Row ' . $rowNumber . ': ' . implode(' ', $validator->errors()->all());
                continue;
            }

            try {
                DB::transaction(function () use ($payload, &$imported) {
                    $teacher = Teacher::create([
                        'code' => $payload['code'],
                        'first_name' => $payload['first_name'],
                        'middle_name' => $payload['middle_name'] ?: null,
                        'last_name' => $payload['last_name'],
                        'email' => $payload['email'],
                        'status' => $payload['status'] ?: 'active',
                    ]);

                    TeacherAccount::create([
                        'teacher_id' => $teacher->id,
                        'password' => Hash::make('123'),
                        'status' => 'off',
                    ]);

                    $imported++;
                });
            } catch (\Throwable $e) {
                $skipped++;
                $rowErrors[] = 'Row ' . $rowNumber . ': Failed to import (DB error).';
            }
        }

        fclose($handle);

        if (!empty($rowErrors)) {
            $preview = array_slice($rowErrors, 0, 5);
            $moreCount = max(count($rowErrors) - count($preview), 0);
            $message = "Imported {$imported}. Skipped {$skipped}.";
            if ($moreCount > 0) {
                $message .= " Showing first " . count($preview) . " errors.";
            }

            return redirect()
                ->route('registrar.teacher')
                ->with('warning', $message)
                ->withErrors($preview);
        }

        return redirect()
            ->route('registrar.teacher')
            ->with('success', "Imported {$imported} teacher(s) successfully.");
    }

    /**
     * Download a sample CSV for importing teachers.
     */
    public function downloadTeacherImportSample()
    {
        $filename = 'teachers_import_sample.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $rows = [
            ['code', 'first_name', 'middle_name', 'last_name', 'email', 'status'],
            ['TCH-0001', 'Juan', 'D', 'Cruz', 'juan.cruz@example.com', 'active'],
            ['TCH-0002', 'Maria', '', 'Santos', 'maria.santos@example.com', 'active'],
        ];

        return response()->stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, 200, $headers);
    }
}
