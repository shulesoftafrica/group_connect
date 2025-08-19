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
        // Validate that 'group_connect_code' is present and is a string
        $validated = $request->validate([
            'group_connect_code' => 'required|string',
        ]);

        // Check if the code exists in shulesoft.setting table
        $setting = \DB::table('shulesoft.setting')
            ->where('login_code', $validated['group_connect_code'])
            ->first();

        if (!$setting) {
            return redirect()->back()
            ->with('error', 'Invalid code supplied.');
        }

        // Record information in shulesoft.connect_schools
        \DB::table('shulesoft.connect_schools')->insert([
            'school_setting_uid' => $setting->uid,
            'connect_organization_id' => Auth::user()->connect_organization_id,
            'connect_user_id' => Auth::id(),
            'is_active' => true,
            'shulesoft_code' => $validated['group_connect_code'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('schools.index')
            ->with('success', 'School connected successfully!');
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
        // Validate the ID parameter

    
        // Verify the record exists and belongs to the authenticated user
        $school = \DB::table('shulesoft.connect_schools')
            ->where('id', $id)
            ->where('connect_user_id', Auth::id())
            ->first();

        if (!$school) {
            return redirect()->route('schools.index')
            ->with('error', 'School not found or you do not have permission to disconnect it.');
        }

        // Delete the record
        $deleted = \DB::table('shulesoft.connect_schools')
            ->where('id', $id)
            ->where('connect_user_id', Auth::id())
            ->delete();

        if ($deleted) {
            return redirect()->route('schools.index')
            ->with('success', 'School disconnected successfully!');
        } else {
            return redirect()->route('schools.index')
            ->with('error', 'Failed to disconnect the school. Please try again.');
        }
    }
}
