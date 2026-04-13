<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Etudiant;

class StatistiqueController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_utilisateurs' => Utilisateur::count(),
            'total_etudiants' => Etudiant::count(),
        ]);
    }
}
