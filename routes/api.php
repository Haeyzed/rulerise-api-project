<?php

use App\Http\Controllers\Api\Admin\CvPackageController;
use App\Http\Controllers\Api\Admin\JobCategoryController;
use App\Http\Controllers\Api\Admin\GeneralSettingController;
use App\Http\Controllers\Api\Admin\WebsiteCustomizationController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\Candidate\AuthController as CandidateAuthController;
use App\Http\Controllers\Api\Candidate\ProfileController as CandidateProfileController;
use App\Http\Controllers\Api\Candidate\JobController as CandidateJobController;
use App\Http\Controllers\Api\Candidate\CvController as CandidateCvController;
use App\Http\Controllers\Api\Employer\AuthController as EmployerAuthController;
use App\Http\Controllers\Api\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Api\Employer\CandidateController as EmployerCandidateController;
use App\Http\Controllers\Api\Employer\TemplateController as EmployerTemplateController;
use App\Http\Controllers\Api\Employer\CvPackageController as EmployerCvPackageController;
use App\Http\Controllers\Api\Employer\ProfileController as EmployerProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::prefix('plan')->group(function () {
        Route::get('/', [CvPackageController::class, 'index']);
        Route::get('/{id}', [CvPackageController::class, 'show']);
        Route::post('/', [CvPackageController::class, 'store']);
        Route::post('/update', [CvPackageController::class, 'update']);
        Route::post('/setActive', [CvPackageController::class, 'setActive']);
        Route::post('/{id}/delete', [CvPackageController::class, 'destroy']);
    });

    Route::prefix('job-category')->group(function () {
        Route::get('/', [JobCategoryController::class, 'index']);
        Route::post('/', [JobCategoryController::class, 'store']);
        Route::post('/update', [JobCategoryController::class, 'update']);
        Route::post('/{id}/delete', [JobCategoryController::class, 'destroy']);
        Route::post('/{id}/setActive', [JobCategoryController::class, 'setActive']);
    });

    Route::prefix('general-setting')->group(function () {
        Route::get('/', [GeneralSettingController::class, 'index']);
        Route::post('/', [GeneralSettingController::class, 'update']);
    });

    Route::prefix('website-customization')->group(function () {
        Route::get('/{type}', [WebsiteCustomizationController::class, 'show']);
        Route::post('/', [WebsiteCustomizationController::class, 'update']);
        Route::post('/createNewContact', [WebsiteCustomizationController::class, 'createNewContact']);
        Route::post('/uploadImage', [WebsiteCustomizationController::class, 'uploadImage']);
    });

    Route::prefix('user-management')->group(function () {
        Route::prefix('role')->group(function () {
            Route::get('/', [UserManagementController::class, 'index']);
            Route::get('/{slug}', [UserManagementController::class, 'show']);
            Route::post('/', [UserManagementController::class, 'store']);
            Route::post('/update', [UserManagementController::class, 'update']);
            Route::post('/{slug}/delete', [UserManagementController::class, 'destroy']);
            Route::get('/permissions', [UserManagementController::class, 'permissions']);
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserManagementController::class, 'listUsers']);
            Route::get('/{id}', [UserManagementController::class, 'showUser']);
            Route::post('/', [UserManagementController::class, 'createUser']);
            Route::post('/update', [UserManagementController::class, 'updateUser']);
            Route::post('/{id}/delete', [UserManagementController::class, 'deleteUser']);
        });
    });

    Route::get('/dashboard-overview', [DashboardOverviewController::class, 'index']);
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword']);
});

Route::middleware(['auth:sanctum'])->prefix('candidate')->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [CandidateProfileController::class, 'show']);
        Route::post('/update', [CandidateProfileController::class, 'update']);
        Route::post('/upload-profile-picture', [CandidateProfileController::class, 'uploadProfilePicture']);
        Route::prefix('work-experience')->group(function () {
            Route::post('/', [CandidateProfileController::class, 'createWorkExperience']);
            Route::post('/update', [CandidateProfileController::class, 'updateWorkExperience']);
            Route::post('/{id}/delete', [CandidateProfileController::class, 'deleteWorkExperience']);
        });
        Route::prefix('education-history')->group(function () {
            Route::post('/', [CandidateProfileController::class, 'createEducationHistory']);
            Route::post('/update', [CandidateProfileController::class, 'updateEducationHistory']);
            Route::post('/{id}/delete', [CandidateProfileController::class, 'deleteEducationHistory']);
        });
        Route::prefix('credential')->group(function () {
            Route::post('/', [CandidateProfileController::class, 'createCredential']);
            Route::post('/update', [CandidateProfileController::class, 'updateCredential']);
            Route::post('/{id}/delete', [CandidateProfileController::class, 'deleteCredential']);
        });
        Route::prefix('language')->group(function () {
            Route::post('/', [CandidateProfileController::class, 'createLanguage']);
            Route::post('/update', [CandidateProfileController::class, 'updateLanguage']);
            Route::post('/{id}/delete', [CandidateProfileController::class, 'deleteLanguage']);
        });
    });

    Route::prefix('auth')->group(function () {
        Route::post('/resendEmailVerification/{email}', [CandidateAuthController::class, 'resendEmailVerification']);
        Route::post('/verifyEmail', [CandidateAuthController::class, 'verifyEmail']);
        Route::post('/forgot-password/{email}', [CandidateAuthController::class, 'forgotPassword']);
        Route::post('/verify-forgot-password', [CandidateAuthController::class, 'verifyForgotPassword']);
        Route::post('/reset-password', [CandidateAuthController::class, 'resetPassword']);
    });

    Route::prefix('account-setting')->group(function () {
        Route::get('/', [CandidateProfileController::class, 'retrieveSettings']);
        Route::post('/update-account-setting', [CandidateProfileController::class, 'updateSettings']);
        Route::post('/delete-account', [CandidateProfileController::class, 'deleteAccount']);
        Route::post('/change-password', [CandidateProfileController::class, 'changePassword']);
    });

    Route::prefix('job')->group(function () {
        Route::get('/', [CandidateJobController::class, 'index']);
        Route::post('/{jobId}/saveJob', [CandidateJobController::class, 'saveJob']);
        Route::post('/applyJob', [CandidateJobController::class, 'applyJob']);
        Route::get('/{jobId}/detail', [CandidateJobController::class, 'show']);
        Route::get('/{jobId}/similarJobs', [CandidateJobController::class, 'similarJobs']);
        Route::post('/{jobId}/reportJob', [CandidateJobController::class, 'reportJob']);
    });

    Route::prefix('cv')->group(function () {
        Route::post('/upload', [CandidateCvController::class, 'upload']);
        Route::get('/detail', [CandidateCvController::class, 'show']);
        Route::post('/{cvId}/delete', [CandidateCvController::class, 'delete']);
    });

    Route::get('/languageProficiency', [CandidateProfileController::class, 'languageProficiency']);
    Route::get('/metrics', [CandidateProfileController::class, 'metrics']);
});

