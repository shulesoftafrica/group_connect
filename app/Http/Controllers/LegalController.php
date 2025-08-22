<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    /**
     * Display the Privacy Policy page
     */
    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }

    /**
     * Display the Terms of Service page
     */
    public function termsOfService()
    {
        return view('legal.terms-of-service');
    }

    /**
     * Display the AI Policy & Security page
     */
    public function aiPolicyAndSecurity()
    {
        return view('legal.ai-policy-security');
    }

    /**
     * Display the Data Processing Agreement page
     */
    public function dataProcessingAgreement()
    {
        return view('legal.data-processing-agreement');
    }
}
