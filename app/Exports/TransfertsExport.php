<?php

namespace App\Exports;

use App\Models\TransfertExterne;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransfertsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return TransfertExterne::with(['dossier.etudiant'])->get();
    }
    
    public function headings(): array
    {
        return [
            'ID', 'Numéro Dossier', 'Étudiant', 'École Origine',
            'École Destination', 'Date Demande', 'Date Validation',
            'Statut', 'Documents transmis', 'Référence Courrier', 'Date création'
        ];
    }
    
    public function map($transfert): array
    {
        return [
            $transfert->id,
            $transfert->dossier->numeroDossier ?? '',
            $transfert->dossier->etudiant->nom . ' ' . $transfert->dossier->etudiant->prenom ?? '',
            $transfert->ecoleOrigine,
            $transfert->ecoleDestination,
            $transfert->dateDemande,
            $transfert->dateValidation,
            $transfert->statut,
            is_array($transfert->documentsTransmis) ? implode(', ', $transfert->documentsTransmis) : '',
            $transfert->referenceCourrier,
            $transfert->created_at,
        ];
    }
}