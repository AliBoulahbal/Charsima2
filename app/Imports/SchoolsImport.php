<?php

namespace App\Imports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SchoolsImport implements ToModel, WithStartRow, WithCustomCsvSettings, SkipsEmptyRows
{
    private $wilaya;
    private $lastCommune = null; 

    public function __construct(string $wilaya)
    {
        $this->wilaya = $wilaya;
    }

    public function model(array $row)
    {
        // DEBUG: Voir la structure des données
        // \Log::info('Row index: ' . implode(' | ', array_map(function($v) {
        //     return '"' . $v . '"';
        // }, $row)));
        
        // Les colonnes dans le CSV (séparateur ;)
        // Index 0: "عدد المدارس" (ignorer)
        // Index 1: "الرقم" (ignorer)
        // Index 2: "البلدية"
        // Index 3: "المدرسة"
        // Index 4: "رقم الهاتف"
        // Index 5: "الحي"
        // Index 6: "المدير"
        // Index 7: "عدد التلاميذ"
        
        // Vérifier si c'est une ligne d'en-tête ou vide
        if (count($row) < 4 || 
            $row[0] === 'عدد المدارس' || 
            $row[2] === 'البلدية' || 
            empty($row[2]) && empty($row[3])) {
            return null;
        }
        
        $currentCommune = isset($row[2]) ? $this->cleanValue($row[2]) : '';
        $schoolName = isset($row[3]) ? $this->cleanValue($row[3]) : '';
        
        // Propagation de la commune
        if (!empty($currentCommune)) {
            $this->lastCommune = $currentCommune;
        }
        
        $communeToUse = $this->lastCommune;
        
        // Validation
        if (empty($schoolName) || empty($communeToUse)) {
            return null;
        }
        
        // Récupération des autres données
        $phone = isset($row[4]) ? $this->cleanValue($row[4]) : '';
        $district = isset($row[5]) ? $this->cleanValue($row[5]) : '';
        $managerName = isset($row[6]) ? $this->cleanValue($row[6]) : '';
        
        // Gestion du nombre d'élèves
        $studentCount = 0;
        if (isset($row[7])) {
            $studentValue = $this->cleanValue($row[7]);
            if ($studentValue !== '/' && !empty($studentValue) && is_numeric($studentValue)) {
                $studentCount = intval($studentValue);
            }
        }
        
        // Nettoyage du téléphone
        $phone = preg_replace('/[^0-9+\/]/', '', $phone);
        
        // Vérifier si l'école existe déjà
        $existingSchool = School::where('name', $schoolName)
            ->where('wilaya', $this->wilaya)
            ->first();
            
        if ($existingSchool) {
            // Option: Mettre à jour ou ignorer
            // Pour l'instant, on ignore les doublons
            \Log::info("École déjà existante ignorée: $schoolName - $communeToUse");
            return null;
        }

        return new School([
            'name'           => $schoolName,
            'wilaya'         => $this->wilaya, 
            'commune'        => $communeToUse,
            'district'       => $district,
            'address'        => $district, 
            'phone'          => $phone,
            'manager_name'   => $managerName,
            'student_count'  => $studentCount,
            'is_active'      => true, 
        ]);
    }
    
    /**
     * Nettoie une valeur (enlève BOM, espaces, etc.)
     */
    private function cleanValue($value)
    {
        if ($value === null) {
            return '';
        }
        
        // Enlever le BOM UTF-8 si présent
        $value = preg_replace('/^\xEF\xBB\xBF/', '', $value);
        
        // Nettoyer
        $value = trim($value);
        
        // Convertir "/" en vide si c'est la seule valeur
        if ($value === '/') {
            return '';
        }
        
        return $value;
    }
    
    /**
     * Commence à la ligne 5 (ignorant les 4 premières lignes)
     * Ligne 1: BOM + vide
     * Ligne 2: "قائمة مدارس ولاية الجلفة"
     * Ligne 3: vide
     * Ligne 4: en-têtes
     * Ligne 5: première donnée
     */
    public function startRow(): int
    {
        return 5;
    }
    
    /**
     * Configuration CSV spécifique
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';',
            'enclosure' => '"',
            'escape_character' => '\\',
            'input_encoding' => 'UTF-8',
            'ignore_bom' => true, // Important: ignorer le BOM
        ];
    }
    
    /**
     * Pour traiter par lots (performance)
     */
    public function chunkSize(): int
    {
        return 100;
    }
}