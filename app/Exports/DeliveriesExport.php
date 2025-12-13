<?php

namespace App\Exports;

use App\Models\Delivery;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeliveriesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
    * Récupère la collection de livraisons filtrées pour l'exportation.
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Récupérer la requête de base avec les relations nécessaires
        $query = Delivery::with(['school', 'distributor.user', 'kiosk']);
        
        // --- Application des filtres ---
        
        if (!empty($this->filters['school_id'])) {
            $query->where('school_id', $this->filters['school_id']);
        }
        if (!empty($this->filters['distributor_id'])) {
            $query->where('distributor_id', $this->filters['distributor_id']);
        }
        if (!empty($this->filters['date_from'])) {
            $query->whereDate('delivery_date', '>=', $this->filters['date_from']);
        }
        if (!empty($this->filters['date_to'])) {
            $query->whereDate('delivery_date', '<=', $this->filters['date_to']);
        }
        if (!empty($this->filters['delivery_type'])) {
            $query->where('delivery_type', $this->filters['delivery_type']);
        }
        
        if (!empty($this->filters['wilaya'])) {
            $wilaya = $this->filters['wilaya'];
            $query->where(function ($q) use ($wilaya) {
                $q->whereHas('school', function($q_school) use ($wilaya) {
                    $q_school->where('wilaya', $wilaya);
                })
                ->orWhere('deliveries.wilaya', $wilaya);
            });
        }

        return $query->latest('delivery_date')->get();
    }
    
    /**
     * Définir l'en-tête du fichier Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Date Livraison',
            'Type',
            'Partenaire',
            'Wilaya',
            'École',
            'Quantité (Cartes)',
            'Montant Final (DA)',
            'Transaction ID',
            'Statut'
        ];
    }

    /**
     * Mapper chaque ligne de la collection vers les colonnes Excel
     */
    public function map($delivery): array
    {
        $partnerName = 'N/A';
        if ($delivery->distributor) {
            $partnerName = $delivery->distributor->user->name ?? $delivery->distributor->name;
        } elseif ($delivery->kiosk) {
            $partnerName = $delivery->kiosk->name;
        } elseif ($delivery->delivery_type === 'online') {
             $partnerName = 'Vente en ligne';
        }

        return [
            $delivery->id,
            $delivery->delivery_date ? $delivery->delivery_date->format('d/m/Y H:i') : 'N/A',
            $delivery->delivery_type_formatted, 
            $partnerName,
            $delivery->school->wilaya ?? $delivery->wilaya ?? 'N/A',
            $delivery->school->name ?? 'N/A',
            $delivery->quantity,
            $delivery->final_price,
            $delivery->transaction_id,
            $delivery->status,
        ];
    }
}