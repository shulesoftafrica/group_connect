<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schools = $user->schools()->active()->get();
        
        // Calculate academic metrics
        $totalStudents = $schools->sum('total_students');
        $avgPerformance = $schools->avg('academic_index');
        $avgAttendance = $schools->avg('attendance_percentage');
        $topSchools = $schools->orderByDesc('academic_index')->take(5);
        $lowPerformingSchools = $schools->where('academic_index', '<', 70)->count();
        
        return view('academics.index', compact(
            'schools',
            'totalStudents',
            'avgPerformance',
            'avgAttendance', 
            'topSchools',
            'lowPerformingSchools'
        ));
    }
}
