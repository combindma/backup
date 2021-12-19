<?php

namespace Combindma\Backup;

use Combindma\Backup\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BackupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('backup')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageRegistered()
    {
        Route::macro('backup', function (string $baseUrl = 'admin') {
            Route::group(['prefix' => $baseUrl, 'as' => 'backup::'], function () {
                Route::get('/backups/', [BackupController::class, 'index'])->name('backups.index');
                Route::get('/backups/create', [BackupController::class,'create'])->name('backups.create');
                Route::get('/backups/download/{file_name?}', [BackupController::class, 'download'])->name('backups.download');
                Route::delete('/backups/delete/{file_name?}', [BackupController::class,'delete'])->name('backups.delete')->where('file_name', '(.*)');
            });
        });
    }
}
