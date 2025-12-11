@extends('layouts.admin')

@section('title', 'Export des Écoles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-school"></i> Export des Écoles
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Total des écoles : {{ $schools->count() }}
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
                                    <th>Commune</th>
                                    <th>Directeur</th>
                                    <th>Nombre d'élèves</th>
                                    <th>Téléphone</th>
                                    <th>Livraisons</th>
                                    <th>Total Livré</th>
                                    <th>Créé le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schools as $school)
                                    <tr>
                                        <td>{{ $school->id }}</td>
                                        <td>{{ $school->name }}</td>
                                        <td>{{ $school->wilaya }}</td>
                                        <td>{{ $school->district }}</td>
                                        <td>{{ $school->manager_name }}</td>
                                        <td>{{ number_format($school->student_count, 0, ',', ' ') }}</td>
                                        <td>{{ $school->phone ?? 'N/A' }}</td>
                                        <td>{{ $school->deliveries_count }}</td>
                                        <td>{{ number_format($school->total_delivered, 0, ',', ' ') }} DA</td>
                                        <td>{{ $school->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Aucune école à exporter</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($schools->count() > 0)
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="7" class="text-right"><strong>Totaux :</strong></td>
                                        <td><strong>{{ $schools->sum('deliveries_count') }}</strong></td>
                                        <td><strong>{{ number_format($schools->sum('total_delivered'), 0, ',', ' ') }} DA</strong></td>
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
                                        $byWilaya = $schools->groupBy('wilaya')->map(function($group) {
                                            return [
                                                'count' => $group->count(),
                                                'total_delivered' => $group->sum('total_delivered')
                                            ];
                                        });
                                    @endphp
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Wilaya</th>
                                                <th>Écoles</th>
                                                <th>Total Livré</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($byWilaya as $wilaya => $data)
                                                <tr>
                                                    <td>{{ $wilaya }}</td>
                                                    <td>{{ $data['count'] }}</td>
                                                    <td>{{ number_format($data['total_delivered'], 0, ',', ' ') }} DA</td>
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
                                    <h5 class="card-title">Statistiques Générales</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Nombre total d'écoles
                                            <span class="badge badge-primary badge-pill">{{ $schools->count() }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Élèves total
                                            <span class="badge badge-info badge-pill">{{ number_format($schools->sum('student_count'), 0, ',', ' ') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Livraisons totales
                                            <span class="badge badge-success badge-pill">{{ $schools->sum('deliveries_count') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Montant total livré
                                            <span class="badge badge-warning badge-pill">{{ number_format($schools->sum('total_delivered'), 0, ',', ' ') }} DA</span>
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
        const workbook = XLSX.utils.table_to_book(table, {sheet: "Écoles"});
        XLSX.writeFile(workbook, 'ecoles_export_' + new Date().toISOString().split('T')[0] + '.xlsx');
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
            
            pdf.text('Export des Écoles', 20, 20);
            pdf.text('Date d\'export : ' + new Date().toLocaleDateString(), 20, 30);
            pdf.addImage(imgData, 'PNG', 20, 40, pdfWidth - 40, pdfHeight);
            pdf.save('ecoles_export_' + new Date().toISOString().split('T')[0] + '.pdf');
        });
    }
</script>
@endpush