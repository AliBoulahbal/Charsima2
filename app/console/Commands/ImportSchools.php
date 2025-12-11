<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportSchools extends Command
{
    protected $signature = 'import:schools {file}';
    protected $description = 'Importer les écoles depuis un fichier Excel';

    public function handle()
    {
        $filePath = $this->argument('file');
        
        if (!file_exists($filePath)) {
            $this->error("Fichier non trouvé : $filePath");
            return 1;
        }

        $this->info("Importation des écoles depuis : $filePath");
        
        $spreadsheet = IOFactory::load($filePath);
        
        $totalSchools = 0;
        
        // Parcourir toutes les feuilles (wilayas)
        foreach ($spreadsheet->getSheetNames() as $sheetName) {
            $wilaya = $sheetName;
            $this->info("\nImportation de la wilaya : $wilaya");
            
            $worksheet = $spreadsheet->getSheetByName($sheetName);
            $rows = $worksheet->toArray();
            
            $schoolsInSheet = 0;
            $currentMunicipality = '';
            
            // Parcourir les lignes (en sautant les en-têtes)
            foreach ($rows as $index => $row) {
                // Ignorer les premières lignes (titres et en-têtes)
                if ($index < 5 || empty($row[2]) || $row[2] === 'البلدية') {
                    continue;
                }
                
                // Vérifier si c'est une nouvelle commune
                if (!empty($row[2]) && $row[2] !== '/' && $row[2] !== '') {
                    $currentMunicipality = $row[2];
                }
                
                // Si pas de nom d'école, passer à la suivante
                if (empty($row[3]) || $row[3] === '/' || $row[3] === 'المدرسة') {
                    continue;
                }
                
                // Préparer les données
                $schoolData = [
                    'name' => $row[3], // Nom de l'école
                    'district' => $currentMunicipality, // Commune
                    'phone' => $this->cleanPhone($row[4] ?? '/'), // Téléphone
                    'manager_name' => $this->cleanManagerName($row[6] ?? '/'), // Nom du directeur
                    'student_count' => $this->parseStudentCount($row[7] ?? '/'), // Nombre d'élèves
                    'wilaya' => $wilaya, // Wilaya
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Insérer dans la base de données
                try {
                    School::create($schoolData);
                    $schoolsInSheet++;
                    $totalSchools++;
                    
                    if ($schoolsInSheet % 10 === 0) {
                        $this->info("$schoolsInSheet écoles importées pour $wilaya");
                    }
                } catch (\Exception $e) {
                    $this->error("Erreur avec l'école : " . $row[3]);
                    $this->error($e->getMessage());
                }
            }
            
            $this->info("$schoolsInSheet écoles importées pour $wilaya");
        }
        
        $this->info("\nImportation terminée !");
        $this->info("Total des écoles importées : $totalSchools");
        
        return 0;
    }
    
    /**
     * Nettoyer le numéro de téléphone
     */
    private function cleanPhone($phone)
    {
        if ($phone === '/' || empty($phone)) {
            return null;
        }
        
        // Convertir en string si c'est un float
        if (is_float($phone)) {
            $phone = (string) $phone;
        }
        
        // Retirer les points décimaux
        $phone = str_replace('.0', '', $phone);
        
        // Si c'est un nombre trop court, ajouter le préfixe
        if (strlen($phone) === 9) {
            $phone = '0' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Nettoyer le nom du directeur
     */
    private function cleanManagerName($name)
    {
        if ($name === '/' || empty($name)) {
            return 'غير محدد';
        }
        
        return trim($name);
    }
    
    /**
     * Parser le nombre d'élèves
     */
    private function parseStudentCount($count)
    {
        if ($count === '/' || empty($count) || !is_numeric($count)) {
            return 0;
        }
        
        return (int) $count;
    }
}