<?php

namespace App\Imports;

use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UtilisateursImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        if (empty($row['email'])) {
            return null;
        }

        return new Utilisateur([
            'username'  => $row['username'] ?? $row['nom_utilisateur'] ?? strtolower($row['prenom'] . '.' . $row['nom']),
            'nom'       => $row['nom'],
            'prenom'    => $row['prenom'],
            'email'     => $row['email'],
            'telephone' => $row['telephone'] ?? null,
            'password'  => Hash::make($row['password'] ?? 'password123'),
            'role'      => $row['role'] ?? 'AGENT_ACCUEIL',
        ]);
    }

    public function rules(): array
    {
        return [
            'nom'    => 'required|string',
            'prenom' => 'required|string',
            'email'  => 'required|email',
        ];
    }
}
