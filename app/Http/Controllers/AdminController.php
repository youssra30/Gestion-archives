<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller

{
    // 🔐 Check role helper
    private function checkAdmin($user)
    {
        return in_array($user->role, ['SUPER_ADMIN', 'ADMIN_SYSTEME']);
    }

    // 💾 BACKUP
    public function backup(Request $request)
    {
        if (!$this->checkAdmin($request->user())) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path("app/$filename");

        $command = "mysqldump --user=root --password= --host=127.0.0.1 nom_base > $path";

        system($command);

        return response()->json([
            'message' => 'Backup créé avec succès',
            'file' => $filename
        ]);
    }

    // 🔄 RESTORE
    public function restore(Request $request)
    {
        if (!$this->checkAdmin($request->user())) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $request->validate([
            'file' => 'required|file'
        ]);

        $path = $request->file('file')->store('backups');
        $fullPath = storage_path('app/' . $path);

        $command = "mysql --user=root --password= nom_base < $fullPath";

        system($command);

        return response()->json([
            'message' => 'Base restaurée avec succès'
        ]);
    }
}

