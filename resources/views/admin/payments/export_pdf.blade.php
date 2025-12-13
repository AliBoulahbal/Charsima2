<!DOCTYPE html>
<html>
<head>
    <title>Rapport de Paiements</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* CSS ESSENTIEL POUR LE SUPPORT ARABE/RTL DANS DOMPDF */
        /* Assurez-vous d'avoir les polices DejaVu installées ou une alternative RTL */
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('/fonts/DejaVuSans.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body { 
            font-family: 'DejaVu Sans', sans-serif;
            /* Le corps entier est affiché en RTL */
            direction: rtl; 
            font-size: 10px;
        }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px; 
            text-align: right; /* Alignement du texte à droite */
        }
        th { 
            background-color: #f2f2f2; 
        }
        h1 { 
            font-size: 18px; 
            text-align: right;
            margin-bottom: 20px;
        }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; }
    </style>
</head>
<body>
    <h1>تقرير المدفوعات (Rapport de Paiements) - {{ now()->format('d/m/Y H:i') }}</h1>
    
    <table>
        <thead>
            <tr>
                <th class="text-center">البيان (ID)</th>
                <th class="text-center">التاريخ (Date)</th>
                <th>الشريك (Partenaire)</th>
                <th>المدرسة (School)</th>
                <th>الولاية (Wilaya)</th>
                <th class="text-center">المبلغ (Montant DA)</th>
                <th>الطريقة (Méthode)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td class="text-center">{{ $payment->id }}</td>
                <td class="text-center">{{ $payment->payment_date->format('d/m/Y') }}</td>
                <td>
                    {{-- Déterminer le nom du partenaire --}}
                    @if($payment->distributor)
                        {{ $payment->distributor->user?->name ?? $payment->distributor->name }} (موزع)
                    @elseif($payment->kiosk)
                        {{ $payment->kiosk->name }} (كشك)
                    @else
                        {{ $payment->payment_type ?? 'أخرى' }}
                    @endif
                </td>
                <td>{{ $payment->school->name ?? $payment->school_name ?? 'N/A' }}</td>
                <td>{{ $payment->wilaya ?? 'N/A' }}</td>
                <td class="text-center">{{ number_format($payment->amount, 0, ',', ' ') }}</td>
                <td>{{ $payment->method_formatted ?? $payment->method }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">لا توجد مدفوعات مطابقة للمعايير.</td>
            </tr>
            @endforelse
        </tbody>
        
        {{-- Total --}}
        <tfoot>
            <tr>
                <th colspan="5">الإجمالي</th>
                <th class="text-center">{{ number_format($payments->sum('amount'), 0, ',', ' ') }} DA</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Généré par le système le {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>