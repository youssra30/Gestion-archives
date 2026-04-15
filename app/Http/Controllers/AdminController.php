<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    private function checkAdmin($user)
    {
        return in_array($user->role, ['SUPER_ADMIN', 'ADMIN_SYSTEME']);
    }

    /**
     * 🔵 BACKUP DATABASE
     */
    public function backup(Request $request)
    {
        if (!$this->checkAdmin($request->user())) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $directory = storage_path("app/backups");
        $path = $directory . "/" . $filename;

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // IMPORTANT: full path to mysqldump
        $mysqldump = "C:\\xampp\\mysql\\bin\\mysqldump.exe";

        $command = sprintf(
            '"%s" --user=%s --password="%s" --host=%s --port=%s %s',
            $mysqldump,
            env('DB_USERNAME'),
            env('DB_PASSWORD') ?: '',
            env('DB_HOST'),
            env('DB_PORT'),
            env('DB_DATABASE')
        );

        exec($command, $output, $result);

        $sql = implode(PHP_EOL, $output);

        file_put_contents($path, $sql);

        if ($result !== 0 || empty($sql)) {
            return response()->json([
                'message' => 'Backup failed',
                'result' => $result,
                'debug' => $output
            ], 500);
        }

        return response()->json([
            'message' => 'Backup exécuté avec succès',
            'file' => $filename,
            'path' => $path
        ]);
    }

    /**
     * 🟢 RESTORE DATABASE
     */
    public function restore(Request $request)
    {
        if (!$this->checkAdmin($request->user())) {
            return response()->json(['message' => 'Accès interdit'], 403);
        }

        $request->validate([
            'file' => 'required|file'
        ]);

        $filePath = $request->file('file')->getRealPath();

        $mysql = "C:\\xampp\\mysql\\bin\\mysql.exe";

        $command = sprintf(
            '"%s" --user=%s --password="%s" --host=%s --port=%s %s < "%s"',
            $mysql,
            env('DB_USERNAME'),
            env('DB_PASSWORD') ?: '',
            env('DB_HOST'),
            env('DB_PORT'),
            env('DB_DATABASE'),
            $filePath
        );

        exec($command, $output, $result);

        if ($result !== 0) {
            return response()->json([
                'message' => 'Restore failed',
                'result' => $result,
                'debug' => $output
            ], 500);
        }

        return response()->json([
            'message' => 'Base restaurée avec succès'
        ]);
    }
}