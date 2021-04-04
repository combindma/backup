<?php

namespace Combindma\Backup;

use Combindma\Backup\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;

class Backup
{
    public static function routes(string $prefix = 'dash')
    {
        Route::group(['prefix' => $prefix, 'as' => 'backup::'], function () {
            Route::get('/backups/', [BackupController::class, 'index'])->name('backups.index');
            Route::get('/backups/create', [BackupController::class,'create'])->name('backups.create');
            Route::get('/backups/download/{file_name?}', [BackupController::class, 'download'])->name('backups.download');
            Route::delete('/backups/delete/{file_name?}', [BackupController::class,'delete'])->name('backups.delete')->where('file_name', '(.*)');
        });
    }
}
