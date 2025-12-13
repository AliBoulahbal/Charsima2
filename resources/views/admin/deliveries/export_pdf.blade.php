<!DOCTYPE html>
<html>
<head>
    <title>Export Livraisons</title>
    <style>
        /*
         * ESSENTIEL POUR L'ARABE
         */
        body { 
            font-family: 'DejaVu Sans', sans-serif; /* Utiliser DejaVu Sans ou une autre police Unicode */
        }
        
        /* Forcer la direction de droite à gauche pour le corps du document */
        html { 
            direction: rtl; 
        }

        /* Stylisme de base */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            /* Assurer que le texte s'aligne correctement */
            text-align: right; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        h1 { 
            font-size: 18px; 
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>تقرير الشحنات (Rapport de Livraisons) - {{ now()->format('d/m/Y H:i') }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>البيان (ID)</th>
                <th>التاريخ (Date)</th>
                <th>النوع (Type)</th>
                <th>الولاية (Wilaya)</th>
                <th>المبلغ (Montant)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $delivery)
            <tr>
                <td>{{ $delivery->id }}</td>
                {{-- Formatage des dates --}}
                <td>{{ $delivery->delivery_date->format('d/m/Y') }}</td>
                {{-- Laissez la Wilaya, Dompdf la gère correctement --}}
                <td>{{ $delivery->delivery_type }}</td>
                <td>{{ $delivery->wilaya }}</td>
                <td>{{ number_format($delivery->final_price, 0, ',', ' ') }} DA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>