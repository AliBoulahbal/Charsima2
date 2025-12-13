<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $query;

    public function __construct(Builder $query)
    {
        // La requête est passée depuis le PaymentController
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date du paiement',
            'Type de paiement',
            'Montant (DA)',
            'Méthode',
            'Référence',
            'Distributeur',
            'Kiosque',
            'École',
            'Wilaya',
            'Notes'
        ];
    }

    public function map($payment): array
    {
        // map() est appelé pour chaque paiement de la collection
        return [
            $payment->id,
            $payment->payment_date->format('d/m/Y'),
            $payment->payment_type,
            $payment->amount,
            $payment->method_formatted ?? $payment->method, // Utiliser l'accesseur si disponible
            $payment->reference_number,
            $payment->distributor->name ?? 'N/A',
            $payment->kiosk->name ?? 'N/A',
            $payment->school->name ?? $payment->school_name ?? 'N/A',
            $payment->wilaya,
            $payment->notes,
        ];
    }
}