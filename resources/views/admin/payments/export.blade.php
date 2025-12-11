@extends('layouts.admin')

@section('title', 'Export des Paiements')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-file-export"></i> Export des Paiements
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Total des paiements : {{ $payments->count() }}
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
                                    <th>Distributeur</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                    <th>Méthode</th>
                                    <th>Créé le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->id }}</td>
                                        <td>{{ $payment->distributor->name ?? 'N/A' }}</td>
                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} DA</td>
                                        <td>
                                            @switch($payment->method)
                                                @case('cash')
                                                    <span class="badge badge-success">Espèces</span>
                                                    @break
                                                @case('check')
                                                    <span class="badge badge-info">Chèque</span>
                                                    @break
                                                @case('transfer')
                                                    <span class="badge badge-warning">Virement</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">Autre</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucun paiement à exporter</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($payments->count() > 0)
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="3" class="text-right"><strong>Total :</strong></td>
                                        <td><strong>{{ number_format($payments->sum('amount'), 0, ',', ' ') }} DA</strong></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
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
        const workbook = XLSX.utils.table_to_book(table, {sheet: "Paiements"});
        XLSX.writeFile(workbook, 'paiements_export_' + new Date().toISOString().split('T')[0] + '.xlsx');
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
            
            pdf.text('Export des Paiements', 20, 20);
            pdf.text('Date d\'export : ' + new Date().toLocaleDateString(), 20, 30);
            pdf.addImage(imgData, 'PNG', 20, 40, pdfWidth - 40, pdfHeight);
            pdf.save('paiements_export_' + new Date().toISOString().split('T')[0] + '.pdf');
        });
    }
</script>
@endpush