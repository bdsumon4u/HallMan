<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Models\Student;

class StudentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StudentRequest $request)
    {
        $query = Student::query()
            ->when($request->filled('search'), fn($query) => $query->whereAny([
                'name',
                'phone',
                'department',
                'session',
                'year',
            ], 'like', "%{$request->search}%"))
            ->when($request->filled('department'), function ($query) use ($request) {
                $query->where('department', $request->department);
            })
            ->when($request->filled('session'), function ($query) use ($request) {
                $query->where('session', $request->session);
            })
            ->when($request->filled('year'), function ($query) use ($request) {
                $query->where('year', $request->year);
            })
            ->when($request->filled('hall_id'), function ($query) use ($request) {
                $query->where('hall_id', $request->hall);
            });

        $students = $request->filled('per_page') ? $query->paginate($request->per_page) : $query->get();
        
        return StudentResource::collection($students);
    }
}
