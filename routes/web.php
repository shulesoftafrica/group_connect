<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\OperationsController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\CommunicationsController;
use App\Http\Controllers\DigitalLearningController;
use App\Http\Controllers\InsightsController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Schools Management
    Route::resource('schools', SchoolController::class);
    
    // Academics Dashboard
    Route::get('/academics', [AcademicController::class, 'index'])->name('academics.index');
    Route::get('/academics/school/{id}', [AcademicController::class, 'schoolDetail'])->name('academics.school');
    Route::get('/academics/export', [AcademicController::class, 'exportReport'])->name('academics.export');
    
    // Operations Dashboard
    Route::get('/operations', [OperationsController::class, 'index'])->name('operations.index');
    Route::get('/operations/school/{id}', [OperationsController::class, 'schoolDetail'])->name('operations.school');
    Route::post('/operations/export', [OperationsController::class, 'exportReport'])->name('operations.export');
    Route::post('/operations/bulk-action', [OperationsController::class, 'bulkAction'])->name('operations.bulk-action');
    
    // Finance Dashboard
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/school/{id}', [FinanceController::class, 'schoolDetail'])->name('finance.school');
    Route::get('/finance/reconciliation', [FinanceController::class, 'reconciliation'])->name('finance.reconciliation');
    Route::post('/finance/export', [FinanceController::class, 'exportReport'])->name('finance.export');
    Route::post('/finance/bank-reconciliation', [FinanceController::class, 'bankReconciliation'])->name('finance.bank-reconciliation');
    Route::post('/finance/import-statement', [FinanceController::class, 'importStatement'])->name('finance.import-statement');
    
    // Human Resources Dashboard
    Route::get('/hr', [HRController::class, 'index'])->name('hr.index');
    Route::get('/hr/school/{id}', [HRController::class, 'schoolDetail'])->name('hr.school');
    Route::get('/hr/staff-directory', [HRController::class, 'staffDirectory'])->name('hr.staff-directory');
    Route::get('/hr/recruitment', [HRController::class, 'recruitment'])->name('hr.recruitment');
    Route::get('/hr/leave-management', [HRController::class, 'leaveManagement'])->name('hr.leave-management');
    Route::get('/hr/payroll-management', [HRController::class, 'payrollManagement'])->name('hr.payroll-management');
    Route::post('/hr/export', [HRController::class, 'exportReport'])->name('hr.export');
    Route::post('/hr/bulk-action', [HRController::class, 'bulkAction'])->name('hr.bulk-action');
    
    // Communications Dashboard
    Route::get('/communications', [CommunicationsController::class, 'dashboard'])->name('communications.index');
    Route::get('/communications/campaigns', [CommunicationsController::class, 'campaigns'])->name('communications.campaigns');
    Route::get('/communications/messaging', [CommunicationsController::class, 'messaging'])->name('communications.messaging');
    Route::get('/communications/analytics', [CommunicationsController::class, 'analytics'])->name('communications.analytics');
    Route::get('/communications/feedback', [CommunicationsController::class, 'feedback'])->name('communications.feedback');
    Route::post('/communications/send-message', [CommunicationsController::class, 'sendMessage'])->name('communications.send-message');
    Route::post('/communications/create-campaign', [CommunicationsController::class, 'createCampaign'])->name('communications.create-campaign');
    
    // Digital Learning Dashboard
    Route::get('/digital-learning', [DigitalLearningController::class, 'dashboard'])->name('digital-learning.index');
    Route::get('/digital-learning/exams', [DigitalLearningController::class, 'exams'])->name('digital-learning.exams');
    Route::get('/digital-learning/content', [DigitalLearningController::class, 'contentManagement'])->name('digital-learning.content');
    Route::get('/digital-learning/analytics', [DigitalLearningController::class, 'analytics'])->name('digital-learning.analytics');
    Route::get('/digital-learning/ai-tools', [DigitalLearningController::class, 'aiTools'])->name('digital-learning.ai-tools');
    Route::post('/digital-learning/create-ai-exam', [DigitalLearningController::class, 'createAIExam'])->name('digital-learning.create-ai-exam');
    Route::post('/digital-learning/generate-ai-notes', [DigitalLearningController::class, 'generateAINotes'])->name('digital-learning.generate-ai-notes');
    Route::post('/digital-learning/bulk-content-push', [DigitalLearningController::class, 'bulkContentPush'])->name('digital-learning.bulk-content-push');
    
    // Executive Insights & Analytics Dashboard
    Route::get('/insights', [InsightsController::class, 'dashboard'])->name('insights.dashboard');
    Route::get('/insights/ai-chat', [InsightsController::class, 'aiChat'])->name('insights.ai-chat');
    Route::get('/insights/reports', [InsightsController::class, 'reports'])->name('insights.reports');
    Route::get('/insights/alerts', [InsightsController::class, 'alerts'])->name('insights.alerts');
    Route::get('/insights/analytics', [InsightsController::class, 'analytics'])->name('insights.analytics');
    Route::post('/insights/ai-query', [InsightsController::class, 'processAIQuery'])->name('insights.ai-query');
    Route::post('/insights/export', [InsightsController::class, 'exportReport'])->name('insights.export');
    
    // Settings & Control Panel
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/users', [SettingsController::class, 'users'])->name('settings.users');
    Route::post('/settings/users', [SettingsController::class, 'storeUser'])->name('settings.users.store');
    Route::put('/settings/users/{id}', [SettingsController::class, 'updateUser'])->name('settings.users.update');
    Route::delete('/settings/users/{id}', [SettingsController::class, 'deleteUser'])->name('settings.users.delete');
    Route::get('/settings/schools', [SettingsController::class, 'schools'])->name('settings.schools');
    Route::post('/settings/schools', [SettingsController::class, 'storeSchool'])->name('settings.schools.store');
    Route::get('/settings/academic-years', [SettingsController::class, 'academicYears'])->name('settings.academic-years');
    Route::post('/settings/academic-years', [SettingsController::class, 'storeAcademicYear'])->name('settings.academic-years.store');
    Route::get('/settings/roles-permissions', [SettingsController::class, 'rolesPermissions'])->name('settings.roles-permissions');
    Route::post('/settings/roles', [SettingsController::class, 'storeRole'])->name('settings.roles.store');
    Route::get('/settings/system-config', [SettingsController::class, 'systemConfig'])->name('settings.system-config');
    Route::post('/settings/system-config', [SettingsController::class, 'updateSystemConfig'])->name('settings.system-config.update');
    Route::get('/settings/bulk-operations', [SettingsController::class, 'bulkOperations'])->name('settings.bulk-operations');
    Route::post('/settings/bulk-operations', [SettingsController::class, 'processBulkOperation'])->name('settings.bulk-operations.process');
    Route::get('/settings/audit-logs', [SettingsController::class, 'auditLogs'])->name('settings.audit-logs');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
