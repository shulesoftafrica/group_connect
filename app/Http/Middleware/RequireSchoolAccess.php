<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RequireSchoolAccess
{
    /**
     * Handle an incoming request.
     *
     * Ensures users have at least one linked school before accessing the platform.
     * Users without schools are redirected to complete onboarding.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware if user is not authenticated
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        
        // Skip middleware for onboarding routes to prevent redirect loops
        $onboardingRoutes = [
            'onboarding.start',
            'onboarding.step1',
            'onboarding.save-step1',
            'onboarding.step2',
            'onboarding.save-step2',
            'onboarding.step3',
            'onboarding.save-step3',
            'onboarding.step4',
            'onboarding.complete',
            'onboarding.success',
            'onboarding.submit',
            'settings.validate-login-code',
            'schools.index',  // Allow access to school management for users without schools
            'schools.create', // Allow access to add schools
            'schools.store',  // Allow storing new schools
            'logout'
        ];
        
        if (in_array($request->route()->getName(), $onboardingRoutes)) {
            return $next($request);
        }

        // Skip middleware for API routes and certain paths
        $skipPaths = [
            '/test-onboarding-data',
            '/api/*',
            '/logout'
        ];
        
        foreach ($skipPaths as $skipPath) {
            if ($request->is($skipPath)) {
                return $next($request);
            }
        }

        // Load user schools
        $userSchools = $user->schools();
        $schoolCount = $userSchools->count();

        Log::info('RequireSchoolAccess middleware check', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'schools_count' => $schoolCount,
            'route' => $request->route()->getName(),
            'path' => $request->path()
        ]);

        // If user has no schools, redirect to onboarding
        if ($schoolCount === 0) {
            Log::warning('User has no schools, redirecting to onboarding', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'attempted_route' => $request->route()->getName(),
                'attempted_path' => $request->path()
            ]);

            session()->flash('warning', 
                'You need to complete the school setup process before accessing the platform. ' .
                'Please link or register your schools to continue.'
            );

            return redirect()->route('onboarding.start')
                ->with('redirect_after_onboarding', $request->fullUrl());
        }

        // User has schools, allow access
        Log::info('User has school access, proceeding', [
            'user_id' => $user->id,
            'schools_count' => $schoolCount
        ]);

        return $next($request);
    }
}
