@extends('layouts.admin')

@section('title', 'Export des Distributeurs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users"></i> Export des Distributeurs
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Total des distributeurs : {{ $distributors->count() }}
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <button class="btn btn-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Exporter en Excel
                            </button>
                            <button class="btn btn-danger" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf"></i> Exporter en PDF
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive" id="exportTable">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Wilaya</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Livraisons</th>
                                    <th>Total Livré</th>
                                    <th>Total Payé</th>
                                    <th>Solde</th>
                                    <th>Créé le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($distributors as $distributor)
                                    @php
                                        $balance = $distributor->total_paid - $distributor->total_delivered;
                                    @endphp
                                    <tr>
                                        <td>{{ $distributor->id }}</td>
                                        <td>{{ $distributor->name }}</td>
                                        <td>{{ $distributor->wilaya }}</td>
                                        <td>{{ $distributor->phone ?? 'N/A' }}</td>
                                        <td>{{ $distributor->user->email ?? 'N/A' }}</td>
                                        <td>{{ $distributor->deliveries_count }}</td>
                                        <td>{{ number_format($distributor->total_delivered, 0, ',', ' ') }} DA</td>
                                        <td>{{ number_format($distributor->total_paid, 0, ',', ' ') }} DA</td>
                                        <td class="{{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($balance, 0, ',', ' ') }} DA
                                        </td>
                                        <td>{{ $distributor->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Aucun distributeur à exporter</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($distributors->count() > 0)
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="5" class="text-right"><strong>Totaux :</strong></td>
                                        <td><strong>{{ $distributors->sum('deliveries_count') }}</strong></td>
                                        <td><strong>{{ number_format($distributors->sum('total_delivered'), 0, ',', ' ') }} DA</strong></td>
                                        <td><strong>{{ number_format($distributors->sum('total_paid'), 0, ',', ' ') }} DA</strong></td>
                                        <td><strong>{{ number_format($distributors->sum('total_paid') - $distributors->sum('total_delivered'), 0, ',', ' ') }} DA</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Répartition par Wilaya</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $byWilaya = $distributors->groupBy('wilaya')->map(function($group) {
                                            return [
                                                'count' => $group->count(),
                                                'total_delivered' => $group->sum('total_delivered'),
                                                'total_paid' => $group->sum('total_paid')
                                            ];
                                        });
                                    @endphp
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Wilaya</th>
                                                <th>Distributeurs</th>
                                                <th>Total Livré</th>
                                                <th>Total Payé</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($byWilaya as $wilaya => $data)
                                                <tr>
                                                    <td>{{ $wilaya }}</td>
                                                    <td>{{ $data['count'] }}</td>
                                                    <td>{{ number_format($data['total_delivered'], 0, ',', ' ') }} DA</td>
                                                    <td>{{ number_format($data['total_paid'], 0, ',', ' ') }} DA</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistiques de Performance</h5>
                                </div>
                                <div class="card-body">
                                    @php
                                        $activeDistributors = $distributors->where('deliveries_count', '>', 0);
                                        $avgDeliveries = $activeDistributors->count() > 0 ? 
                                            round($activeDistributors->sum('deliveries_count') / $activeDistributors->count(), 2) : 0;
                                        $avgAmount = $activeDistributors->count() > 0 ? 
                                            round($activeDistributors->sum('total_delivered') / $activeDistributors->count(), 0) : 0;
                                        $totalBalance = $distributors->sum('total_paid') - $distributors->sum('total_delivered');
                                    @endphp
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Distributeurs actifs
                                            <span class="badge badge-primary badge-pill">{{ $activeDistributors->count() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Moyenne livraisons/distributeur
                                            <span class="badge badge-info badge-pill">{{ $avgDeliveries }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Montant moyen/distributeur
                                            <span class="badge badge-success badge-pill">{{ number_format($avgAmount, 0, ',', ' ') }} DA</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Solde global
                                            <span class="badge {{ $totalBalance >= 0 ? 'badge-warning' : 'badge-danger' }} badge-pill">
                                                {{ number_format($totalBalance, 0, ',', ' ') }} DA
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
<script>
    function exportToExcel() {
        const table = document.getElementById('exportTable').getElementsByTagName('table')[0];
        const workbook = XLSX.utils.table_to_book(table, {sheet: "Distributeurs"});
        XLSX.writeFile(workbook, 'distributeurs_export_' + new Date().toISOString().split('T')[0] + '.xlsx');
    }

    function exportToPDF() {
        const element = document.getElementById('exportTable');
        html2canvas(element).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('l', 'mm', 'a4');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            
            pdf.text('Export des Distributeurs', 20, 20);
            pdf.text('Date d\'export : ' + new Date().toLocaleDateString(), 20, 30);
            pdf.addImage(imgData, 'PNG', 20, 40, pdfWidth - 40, pdfHeight);
            pdf.save('distributeurs_export_' + new Date().toISOString().split('T')[0] + '.pdf');
        });
    }
</script>
@endpush