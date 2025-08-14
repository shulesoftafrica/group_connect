<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get schools accessible to this user
        $schoolsQuery = $user->schools()->active();
        $schools = $schoolsQuery->get();
        
        // Calculate dashboard metrics
        $totalStudents = $schools->sum('total_students');
        $avgAttendance = $schools->avg('attendance_percentage');
        $feesCollected = $schools->sum('fee_collection_percentage') * 10000; // Mock calculation
        $activeSchools = $schools->where('status', 'active')->count();
        $totalSchools = $schools->count();
        
        // Get top performing schools
        $topSchools = $schoolsQuery->orderBy('id')->take(5)->get();

        return view('dashboard', compact(
            'totalStudents',
            'avgAttendance', 
            'feesCollected',
            'activeSchools',
            'totalSchools',
            'topSchools'
        ));
    }
}
