<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return response()->json(Student::all(), 200);
    }

    // POST /api/students
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_ID' => [
                'required',
                'string',
                'max:50',
                'unique:students,student_ID',
            ],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email',
            'course' => 'required|string|max:100',
            'year_level' => 'required|string|max:50',
        ]);

        $student = Student::create($validated);

        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student,
        ], 201);
    }

    // GET /api/students/{student}
    public function show(Student $student)
    {
        return response()->json($student, 200);
    }

    // PUT /api/students/{student}
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_ID' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'student_ID')
                    ->ignore($student->id),
            ],
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('students', 'email')
                    ->ignore($student->id),
            ],
            'course' => 'sometimes|required|string|max:100',
            'year_level' => 'sometimes|required|string|max:50',
        ]);

        $student->update($validated);

        return response()->json([
            'message' => 'Student updated successfully',
            'student' => $student->fresh(),
        ], 200);
    }

    // DELETE /api/students/{student}
    public function destroy(Student $student)
    {
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully',
        ], 200);
    }
}
