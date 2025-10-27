<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Livraisons PDF</title>
    <style>
        table {
            width: 100%; border-collapse: collapse; font-size: 12px;
        }
        th, td {
            border: 1px solid #333; padding: 6px;
        }
        th {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h2>Liste des Livraisons</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Poids</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $delivery)
            <tr>
                <td>{{ $delivery->id }}</td>
                <td>{{ $delivery->client_name }}</td>
                <td>{{ $delivery->address }}</td>
                <td>{{ $delivery->city }}</td>
                <td>{{ $delivery->weight }} kg</td>
                <td>{{ $delivery->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
