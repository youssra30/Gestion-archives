<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MassDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Insertion massive de données de test...');

        // ── 1. UTILISATEURS (10) ──────────────────────────────────
        $this->command->info('→ Création des utilisateurs...');

        $users = [
            ['nom'=>'Bennani','prenom'=>'Karim','username'=>'karim.bennani','email'=>'karim.bennani@ensa.ma','role'=>'ADMIN_SYSTEME','telephone'=>'0661234567'],
            ['nom'=>'El Amrani','prenom'=>'Fatima','username'=>'fatima.elamrani','email'=>'fatima.elamrani@ensa.ma','role'=>'RESPONSABLE_ARCHIVES','telephone'=>'0672345678'],
            ['nom'=>'Tahiri','prenom'=>'Youssef','username'=>'youssef.tahiri','email'=>'youssef.tahiri@ensa.ma','role'=>'AGENT_ACCUEIL','telephone'=>'0683456789'],
            ['nom'=>'Chraibi','prenom'=>'Nadia','username'=>'nadia.chraibi','email'=>'nadia.chraibi@ensa.ma','role'=>'AGENT_ACCUEIL','telephone'=>'0694567890'],
            ['nom'=>'Fassi','prenom'=>'Omar','username'=>'omar.fassi','email'=>'omar.fassi@ensa.ma','role'=>'RESPONSABLE_ARCHIVES','telephone'=>'0655678901'],
            ['nom'=>'Berrada','prenom'=>'Amina','username'=>'amina.berrada','email'=>'amina.berrada@ensa.ma','role'=>'CONSULTANT','telephone'=>'0666789012'],
            ['nom'=>'Kettani','prenom'=>'Hassan','username'=>'hassan.kettani','email'=>'hassan.kettani@ensa.ma','role'=>'AGENT_ACCUEIL','telephone'=>'0677890123'],
            ['nom'=>'Alaoui','prenom'=>'Salma','username'=>'salma.alaoui','email'=>'salma.alaoui@ensa.ma','role'=>'ADMIN_SYSTEME','telephone'=>'0688901234'],
            ['nom'=>'Ziani','prenom'=>'Mehdi','username'=>'mehdi.ziani','email'=>'mehdi.ziani@ensa.ma','role'=>'CONSULTANT','telephone'=>'0699012345'],
            ['nom'=>'Hajji','prenom'=>'Layla','username'=>'layla.hajji','email'=>'layla.hajji@ensa.ma','role'=>'AGENT_ACCUEIL','telephone'=>'0610123456'],
        ];

        $userIds = [];
        foreach ($users as $u) {
            $userIds[] = DB::table('utilisateurs')->insertGetId(array_merge($u, [
                'password' => Hash::make('password123'),
                'derniereConnexion' => Carbon::now()->subDays(rand(0, 30)),
                'created_at' => now(), 'updated_at' => now(),
            ]));
        }

        // ── 2. ÉTUDIANTS (50) ─────────────────────────────────────
        $this->command->info('→ Création de 50 étudiants...');

        $noms = ['Amrani','Benjelloun','Cherkaoui','Daoudi','El Fassi','Fikri','Ghali','Hmidouch','Idrissi','Jamal',
                 'Kabbaj','Lahlou','Mansouri','Naciri','Ouazzani','Rachidi','Saidi','Tazi','Wahbi','Yamani',
                 'Belhaj','Khouya','Mernissi','Bouazza','Zerhouni'];
        $prenoms_m = ['Mohamed','Youssef','Adam','Amine','Hamza','Ayoub','Ilyas','Othman','Reda','Zakaria',
                      'Saad','Walid','Rayan','Bilal','Adil','Rachid','Tarik','Nabil','Kamal','Driss'];
        $prenoms_f = ['Fatima','Khadija','Salma','Meryem','Amina','Sara','Hajar','Zineb','Nora','Imane',
                      'Houda','Layla','Soukaina','Chaima','Asmaa','Hiba','Dounia','Rania','Yasmine','Siham'];
        $villes = ['Casablanca','Rabat','Fès','Marrakech','Tanger','Agadir','Meknès','Oujda','Kénitra','Tétouan',
                   'El Jadida','Safi','Beni Mellal','Nador','Khénifra','Taza','Settat','Berrechid','Mohammedia','Laâyoune'];
        $filieres = ['Génie Informatique','Génie Civil','Génie Électrique','Génie Mécanique','Génie Industriel',
                     'Génie des Procédés','Génie Réseaux','Génie Énergétique'];
        $lycees = ['Lycée Hassan II','Lycée Moulay Youssef','Lycée Ibn Khaldoun','Lycée Al Khawarizmi',
                   'Lycée Abdelkrim El Khattabi','Lycée Omar Ibn Abdelaziz','Lycée Reda Slaoui','Lycée Allal Ben Abdellah'];
        $ecoles = ['ENSA Khouribga','ENSA Fès','ENSA Marrakech','ENSA Tanger','ENSA Oujda','ENSA Kénitra','ENSA Agadir','ENSA Berrechid'];

        $etudiantIds = [];
        for ($i = 0; $i < 50; $i++) {
            $sexe = rand(0, 1) ? 'MASCULIN' : 'FEMININ';
            $prenom = $sexe === 'MASCULIN' ? $prenoms_m[array_rand($prenoms_m)] : $prenoms_f[array_rand($prenoms_f)];
            $nom = $noms[array_rand($noms)];
            $ville = $villes[array_rand($villes)];
            $annee = rand(2019, 2025);
            $idx = str_pad($i + 10, 6, '0', STR_PAD_LEFT);

            $etudiantIds[] = DB::table('etudiants')->insertGetId([
                'utilisateur_id' => null,
                'cne' => 'R' . rand(100000000, 999999999),
                'cin' => chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100000, 999999),
                'nom' => $nom,
                'prenom' => $prenom,
                'dateNaissance' => Carbon::create(rand(1998, 2004), rand(1, 12), rand(1, 28)),
                'lieuNaissance' => $ville,
                'nationalite' => 'Marocaine',
                'sexe' => $sexe,
                'adresse' => rand(1, 200) . ' Rue ' . ['Mohammed V','Hassan II','Al Massira','La Liberté','Ibn Sina','Allal Ben Abdellah'][rand(0,5)] . ', ' . $ville,
                'telephone' => '06' . rand(10000000, 99999999),
                'email' => strtolower($prenom) . '.' . strtolower(str_replace(' ', '', $nom)) . $i . '@etu.ensa.ma',
                'nomPere' => $prenoms_m[array_rand($prenoms_m)] . ' ' . $nom,
                'nomMere' => $prenoms_f[array_rand($prenoms_f)] . ' ' . $noms[array_rand($noms)],
                'adresseParents' => $ville,
                'filiere' => $filieres[array_rand($filieres)],
                'anneeInscription' => $annee,
                'etablissementOrigine' => $lycees[array_rand($lycees)],
                'etablissementAccueil' => $ecoles[array_rand($ecoles)],
                'photoUrl' => null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ── 3. BAC INFOS (50) ─────────────────────────────────────
        $this->command->info('→ Création des infos bac...');

        $series = ['Sciences Mathématiques A','Sciences Mathématiques B','Sciences Physiques','Sciences de la Vie et de la Terre','Sciences Économiques','Techniques Mathématiques'];
        $mentions = ['TRES_BIEN','BIEN','ASSEZ_BIEN','PASSABLE'];
        $academies = ['Casablanca-Settat','Rabat-Salé-Kénitra','Fès-Meknès','Marrakech-Safi','Tanger-Tétouan-Al Hoceima','Oriental','Souss-Massa','Béni Mellal-Khénifra'];

        foreach ($etudiantIds as $eid) {
            DB::table('bac_infos')->insert([
                'etudiant_id' => $eid,
                'serie' => $series[array_rand($series)],
                'mention' => $mentions[array_rand($mentions)],
                'anneeObtention' => rand(2017, 2024),
                'lycee' => $lycees[array_rand($lycees)],
                'academie' => $academies[array_rand($academies)],
                'copieScaneeUrl' => null,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ── 4. DOSSIERS ARCHIVES (50) ─────────────────────────────
        $this->command->info('→ Création de 50 dossiers d\'archives...');

        $typeCas = ['ADMISSION','AUTRE_VILLE','ABANDON_CYCLE','TRANSFERT_SORTANT','TRANSFERT_ENTRANT','LAUREAT','ABANDON_PREPA','DEMI_PENSION','PENSION_COMPLETE'];
        $statuts = ['EN_COURS','COMPLET','INCOMPLET','ARCHIVE','TRANSFERE','RETIRE','DETRUIT'];
        $localisations = ['Salle A - Étagère 1','Salle A - Étagère 2','Salle A - Étagère 3','Salle B - Étagère 1','Salle B - Étagère 2',
                          'Salle C - Armoire 1','Salle C - Armoire 2','Archive Principale - Rayon 1','Archive Principale - Rayon 2','Archive Secondaire'];

        $dossierIds = [];
        foreach ($etudiantIds as $idx => $eid) {
            $dossierIds[] = DB::table('dossier_archives')->insertGetId([
                'numeroDossier' => 'DOS-' . date('Y') . '-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                'etudiant_id' => $eid,
                'typeCas' => $typeCas[array_rand($typeCas)],
                'statut' => $statuts[array_rand($statuts)],
                'dateArchivage' => Carbon::now()->subDays(rand(1, 365)),
                'localisation' => $localisations[array_rand($localisations)],
                'observations' => ['Dossier complet','Documents en attente de vérification','Manque attestation','Transfert en cours','RAS','Dossier vérifié par le responsable','En attente de signature',null][rand(0,7)],
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ── 5. DOCUMENTS (120) ────────────────────────────────────
        $this->command->info('→ Création de ~120 documents...');

        $typesDocs = ['CIN_RECTO','CIN_VERSO','BAC_ORIGINAL','BAC_COPIE','DIPLOME_ORIGINAL','RELEVE_NOTES','ATTESTATION_SCOLARITE','CERTIFICAT_RESIDENCE','PHOTO_IDENTITE','FICHE_INSCRIPTION','AUTRE'];
        $formats = ['pdf','jpg','png','docx'];

        foreach ($dossierIds as $did) {
            $nbDocs = rand(1, 4);
            $usedTypes = [];
            for ($d = 0; $d < $nbDocs; $d++) {
                $type = $typesDocs[array_rand($typesDocs)];
                if (in_array($type, $usedTypes)) continue;
                $usedTypes[] = $type;
                $fmt = $formats[array_rand($formats)];
                DB::table('documents')->insert([
                    'dossier_id' => $did,
                    'type_document' => $type,
                    'nomFichier' => strtolower(str_replace(' ', '_', $type)) . '_' . $did . '.' . $fmt,
                    'cheminStockage' => '/storage/documents/' . $did . '/' . strtolower($type) . '.' . $fmt,
                    'ajoute_par' => $userIds[array_rand($userIds)],
                    'taille' => rand(50000, 5000000),
                    'format' => $fmt,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }

        // ── 6. MOUVEMENTS (40) ────────────────────────────────────
        $this->command->info('→ Création de 40 mouvements...');

        $typesMvt = ['DEPOT_INITIAL','RETRAIT_TEMP','RETOUR','TRANSFERT_DEF','CONSULTATION','RESTITUTION'];
        $statutsMvt = ['EN_COURS','TERMINE','EN_RETARD','ANNULE'];
        $motifs = ['Inscription initiale','Demande de l\'étudiant','Vérification administrative','Transfert vers autre établissement',
                   'Consultation par le responsable','Retrait temporaire pour mise à jour','Restitution après consultation','Contrôle annuel des archives'];

        for ($i = 0; $i < 40; $i++) {
            $dateMvt = Carbon::now()->subDays(rand(1, 300));
            $statut = $statutsMvt[array_rand($statutsMvt)];
            DB::table('mouvements')->insert([
                'dossier_id' => $dossierIds[array_rand($dossierIds)],
                'type_mouvement' => $typesMvt[array_rand($typesMvt)],
                'dateMouvement' => $dateMvt,
                'motif' => $motifs[array_rand($motifs)],
                'provenance' => $localisations[array_rand($localisations)],
                'destination' => $localisations[array_rand($localisations)],
                'effectue_par' => $userIds[array_rand($userIds)],
                'documentRetire' => rand(0, 1),
                'documentsRetires' => json_encode(rand(0,1) ? [$typesDocs[array_rand($typesDocs)]] : null),
                'dateRetourPrevu' => $statut !== 'TERMINE' ? $dateMvt->copy()->addDays(rand(7, 30)) : null,
                'dateRetourEffectif' => $statut === 'TERMINE' ? $dateMvt->copy()->addDays(rand(3, 20)) : null,
                'statut' => $statut,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ── 7. RÉCLAMATIONS (30) ──────────────────────────────────
        $this->command->info('→ Création de 30 réclamations...');

        $typesDemande = ['COPIE_DOCUMENT','DUPLICATA_DIPLOME','ATTESTATION_REUSSITE','CERTIFICAT_SCOLARITE_ANCIENNE','COPIE_CIN','COPIE_BAC','DOSSIER_COMPLET'];
        $statutsRec = ['EN_ATTENTE','EN_COURS','TRAITEE','REJETEE','ANNULEE'];
        $demandeurs = [];
        foreach ($etudiantIds as $eid) {
            $et = DB::table('etudiants')->find($eid);
            $demandeurs[] = $et->prenom . ' ' . $et->nom;
        }

        for ($i = 0; $i < 30; $i++) {
            $statut = $statutsRec[array_rand($statutsRec)];
            $dateD = Carbon::now()->subDays(rand(1, 200));
            DB::table('reclamations')->insert([
                'dossier_id' => $dossierIds[array_rand($dossierIds)],
                'demandeur' => $demandeurs[array_rand($demandeurs)],
                'typeDemande' => $typesDemande[array_rand($typesDemande)],
                'dateDemande' => $dateD,
                'dateTraitement' => in_array($statut, ['TRAITEE','REJETEE']) ? $dateD->copy()->addDays(rand(1, 15)) : null,
                'statut' => $statut,
                'documentsDemandes' => json_encode([$typesDocs[array_rand($typesDocs)], $typesDocs[array_rand($typesDocs)]]),
                'motif' => ['Besoin pour inscription','Demande personnelle','Exigé par employeur','Procédure administrative','Poursuite d\'études à l\'étranger','Concours professionnel'][rand(0,5)],
                'traite_par' => in_array($statut, ['TRAITEE','REJETEE']) ? $userIds[array_rand($userIds)] : null,
                'reponse' => $statut === 'TRAITEE' ? 'Document préparé et remis au demandeur' : ($statut === 'REJETEE' ? 'Dossier incomplet, veuillez compléter' : null),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        // ── 8. TRANSFERTS EXTERNES (15) ───────────────────────────
        $this->command->info('→ Création de 15 transferts externes...');

        $statutsTr = ['DEMANDE_ENVOI','DEMANDE_RECU','VALIDE','REFUSE','EN_COURS','TERMINE'];

        for ($i = 0; $i < 15; $i++) {
            $statut = $statutsTr[array_rand($statutsTr)];
            $dateD = Carbon::now()->subDays(rand(1, 250));
            DB::table('transfert_externes')->insert([
                'dossier_id' => $dossierIds[array_rand($dossierIds)],
                'ecoleOrigine' => $ecoles[array_rand($ecoles)],
                'ecoleDestination' => $ecoles[array_rand($ecoles)],
                'dateDemande' => $dateD,
                'dateValidation' => in_array($statut, ['VALIDE','TERMINE']) ? $dateD->copy()->addDays(rand(5, 30)) : null,
                'statut' => $statut,
                'documentsTransmis' => json_encode(array_slice($typesDocs, 0, rand(2, 5))),
                'referenceCourrier' => 'REF-' . date('Y') . '-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        $this->command->info('');
        $this->command->info('✅ Données insérées avec succès !');
        $this->command->info('   • 10 utilisateurs (mot de passe: password123)');
        $this->command->info('   • 50 étudiants avec infos bac');
        $this->command->info('   • 50 dossiers d\'archives');
        $this->command->info('   • ~120 documents');
        $this->command->info('   • 40 mouvements');
        $this->command->info('   • 30 réclamations');
        $this->command->info('   • 15 transferts externes');
    }
}
