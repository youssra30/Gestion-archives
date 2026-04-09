<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
public function run(): void
{
    DB::table('utilisateurs')->updateOrInsert(
        ['email' => 'youssraouzahra@gmail.com'], 
        [
            'nom' => 'youssra',
            'prenom' => 'ouzahra',
            'username' => 'youssraouzahra',
            'password' => Hash::make('ensa@#234'),
            'telephone' => '0678433658',
            'role' => 'ADMIN_SYSTEME',
            'derniereConnexion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]
    );
}
}