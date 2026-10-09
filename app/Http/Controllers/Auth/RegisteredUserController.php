<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): RedirectResponse
    {
        // The stock Breeze registration screen was never built for this app (no auth.register view, and a plain
        // users row has no connect_organization_id). Group accounts are created through onboarding.
        return $this->registrationClosed();
    }

    private function registrationClosed(): RedirectResponse
    {
        return redirect()->route('login')->with('status', 'Registration is by invitation. Please contact ShuleSoft to set up your group account.');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (! config('auth.public_registration', false)) {
            return $this->registrationClosed();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // Insert email record into the shulesoft.email table
        \DB::table('shulesoft.email')->insert([
            'body' => 'Welcome to our platform, ' . $user->name . '!',
            'subject' => 'Welcome Email',
            'user_id' => $user->id,
            'email' => $user->email,
            'schema_name' => 'shulesoft',
            'table' => 'users',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
