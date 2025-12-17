<?php

namespace App\Exports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SchoolsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // La colonne 'commune' est maintenant supposée exister grâce à la migration.
        return School::with(['deliveries', 'payments'])
                     ->orderBy('wilaya')
                     ->orderBy('commune') // Tri par commune
                     ->get();
    }

    /**
     * Définit les en-têtes de colonnes (Headers).
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Wilaya',
            'Commune',
            'Nom de l\'École',
            'Directeur',
            'Tél.',
            'Adresse/District',
            'Nb. Élèves',
            'Nb. Livraisons',
            'Cartes Livrées (Qté)',
            'Montant Livré (DA)',
            'Montant Payé (DA)',
            'Montant Dû (DA)',
            'Latitude',
            'Longitude',
        ];
    }

    /**
     * Mappe chaque ligne de données de l'école vers les colonnes.
     * @param School $school
     * @return array
     */
    public function map($school): array
    {
        $totalDelivered = $school->deliveries->sum('final_price');
        $totalPaid = $school->payments->sum('amount');
        $totalDue = $totalDelivered - $totalPaid;

        return [
            $school->id,
            $school->wilaya,
            $school->commune,
            $school->name,
            $school->manager_name,
            $school->phone,
            $school->district,
            $school->student_count,
            $school->deliveries->count(),
            $school->deliveries->sum('quantity'),
            number_format($totalDelivered, 2, '.', ''),
            number_format($totalPaid, 2, '.', ''),
            number_format($totalDue, 2, '.', ''),
            $school->latitude,
            $school->longitude,
        ];
    }
}