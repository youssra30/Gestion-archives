<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        Utilisateur::updateOrCreate(
            ['email' => 'youssraouzahra@gmail.com'],
            [
                'nom' => 'youssra',
                'prenom' => 'ouzahra',
                'username' => 'youssraouzahra',
                'password' => Hash::make('ensa@#234'),
                'telephone' => '0678433658',
                'role' => 'SUPER_ADMIN',
                'derniereConnexion' => now(),
            ]
        );
    }
}