Route::middleware(['auth:sanctum'])->prefix('employer')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/resendEmailVerification/{email}', [EmployerAuthController::class, 'resendEmailVerification']);
        Route::post('/verifyEmail', [EmployerAuthController::class, 'verifyEmail']);
        Route::post('/forgot-password/{email}', [EmployerAuthController::class, 'forgotPassword']);
        Route::post('/verify-forgot-password', [EmployerAuthController::class, 'verifyForgotPassword']);
        Route::post('/reset-password', [EmployerAuthController::class, 'resetPassword']);
    });

    Route::prefix('job')->group(function () {
        Route::get('/', [EmployerJobController::class, 'index']);
        Route::post('/', [EmployerJobController::class, 'store']);
        Route::post('/update', [EmployerJobController::class, 'update']);
        Route::get('/{jobId}', [EmployerJobController::class, 'show']);
        Route::post('/{jobId}/delete', [EmployerJobController::class, 'destroy']);
        Route::get('/{jobId}/filterApplicantsByJob', [EmployerJobController::class, 'filterApplicantsByJob']);
        Route::post('/applicants/update-hiring-stage', [EmployerJobController::class, 'updateHiringStage']);
    });

    Route::prefix('candidate')->group(function () {
        Route::get('/', [EmployerCandidateController::class, 'index']);
        Route::get('/{candidateId}', [EmployerCandidateController::class, 'show']);
        Route::post('/application/change-hiring-stage', [EmployerCandidateController::class, 'changeHiringStage']);
    });

    Route::prefix('template')->group(function () {
        Route::get('/', [EmployerTemplateController::class, 'index']);
        Route::post('/', [EmployerTemplateController::class, 'update']);
    });

    Route::prefix('cv-packages')->group(function () {
        Route::get('/', [EmployerCvPackageController::class, 'index']);
        Route::get('/subscription-detail', [EmployerCvPackageController::class, 'subscriptionDetail']);
        Route::post('/{packageId}/subscribe', [EmployerCvPackageController::class, 'subscribe']);
        Route::post('/update-download-usage', [EmployerCvPackageController::class, 'updateDownloadUsage']);
        Route::post('/verifySubscription', [EmployerCvPackageController::class, 'verifySubscription']);
    });

    Route::get('/profile', [EmployerProfileController::class, 'show']);
    Route::post('/profile', [EmployerProfileController::class, 'update']);
    Route::post('/profile/delete-account', [EmployerProfileController::class, 'deleteAccount']);
    Route::post('/profile/change-password', [EmployerProfileController::class, 'changePassword']);
    Route::post('/profile/upload-logo', [EmployerProfileController::class, 'uploadLogo']);
    Route::get('/dashboard', [EmployerProfileController::class, 'dashboard']);
    Route::get('/users', [EmployerProfileController::class, 'listUsers']);
    Route::post('/users', [EmployerProfileController::class, 'createUser']);
    Route::get('/users/{userId}', [EmployerProfileController::class, 'showUser']);
    Route::post('/users/update', [EmployerProfileController::class, 'updateUser']);
    Route::post('/users/{userId}/delete', [EmployerProfileController::class, 'deleteUser']);
});

Route::get('/employers', [FrontPageController::class, 'listEmployers']);
Route::get('/employers/{employerId}', [FrontPageController::class, 'showEmployer']);
Route::get('/job-categories', [FrontPageController::class, 'listJobCategories']);
Route::get('/candidate-profile/{candidateId}', [FrontPageController::class, 'showCandidateProfile']);
Route::get('/front-page', [FrontPageController::class, 'index']);
Route::get('/search-jobs', [FrontPageController::class, 'searchJobs']);
Route::get('/latest-jobs', [FrontPageController::class, 'latestJobs']);
Route::get('/job/{jobId}', [FrontPageController::class, 'showJob']);

