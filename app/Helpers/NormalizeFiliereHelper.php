<?php

namespace App\Helpers;

/**
 * Normalise les noms de filières vers les 5 filières officielles ENSA.
 *
 * Filières ingénieur (3 ans) :
 *   - GEER : Génie Électrique et Énergies Renouvelables
 *   - IAA  : Industries Agroalimentaires
 *   - IAC  : Intelligence Artificielle et Cybersécurité
 *   - TDI  : Transformation Digitale Industrielle
 *
 * Cycle préparatoire :
 *   - CP   : Classe Préparatoire
 */
class NormalizeFiliereHelper
{
    public const FILIERES_OFFICIELLES = ['GEER', 'IAA', 'IAC', 'TDI', 'CP'];

    public static function normalize(?string $raw): string
    {
        if (empty(trim($raw ?? ''))) {
            return 'Non spécifié';
        }

        $f = mb_strtoupper(trim($raw));
        $f = preg_replace('/\s+/', ' ', $f);

        // Remplacer les caractères accentués
        $f = str_replace(
            ['É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Ä', 'Ô', 'Ö', 'Î', 'Ï', 'Ù', 'Û', 'Ü', 'Ç'],
            ['E', 'E', 'E', 'E', 'A', 'A', 'A', 'O', 'O', 'I', 'I', 'U', 'U', 'U', 'C'],
            $f
        );

        // ── GEER : Génie Électrique et Énergies Renouvelables
        if (
            (str_contains($f, 'ELECTRIQUE') && str_contains($f, 'RENOUVELABLE')) ||
            (str_contains($f, 'ENERGIE') && str_contains($f, 'RENOUVELABLE')) ||
            in_array($f, ['GEER', 'EREE', 'GE', 'GENIE ELECTRIQUE']) ||
            str_contains($f, 'GEER') || str_contains($f, 'EREE')
        ) {
            return 'GEER';
        }

        // ── IAA : Industries Agroalimentaires
        if (
            str_contains($f, 'AGROALIMENTAIRE') || str_contains($f, 'AGRO') ||
            in_array($f, ['IAA']) || str_contains($f, 'IAA')
        ) {
            return 'IAA';
        }

        // ── IAC : Intelligence Artificielle et Cybersécurité
        if (
            str_contains($f, 'INTELLIGENCE ARTIFICIELLE') ||
            str_contains($f, 'CYBERSECURITE') ||
            str_contains($f, 'INTELIGENCE ARTIFICIELLE') ||
            in_array($f, ['IAC', 'IACS', 'IAEC', 'IA', 'API', 'SCAI', 'APCI']) ||
            str_contains($f, 'IAC') || str_contains($f, 'IACS')
        ) {
            return 'IAC';
        }

        // ── TDI : Transformation Digitale Industrielle
        if (
            str_contains($f, 'TRANSFORMATION DIGITALE') ||
            str_contains($f, 'DIGITALE INDUSTRIELLE') ||
            in_array($f, ['TDI', 'PCI']) ||
            str_contains($f, 'TDI')
        ) {
            return 'TDI';
        }

        // ── CP : Classe Préparatoire
        if (
            str_contains($f, 'PREPARATOIRE') ||
            str_contains($f, 'PREPA') ||
            in_array($f, ['CP', 'CP1', 'CP2', 'CP 1', 'CP 2', 'MPSI', 'MP', 'PC', 'SP', '1APACI', '2APACI']) ||
            str_contains($f, 'CYCLE PREPARATOIRE') ||
            preg_match('/^CP\d?$/', $f) ||
            preg_match('/^\d?A?PACI$/', $f)
        ) {
            return 'CP';
        }

        return 'Non spécifié';
    }

    /**
     * Vérifie si une filière (brute) correspond à une filière officielle.
     */
    public static function isOfficielle(?string $raw): bool
    {
        $normalized = self::normalize($raw);
        return in_array($normalized, self::FILIERES_OFFICIELLES);
    }
}
