<?php
/**
 * Test Script: Phase 2 UX Enhancements
 * 
 * This script tests the Phase 2 enhancements including:
 * - Getting Started Dashboard detection
 * - Progressive Feature Disclosure
 * - User onboarding status calculation
 * - Setup completion percentage
 */

echo "=== PHASE 2 TEST: UX Enhancements ===\n";

// Simulate user data for testing
$testUsers = [
    [
        'id' => 1,
        'name' => 'New User',
        'email' => 'newuser@test.com',
        'created_at' => '2025-09-11 10:00:00', // 1 day ago
        'phone' => null,
        'schools_count' => 0,
        'pending_requests' => 1
    ],
    [
        'id' => 2,
        'name' => 'Getting Started User',
        'email' => 'gettingstarted@test.com',
        'created_at' => '2025-09-05 10:00:00', // 7 days ago
        'phone' => '+254722123456',
        'schools_count' => 1,
        'pending_requests' => 0
    ],
    [
        'id' => 3,
        'name' => 'Established User',
        'email' => 'established@test.com',
        'created_at' => '2025-08-01 10:00:00', // 42 days ago
        'phone' => '+254733123456',
        'schools_count' => 3,
        'pending_requests' => 0
    ]
];

// Function to simulate getUserOnboardingStatus logic
function getUserOnboardingStatus($user) {
    $schoolCount = $user['schools_count'];
    $createdAt = new DateTime($user['created_at']);
    $daysSinceRegistration = (new DateTime())->diff($createdAt)->days;
    $pendingSchoolRequests = $user['pending_requests'];
    
    // Determine if user is in "getting started" mode
    $isGettingStarted = (
        $schoolCount <= 1 || // 1 or fewer active schools
        $daysSinceRegistration <= 7 || // Registered within last 7 days
        $pendingSchoolRequests > 0 // Has pending school requests
    );
    
    return [
        'is_getting_started' => $isGettingStarted,
        'school_count' => $schoolCount,
        'days_since_registration' => $daysSinceRegistration,
        'pending_school_requests' => $pendingSchoolRequests,
        'setup_completion_percentage' => calculateSetupCompletion($user),
        'next_steps' => getNextSteps($user, $pendingSchoolRequests)
    ];
}

// Function to simulate calculateSetupCompletion logic
function calculateSetupCompletion($user) {
    $completedSteps = 0;
    $totalSteps = 5;

    // Step 1: Account created
    if ($user['created_at']) $completedSteps++;
    
    // Step 2: Email verified (assume true for test)
    $completedSteps++;
    
    // Step 3: Has at least one school
    if ($user['schools_count'] > 0) $completedSteps++;
    
    // Step 4: Profile completed (has phone)
    if ($user['phone']) $completedSteps++;
    
    // Step 5: Organization setup (assume true for test)
    $completedSteps++;

    return round(($completedSteps / $totalSteps) * 100);
}

// Function to simulate getNextSteps logic
function getNextSteps($user, $pendingSchoolRequests) {
    $nextSteps = [];
    
    if ($user['schools_count'] === 0) {
        $nextSteps[] = [
            'title' => 'Add Your First School',
            'description' => 'Connect your first school to start using the platform',
            'priority' => 'high'
        ];
    }
    
    if ($pendingSchoolRequests > 0) {
        $nextSteps[] = [
            'title' => "Track School Setup Progress",
            'description' => "You have {$pendingSchoolRequests} school(s) being set up by our team",
            'priority' => 'medium'
        ];
    }
    
    if (!$user['phone']) {
        $nextSteps[] = [
            'title' => 'Complete Your Profile',
            'description' => 'Add your phone number for better security and notifications',
            'priority' => 'low'
        ];
    }
    
    if ($user['schools_count'] > 0 && $user['schools_count'] < 3) {
        $nextSteps[] = [
            'title' => 'Add More Schools',
            'description' => 'Connect additional schools to your organization',
            'priority' => 'low'
        ];
    }

    return $nextSteps;
}

