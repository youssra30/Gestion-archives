<?php

namespace App\Exports;

use App\Models\Utilisateur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UtilisateursExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Utilisateur::all();
    }
    
    public function headings(): array
    {
        return [
            'ID', 'Nom', 'Prénom', 'Nom d\'utilisateur', 'Email', 
            'Téléphone', 'Rôle', 'Dernière connexion', 'Date création'
        ];
    }
    
    public function map($user): array
    {
        return [
            $user->id,
            $user->nom,
            $user->prenom,
            $user->username,
            $user->email,
            $user->telephone,
            $user->role,
            $user->derniereConnexion,
            $user->created_at,
        ];
    }
}