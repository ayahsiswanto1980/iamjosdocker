<?php

use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'installed'])->group(function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [InstallController::class, 'permissions'])->name('permissions');
    
    Route::get('/environment', [InstallController::class, 'environment'])->name('environment');
    Route::post('/environment/save', [InstallController::class, 'saveEnvironment'])->name('environment.save');
    
    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/database/test', [InstallController::class, 'testDatabase'])->name('database.test');
    
    Route::get('/migration', [InstallController::class, 'migration'])->name('migration');
    Route::post('/migration/run', [InstallController::class, 'runMigration'])->name('migration.run');
    
    Route::get('/admin', [InstallController::class, 'admin'])->name('admin');
    Route::post('/admin/save', [InstallController::class, 'saveAdmin'])->name('admin.save');
    
    Route::get('/complete', [InstallController::class, 'complete'])->name('complete');
});
