<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\License\LicenseController;
use App\Http\Controllers\License\LicenseCategoryController;
use App\Http\Controllers\Fishery\FishermanController;
use App\Http\Controllers\Fishery\FishingBoatController;
use App\Http\Controllers\Market\MarketController;
use App\Http\Controllers\Market\MarketCageController;
use App\Http\Controllers\BusinessFrame\BusinessFrameController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\BusinessSettingController;
use App\Http\Controllers\Admin\MobileAppController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Report\ReportController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    /*
    |--------------------------------------------------------------------------
    | License Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('licenses')->name('licenses.')->group(function () {
        Route::get('/', [LicenseController::class, 'index'])->name('index');
        Route::get('/data', [LicenseController::class, 'getData'])->name('data');
        Route::get('/create', [LicenseController::class, 'create'])->name('create');
        Route::post('/', [LicenseController::class, 'store'])->name('store');
        Route::get('/{license}', [LicenseController::class, 'show'])->name('show');
        Route::get('/{license}/edit', [LicenseController::class, 'edit'])->name('edit');
        Route::put('/{license}', [LicenseController::class, 'update'])->name('update');
        Route::delete('/{license}', [LicenseController::class, 'destroy'])->name('destroy');
        Route::post('/{license}/send-reminder', [LicenseController::class, 'sendReminder'])->name('send-reminder');
        Route::post('/bulk-sms', [LicenseController::class, 'sendBulkSms'])->name('bulk-sms');
        Route::post('/{license}/hygiene-reminder', [LicenseController::class, 'sendHygieneReminder'])->name('hygiene-reminder');
    });

    // License Categories
    Route::prefix('license-categories')->name('license-categories.')->group(function () {
        Route::get('/', [LicenseCategoryController::class, 'index'])->name('index');
        Route::get('/data', [LicenseCategoryController::class, 'getData'])->name('data');
        Route::post('/', [LicenseCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [LicenseCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [LicenseCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [LicenseCategoryController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Fishery Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('fishermen')->name('fishermen.')->group(function () {
        Route::get('/', [FishermanController::class, 'index'])->name('index');
        Route::get('/data', [FishermanController::class, 'getData'])->name('data');
        Route::get('/create', [FishermanController::class, 'create'])->name('create');
        Route::post('/', [FishermanController::class, 'store'])->name('store');
        Route::get('/{fisherman}', [FishermanController::class, 'show'])->name('show');
        Route::get('/{fisherman}/edit', [FishermanController::class, 'edit'])->name('edit');
        Route::put('/{fisherman}', [FishermanController::class, 'update'])->name('update');
        Route::delete('/{fisherman}', [FishermanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('fishing-boats')->name('fishing-boats.')->group(function () {
        Route::get('/', [FishingBoatController::class, 'index'])->name('index');
        Route::get('/data', [FishingBoatController::class, 'getData'])->name('data');
        Route::get('/create', [FishingBoatController::class, 'create'])->name('create');
        Route::post('/', [FishingBoatController::class, 'store'])->name('store');
        Route::get('/{boat}/edit', [FishingBoatController::class, 'edit'])->name('edit');
        Route::put('/{boat}', [FishingBoatController::class, 'update'])->name('update');
        Route::delete('/{boat}', [FishingBoatController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Market Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('markets')->name('markets.')->group(function () {
        Route::get('/', [MarketController::class, 'index'])->name('index');
        Route::get('/data', [MarketController::class, 'getData'])->name('data');
        Route::get('/create', [MarketController::class, 'create'])->name('create');
        Route::post('/', [MarketController::class, 'store'])->name('store');
        Route::get('/{market}', [MarketController::class, 'show'])->name('show');
        Route::get('/{market}/edit', [MarketController::class, 'edit'])->name('edit');
        Route::put('/{market}', [MarketController::class, 'update'])->name('update');
        Route::delete('/{market}', [MarketController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('market-cages')->name('market-cages.')->group(function () {
        Route::get('/', [MarketCageController::class, 'index'])->name('index');
        Route::get('/data', [MarketCageController::class, 'getData'])->name('data');
        Route::get('/create', [MarketCageController::class, 'create'])->name('create');
        Route::post('/', [MarketCageController::class, 'store'])->name('store');
        Route::get('/{cage}/edit', [MarketCageController::class, 'edit'])->name('edit');
        Route::put('/{cage}', [MarketCageController::class, 'update'])->name('update');
        Route::delete('/{cage}', [MarketCageController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Business Frames/Vibanda Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('business-frames')->name('business-frames.')->group(function () {
        Route::get('/', [BusinessFrameController::class, 'index'])->name('index');
        Route::get('/data', [BusinessFrameController::class, 'getData'])->name('data');
        Route::get('/create', [BusinessFrameController::class, 'create'])->name('create');
        Route::post('/', [BusinessFrameController::class, 'store'])->name('store');
        Route::get('/{frame}', [BusinessFrameController::class, 'show'])->name('show');
        Route::get('/{frame}/edit', [BusinessFrameController::class, 'edit'])->name('edit');
        Route::put('/{frame}', [BusinessFrameController::class, 'update'])->name('update');
        Route::delete('/{frame}', [BusinessFrameController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | SMS Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('sms')->name('sms.')->group(function () {
        Route::get('/send', [SmsController::class, 'create'])->name('create');
        Route::post('/send', [SmsController::class, 'send'])->name('send');
        Route::get('/logs', [SmsController::class, 'logs'])->name('logs');
        Route::get('/logs-data', [SmsController::class, 'logsData'])->name('logs-data');
    });

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/licenses', [ReportController::class, 'licenses'])->name('licenses');
        Route::get('/licenses-data', [ReportController::class, 'licensesData'])->name('licenses-data');
        Route::get('/expired-licenses', [ReportController::class, 'expiredLicenses'])->name('expired-licenses');
        Route::get('/expired-licenses-data', [ReportController::class, 'expiredLicensesData'])->name('expired-licenses-data');
        Route::get('/fishery', [ReportController::class, 'fishery'])->name('fishery');
        Route::get('/fishery-data', [ReportController::class, 'fisheryData'])->name('fishery-data');
        Route::get('/markets', [ReportController::class, 'markets'])->name('markets');
        Route::get('/markets-data', [ReportController::class, 'marketsData'])->name('markets-data');
        Route::get('/frames', [ReportController::class, 'frames'])->name('frames');
        Route::get('/frames-data', [ReportController::class, 'framesData'])->name('frames-data');
        Route::get('/map-distribution', [ReportController::class, 'mapDistribution'])->name('map-distribution');
        Route::get('/map-distribution-data', [ReportController::class, 'mapDistributionData'])->name('map-distribution-data');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin / Settings Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->name('admin.')->group(function () {

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Roles
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/data', [RoleController::class, 'getData'])->name('roles.data');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/data', [PermissionController::class, 'getData'])->name('permissions.data');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        // Activity Logs
        Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
        Route::get('/logs/data', [ActivityLogController::class, 'getData'])->name('logs.data');

        // Locations
        Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
        Route::get('/locations/regions', [LocationController::class, 'regions'])->name('locations.regions');
        Route::get('/locations/districts', [LocationController::class, 'districts'])->name('locations.districts');
        Route::get('/locations/wards', [LocationController::class, 'wards'])->name('locations.wards');
        Route::get('/locations/villages', [LocationController::class, 'villages'])->name('locations.villages');

        // Business Settings
        Route::get('/business-settings', [BusinessSettingController::class, 'index'])->name('business-settings.index');
        Route::get('/business-settings/revenue-sources', [BusinessSettingController::class, 'revenueSources'])->name('business-settings.revenue-sources');
        Route::get('/business-settings/revenue-sources/data', [BusinessSettingController::class, 'revenueSourcesData'])->name('business-settings.revenue-sources.data');
        Route::post('/business-settings/revenue-sources', [BusinessSettingController::class, 'storeRevenueSource'])->name('business-settings.revenue-sources.store');
        Route::put('/business-settings/revenue-sources/{source}', [BusinessSettingController::class, 'updateRevenueSource'])->name('business-settings.revenue-sources.update');
        Route::delete('/business-settings/revenue-sources/{source}', [BusinessSettingController::class, 'destroyRevenueSource'])->name('business-settings.revenue-sources.destroy');

        // Mobile App
        Route::get('/mobile-app/advertisements', [MobileAppController::class, 'advertisements'])->name('mobile-app.advertisements');
        Route::get('/mobile-app/advertisements/data', [MobileAppController::class, 'advertisementsData'])->name('mobile-app.advertisements.data');
        Route::get('/mobile-app/advertisements/create', [MobileAppController::class, 'createAdvertisement'])->name('mobile-app.advertisements.create');
        Route::post('/mobile-app/advertisements', [MobileAppController::class, 'storeAdvertisement'])->name('mobile-app.advertisements.store');
        Route::get('/mobile-app/advertisements/{ad}/edit', [MobileAppController::class, 'editAdvertisement'])->name('mobile-app.advertisements.edit');
        Route::put('/mobile-app/advertisements/{ad}', [MobileAppController::class, 'updateAdvertisement'])->name('mobile-app.advertisements.update');
        Route::delete('/mobile-app/advertisements/{ad}', [MobileAppController::class, 'destroyAdvertisement'])->name('mobile-app.advertisements.destroy');
        Route::post('/mobile-app/advertisements/{ad}/approve', [MobileAppController::class, 'approveAdvertisement'])->name('mobile-app.advertisements.approve');

        Route::get('/mobile-app/opportunities', [MobileAppController::class, 'opportunities'])->name('mobile-app.opportunities');
        Route::get('/mobile-app/opportunities/data', [MobileAppController::class, 'opportunitiesData'])->name('mobile-app.opportunities.data');
        Route::get('/mobile-app/opportunities/create', [MobileAppController::class, 'createOpportunity'])->name('mobile-app.opportunities.create');
        Route::post('/mobile-app/opportunities', [MobileAppController::class, 'storeOpportunity'])->name('mobile-app.opportunities.store');
        Route::get('/mobile-app/opportunities/{opportunity}/edit', [MobileAppController::class, 'editOpportunity'])->name('mobile-app.opportunities.edit');
        Route::put('/mobile-app/opportunities/{opportunity}', [MobileAppController::class, 'updateOpportunity'])->name('mobile-app.opportunities.update');
        Route::delete('/mobile-app/opportunities/{opportunity}', [MobileAppController::class, 'destroyOpportunity'])->name('mobile-app.opportunities.destroy');
        Route::post('/mobile-app/opportunities/{opportunity}/toggle-featured', [MobileAppController::class, 'toggleFeaturedOpportunity'])->name('mobile-app.opportunities.toggle-featured');
    });

    // Profile
    Route::get('/profile', function () {
        return view('auth.profile');
    })->name('profile');

    // AJAX Routes for Location Cascading
    Route::get('/ajax/districts/{region}', [LocationController::class, 'getDistricts'])->name('ajax.districts');
    Route::get('/ajax/wards/{district}', [LocationController::class, 'getWards'])->name('ajax.wards');
    Route::get('/ajax/villages/{ward}', [LocationController::class, 'getVillages'])->name('ajax.villages');
});
