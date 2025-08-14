<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $schools = $user->schools()->get();
        
        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'shulesoft_code' => 'nullable|string|max:50|unique:schools',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'principal_name' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:100',
            'school_type' => 'nullable|string|max:100',
        ]);

        $school = School::create(array_merge($validated, [
            'status' => 'active',
            'total_students' => 0,
            'fee_collection_percentage' => 0,
            'academic_index' => 0,
            'attendance_percentage' => 0,
        ]));

        // Assign school to current user
        Auth::user()->schools()->attach($school->id);

        return redirect()->route('schools.index')
            ->with('success', 'School added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