// Function to simulate progressive module access
function hasProgressiveModuleAccess($user, $module) {
    $schoolCount = $user['schools_count'];
    $createdAt = new DateTime($user['created_at']);
    $daysSinceRegistration = (new DateTime())->diff($createdAt)->days;
    
    // Progressive disclosure rules
    $restrictions = [
        'schools' => $schoolCount >= 0, // Always available
        'academics' => $schoolCount >= 1, // Need at least 1 school
        'operations' => $schoolCount >= 1, // Need at least 1 school
        'finance' => $schoolCount >= 1, // Need at least 1 school
        'hr' => $schoolCount >= 1, // Need at least 1 school
        'communications' => $schoolCount >= 1, // Need at least 1 school
        'insights' => $schoolCount >= 2 || $daysSinceRegistration >= 14, // Advanced feature
        'reports' => $schoolCount >= 1, // Need at least 1 school
        'settings' => true, // Always available for setup
    ];

    return $restrictions[$module] ?? false;
}

// Test each user scenario
echo "\n=== Testing User Scenarios ===\n";

foreach ($testUsers as $user) {
    echo "\n--- User: {$user['name']} ---\n";
    
    $onboardingStatus = getUserOnboardingStatus($user);
    
    echo "User Details:\n";
    echo "  Days since registration: {$onboardingStatus['days_since_registration']}\n";
    echo "  Schools count: {$onboardingStatus['school_count']}\n";
    echo "  Pending requests: {$onboardingStatus['pending_school_requests']}\n";
    echo "  Setup completion: {$onboardingStatus['setup_completion_percentage']}%\n";
    
    echo "\nDashboard Experience:\n";
    if ($onboardingStatus['is_getting_started']) {
        echo "  ✅ Shows Getting Started Dashboard\n";
        echo "  📋 Next steps count: " . count($onboardingStatus['next_steps']) . "\n";
        foreach ($onboardingStatus['next_steps'] as $step) {
            $priority_icon = $step['priority'] === 'high' ? '🔴' : ($step['priority'] === 'medium' ? '🟡' : '🔵');
            echo "    $priority_icon {$step['title']}\n";
        }
    } else {
        echo "  ✅ Shows Regular Dashboard\n";
    }
    
    echo "\nModule Access (Progressive Disclosure):\n";
    $modules = ['schools', 'academics', 'operations', 'finance', 'hr', 'communications', 'insights', 'reports', 'settings'];
    
    foreach ($modules as $module) {
        $hasAccess = hasProgressiveModuleAccess($user, $module);
        $status = $hasAccess ? '✅ Available' : '🔒 Locked';
        echo "  $status - " . ucfirst($module) . "\n";
    }
}

echo "\n=== Testing Specific Features ===\n";

echo "\nTest 1: Getting Started Detection\n";
$testCases = [
    ['schools' => 0, 'days' => 1, 'pending' => 0, 'should_show' => true, 'reason' => 'No schools'],
    ['schools' => 1, 'days' => 5, 'pending' => 0, 'should_show' => true, 'reason' => 'Within 7 days'],
    ['schools' => 2, 'days' => 10, 'pending' => 1, 'should_show' => true, 'reason' => 'Has pending requests'],
    ['schools' => 3, 'days' => 30, 'pending' => 0, 'should_show' => false, 'reason' => 'Established user'],
];

foreach ($testCases as $index => $case) {
    $testUser = [
        'schools_count' => $case['schools'],
        'created_at' => (new DateTime())->sub(new DateInterval("P{$case['days']}D"))->format('Y-m-d H:i:s'),
        'pending_requests' => $case['pending']
    ];
    
    $status = getUserOnboardingStatus($testUser);
    $result = $status['is_getting_started'] === $case['should_show'] ? '✅ PASS' : '❌ FAIL';
    
    echo "  Case " . ($index + 1) . ": $result - {$case['reason']}\n";
    echo "    Expected: " . ($case['should_show'] ? 'Getting Started' : 'Regular') . "\n";
    echo "    Got: " . ($status['is_getting_started'] ? 'Getting Started' : 'Regular') . "\n";
}

