<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture - {{ $client->nom }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 40px 20px;
        }

        .invoice-container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }

        .invoice-header h1 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .invoice-logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .invoice-details {
            margin-bottom: 25px;
            font-size: 16px;
        }

        .invoice-details p {
            margin: 5px 0;
        }

        .table th {
            background: #007bff;
            color: white;
            text-align: center;
            font-weight: 600;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .total-row {
            font-size: 18px;
            font-weight: bold;
            background: #f1f1f1;
        }

        .btn-print {
            margin-top: 20px;
            text-align: center;
        }

        .btn-print button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-print button:hover {
            background: #0056b3;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="invoice-container">
    <!-- En-tête de la facture -->
    <div class="invoice-header">
        <img src="{{ asset('logo.png') }}" alt="Logo Hypotherapp" class="invoice-logo">
        <h1>Hypotherapp</h1>
        <h2>Facture</h2>
        <p><strong>Date :</strong> {{ date('d/m/Y') }}</p>
    </div>

    <!-- Informations du client -->
    <div class="invoice-details">
        <p><strong>Client :</strong> {{ $client->nom }}</p>
        <p><strong>Nombre de personnes :</strong> {{ $client->nombre_personnes }}</p>
        <p><strong>Durée :</strong> {{ $client->minutes }} minutes</p>
    </div>

    <!-- Tableau des détails de facturation -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Description</th>
            <th>Quantité</th>
            <th>Prix Unitaire (€)</th>
            <th>Total (€)</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Service équestre</td>
            <td>{{ $client->nombre_personnes }}</td>
            <td>{{ number_format($client->prix_total / $client->nombre_personnes, 2, ',', ' ') }}</td>
            <td>{{ number_format($client->prix_total, 2, ',', ' ') }}</td>
        </tr>
        </tbody>
        <tfoot>
        <tr class="total-row">
            <td colspan="3" class="text-end">Montant Total :</td>
            <td>{{ number_format($client->prix_total, 2, ',', ' ') }} €</td>
        </tr>
        </tfoot>
    </table>
</div>

</body>
</html>
