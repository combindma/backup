<?php

namespace Combindma\Backup\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Adapter\Local;

class BackupController extends Controller
{
    public function index()
    {
        if (! count(config('backup.backup.destination.disks'))) {
            dd('Aucun disque de sauvegarde n\'est configuré dans config/backup.php');
        }

        $backups = [];
        foreach (config('backup.backup.destination.disks') as $disk_name) {
            $disk = Storage::disk($disk_name);
            $adapter = $disk->getDriver()->getAdapter();
            $files = $disk->allFiles();

            // make an array of backup files, with their filesize and creation date
            foreach ($files as $k => $f) {
                // only take the zip files into account
                if (substr($f, -4) === '.zip' && $disk->exists($f)) {
                    $backups[] = [
                        'file_path' => $f,
                        'file_name' => str_replace('backups/', '', $f),
                        'file_size' => $disk->size($f),
                        'last_modified' => $disk->lastModified($f),
                        'disk' => $disk_name,
                        'download' => $adapter instanceof Local,
                    ];
                }
            }
        }

        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);

        return view('backup::index', compact('backups'));
    }

    public function create()
    {
        $message = 'Sauvegarde effectuée avec succès';

        try {
            ini_set('max_execution_time', 600);

            Log::info('Backpack\BackupManager -- Called backup:run from admin interface');

            Artisan::call('backup:run');

            $output = Artisan::output();

            if (strpos($output, 'Backup failed because')) {
                preg_match('/Backup failed because(.*?)$/ms', $output, $match);
                $message = "Backpack\BackupManager -- backup process failed because ";
                $message .= isset($match[1]) ? $match[1] : '';
                Log::error($message.PHP_EOL.$output);
            } else {
                Log::info("Backpack\BackupManager -- backup process has started");
            }
        } catch (Exception $e) {
            Log::error($e);

            return Response::make($e->getMessage(), 500);
        }

        flash($message);

        return redirect(route('backup::backups.index'));
    }

    public function download()
    {
        $disk = Storage::disk(request('disk'));
        $file_name = request('file_name');
        $adapter = $disk->getDriver()->getAdapter();

        if ($adapter instanceof Local) {
            $storage_path = $disk->getDriver()->getAdapter()->getPathPrefix();

            if ($disk->exists($file_name)) {
                return response()->download($storage_path.$file_name);
            }

            return abort(404, 'Le fichier de sauvegarde n\'existe pas.');
        }

        return abort(404, 'Seuls les téléchargments à partir du système de fichier local sont supportés.');
    }

    public function delete()
    {
        $disk = Storage::disk(request('disk'));
        $file_name = request('file_name');

        if ($disk->exists($file_name)) {
            $disk->delete($file_name);
            flash('Sauvegarde supprimée avec succès');

            return redirect(route('backup::backups.index'));
        }

        return abort(404, 'Le fichier de sauvegarde n\'existe pas.');
    }
}