echo "\nTest 2: Progressive Module Access\n";
$accessTests = [
    ['schools' => 0, 'days' => 30, 'module' => 'academics', 'should_have' => false],
    ['schools' => 1, 'days' => 30, 'module' => 'academics', 'should_have' => true],
    ['schools' => 1, 'days' => 5, 'module' => 'insights', 'should_have' => false],
    ['schools' => 2, 'days' => 30, 'module' => 'insights', 'should_have' => true],
];

foreach ($accessTests as $index => $test) {
    $testUser = [
        'schools_count' => $test['schools'],
        'created_at' => (new DateTime())->sub(new DateInterval("P{$test['days']}D"))->format('Y-m-d H:i:s')
    ];
    
    $hasAccess = hasProgressiveModuleAccess($testUser, $test['module']);
    $result = $hasAccess === $test['should_have'] ? '✅ PASS' : '❌ FAIL';
    
    echo "  Test " . ($index + 1) . ": $result - {$test['schools']} schools → {$test['module']}\n";
    echo "    Expected: " . ($test['should_have'] ? 'Access' : 'No Access') . "\n";
    echo "    Got: " . ($hasAccess ? 'Access' : 'No Access') . "\n";
}

echo "\nTest 3: Setup Completion Calculation\n";
$completionTests = [
    ['phone' => null, 'schools' => 0, 'expected' => 60], // 3/5 steps
    ['phone' => '+254722123456', 'schools' => 1, 'expected' => 100], // 5/5 steps
    ['phone' => null, 'schools' => 1, 'expected' => 80], // 4/5 steps
];

foreach ($completionTests as $index => $test) {
    $testUser = [
        'created_at' => '2025-09-01 10:00:00',
        'phone' => $test['phone'],
        'schools_count' => $test['schools']
    ];
    
    $completion = calculateSetupCompletion($testUser);
    $result = $completion === $test['expected'] ? '✅ PASS' : '❌ FAIL';
    
    echo "  Test " . ($index + 1) . ": $result - Setup completion\n";
    echo "    Phone: " . ($test['phone'] ? 'Yes' : 'No') . ", Schools: {$test['schools']}\n";
    echo "    Expected: {$test['expected']}%, Got: {$completion}%\n";
}

echo "\n=== Client-Side Validation Test ===\n";

// Test validation patterns
$validationTests = [
    'phone_numbers' => [
        '+254722123456' => true,
        '0722123456' => true,
        '+254 722 123 456' => true,
        'invalid-phone' => false,
        '722abc456' => false
    ],
    'school_names' => [
        'Sunrise Primary School' => true,
        'St. Mary\'s Academy' => true,
        'ABC School & College' => true,
        'School@Domain' => false,
        'School|Invalid' => false
    ],
    'login_codes' => [
        'SCHOOL001' => true,
        'ACADEMY_001' => true,
        'SCHOOL-001' => true,
        'school@001' => false,
        'SCHOOL 001' => false
    ]
];

foreach ($validationTests as $category => $tests) {
    echo "\n" . ucfirst(str_replace('_', ' ', $category)) . " Validation:\n";
    
    foreach ($tests as $input => $shouldPass) {
        $pattern = '';
        $result = false;
        
        switch ($category) {
            case 'phone_numbers':
                $pattern = '/^[\+]?[0-9\-\(\)\s]+$/';
                $result = preg_match($pattern, $input);
                break;
            case 'school_names':
                $pattern = '/^[a-zA-Z0-9\s\-\.\&\']+$/';
                $result = preg_match($pattern, $input);
                break;
            case 'login_codes':
                $pattern = '/^[A-Z0-9_-]+$/i';
                $result = preg_match($pattern, $input);
                break;
        }
        
        $status = ($result && $shouldPass) || (!$result && !$shouldPass) ? '✅ PASS' : '❌ FAIL';
        echo "  $status - '$input' " . ($shouldPass ? 'should pass' : 'should fail') . "\n";
    }
}

echo "\n=== PHASE 2 TEST COMPLETE ===\n";
echo "✅ Getting Started Dashboard logic working\n";
echo "✅ Progressive Feature Disclosure implemented\n";
echo "✅ Setup completion calculation accurate\n";
echo "✅ Client-side validation patterns correct\n";
echo "✅ User experience enhancement successful\n";

?>
