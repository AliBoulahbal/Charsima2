@extends('layouts.admin')

@section('title', 'Export des Livraisons')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-truck"></i> Export des Livraisons
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Total des livraisons : {{ $deliveries->count() }}
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
                                    <th>Date</th>
                                    <th>École</th>
                                    <th>Wilaya</th>
                                    <th>Distributeur</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Prix Total</th>
                                    <th>Créé le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deliveries as $delivery)
                                    <tr>
                                        <td>{{ $delivery->id }}</td>
                                        <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                                        <td>{{ $delivery->school->name ?? 'N/A' }}</td>
                                        <td>{{ $delivery->school->wilaya ?? 'N/A' }}</td>
                                        <td>{{ $delivery->distributor->name ?? 'N/A' }}</td>
                                        <td>{{ number_format($delivery->quantity, 0, ',', ' ') }}</td>
                                        <td>{{ number_format($delivery->unit_price, 0, ',', ' ') }} DA</td>
                                        <td>{{ number_format($delivery->total_price, 0, ',', ' ') }} DA</td>
                                        <td>{{ $delivery->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucune livraison à exporter</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if($deliveries->count() > 0)
                                <tfoot>
                                    <tr class="bg-light">
                                        <td colspan="5" class="text-right"><strong>Totaux :</strong></td>
                                        <td><strong>{{ number_format($deliveries->sum('quantity'), 0, ',', ' ') }}</strong></td>
                                        <td></td>
                                        <td><strong>{{ number_format($deliveries->sum('total_price'), 0, ',', ' ') }} DA</strong></td>
                                        <td></td>
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
        const workbook = XLSX.utils.table_to_book(table, {sheet: "Livraisons"});
        XLSX.writeFile(workbook, 'livraisons_export_' + new Date().toISOString().split('T')[0] + '.xlsx');
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
            
            pdf.text('Export des Livraisons', 20, 20);
            pdf.text('Date d\'export : ' + new Date().toLocaleDateString(), 20, 30);
            pdf.addImage(imgData, 'PNG', 20, 40, pdfWidth - 40, pdfHeight);
            pdf.save('livraisons_export_' + new Date().toISOString().split('T')[0] + '.pdf');
        });
    }
</script>
@endpush