@extends('admin.layouts.admin')
@section('title', 'Rapport Financier')
@section('page-title', 'Rapport Financier: ' . $kiosk->name)

@section('page-actions')
    <a href="{{ route('admin.kiosks.show', $kiosk) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour au Kiosque
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-tags me-2"></i> Ventes par Type de Transaction (Total)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Type de Livraison</th>
                                <th class="text-end">Nombre de Ventes</th>
                                <th class="text-end">Montant Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typeStats as $stat)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $stat->delivery_type)) }}</span>
                                </td>
                                <td class="text-end">{{ number_format($stat->sales_count) }}</td>
                                <td class="text-end fw-bold text-success">
                                    {{ number_format($stat->total_amount, 0, ',', ' ') }} DA
                                </td>
                            </tr>
                            @endforeach
                            <tr class="table-info">
                                <th>Total</th>
                                <th class="text-end">{{ number_format($typeStats->sum('sales_count')) }}</th>
                                <th class="text-end">{{ number_format($typeStats->sum('total_amount'), 0, ',', ' ') }} DA</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold"><i class="fas fa-calendar-alt me-2"></i> Rapport de Ventes Mensuel (12 derniers mois)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Mois</th>
                                <th class="text-end">Nombre de Ventes</th>
                                <th class="text-end">Montant Total</th>
                                <th class="text-end">Remise Moyenne (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlySales as $sale)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromDate($sale->year, $sale->month)->translatedFormat('F Y') }}</td>
                                <td class="text-end">{{ number_format($sale->sales_count) }}</td>
                                <td class="text-end fw-bold text-success">
                                    {{ number_format($sale->total_amount, 0, ',', ' ') }} DA
                                </td>
                                <td class="text-end">{{ number_format($sale->avg_discount, 2) }} %</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucune donnée de ventes mensuelles.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection