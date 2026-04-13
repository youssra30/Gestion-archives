<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    public function backup()
    {
        try {

            Artisan::call('backup:run');

            return response()->json([
                'message' => 'Backup exécuté avec succès'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }
    }
}