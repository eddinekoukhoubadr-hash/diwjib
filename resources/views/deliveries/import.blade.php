@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">📤 Importer des livraisons via CSV</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Erreurs :</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('deliveries.import.csv') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="csv_file" class="form-label fw-bold">Sélectionner le fichier CSV</label>
                            <input type="file" name="file" id="csv_file" class="form-control" accept=".csv" required>
                            <div class="form-text">
                                Formats acceptés : CSV (max 10MB)
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-upload"></i> Importer le fichier
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="alert alert-info">
                        <h5 class="alert-heading">📋 Format CSV requis</h5>
                        <p class="mb-2">Votre fichier CSV doit contenir les colonnes suivantes (avec en-têtes) :</p>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Colonne</th>
                                        <th>Description</th>
                                        <th>Exemple</th>
                                        <th>Requis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>client_name</code></td>
                                        <td>Nom du client</td>
                                        <td>Yassine Drissennek</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>address</code></td>
                                        <td>Adresse complète</td>
                                        <td>8 rue Jean Jacques Rousseau</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>city</code></td>
                                        <td>Ville</td>
                                        <td>Casablanca</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>postal_code</code></td>
                                        <td>Code postal</td>
                                        <td>20000</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>time_slot</code></td>
                                        <td>Créneau horaire</td>
                                        <td>10:00 - 12:00</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>weight</code></td>
                                        <td>Poids en kg</td>
                                        <td>52</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>status</code></td>
                                        <td>Statut</td>
                                        <td>en attente</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>latitude</code></td>
                                        <td>Latitude</td>
                                        <td>33.5902</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                    <tr>
                                        <td><code>longitude</code></td>
                                        <td>Longitude</td>
                                        <td>-7.6038</td>
                                        <td>✅ Oui</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <p class="mt-3 mb-1"><strong>Exemple de ligne :</strong></p>
                        <code class="d-block p-2 bg-light rounded">
                            Yassine Drissennek,8 rue Jean Jacques Rousseau,Casablanca,20000,10:00 - 12:00,52,en attente,33.5902,-7.6038
                        </code>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading">💡 Conseil</h6>
                        <p class="mb-0">Téléchargez d'abord le <a href="#template" onclick="downloadTemplate()" class="alert-link">modèle CSV</a> pour vous assurer du bon format.</p>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la liste des livraisons
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function downloadTemplate() {
    const csvContent = "client_name,address,city,postal_code,time_slot,weight,status,latitude,longitude\n" +
                      "Yassine Drissennek,8 rue Jean Jacques Rousseau,Casablanca,20000,10:00 - 12:00,52,en attente,33.5902,-7.6038\n" +
                      "Karim El Aouad,27 avenue Moulay Youssef,Rabat,10000,12:00 - 14:00,18,en cours,34.0209,-6.8416\n" +
                      "Sara Benali,54 boulevard Moulay Rachid,Marrakech,40000,14:00 - 16:00,22,livrée,31.6258,-7.9892";
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'modele-livraisons.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Validation du fichier
document.getElementById('importForm').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('csv_file');
    const submitBtn = document.getElementById('submitBtn');
    
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        if (!file.name.toLowerCase().endsWith('.csv')) {
            e.preventDefault();
            alert('Veuillez sélectionner un fichier CSV.');
            return;
        }
        
        // Désactiver le bouton pendant l'import
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Import en cours...';
    }
});
</script>

<style>
.table code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 0.9em;
}
</style>
@endpush