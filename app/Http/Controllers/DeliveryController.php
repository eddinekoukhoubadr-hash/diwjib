<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DeliveriesExport;
use Illuminate\Validation\Rules;

class DeliveryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $deliveries = Delivery::where('user_id', $user->_id)->get();
        return view('deliveries.index', compact('deliveries'));
    }

    public function show($id)
    {
        $delivery = Delivery::where('_id', $id)->firstOrFail();
        return view('deliveries.show', compact('delivery'));
    }

    public function create()
    {
        return view('deliveries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'time_slot' => ['required', 'string'],
            'weight' => ['required', 'numeric', 'min:0.1'],
            'status' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        Delivery::create([
            'user_id' => $user->_id,
            'client_name' => $request->client_name,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'time_slot' => $request->time_slot,
            'weight' => $request->weight,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('deliveries.index')->with('success', 'Livraison créée avec succès!');
    }

    public function edit($id)
    {
        $delivery = Delivery::where('_id', $id)->firstOrFail();
        return view('deliveries.edit', compact('delivery'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'time_slot' => ['required', 'string'],
            'weight' => ['required', 'numeric', 'min:0.1'],
            'status' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $delivery = Delivery::where('_id', $id)->firstOrFail();
        $delivery->update([
            'client_name' => $request->client_name,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'time_slot' => $request->time_slot,
            'weight' => $request->weight,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('deliveries.index')->with('success', 'Livraison modifiée avec succès!');
    }

    public function destroy($id)
    {
        $delivery = Delivery::where('_id', $id)->firstOrFail();
        $delivery->delete();
        return redirect()->route('deliveries.index')->with('success', 'Livraison supprimée avec succès!');
    }

    public function clientDashboard()
    {
        $user = Auth::user();
        $deliveries = Delivery::where('user_id', $user->_id)->get();
        $totalDeliveries = $deliveries->count();
        $pendingDeliveries = $deliveries->where('status', 'en attente')->count();
        $completedDeliveries = $deliveries->where('status', 'livrée')->count();
        
        $countDelivered = $deliveries->where('status', 'livrée')->count();
        $countInProgress = $deliveries->where('status', 'en cours')->count();
        $countPending = $pendingDeliveries;

        $cities = $deliveries->groupBy('city')->map->count();

        return view('deliveries.dashboard_client', compact(
            'deliveries', 
            'totalDeliveries', 
            'pendingDeliveries', 
            'completedDeliveries',
            'countDelivered',
            'countInProgress',
            'countPending',
            'cities'
        ));
    }

    public function showImportForm()
    {
        return view('deliveries.import');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240' // 10MB max
        ]);

        try {
            $path = $request->file('file')->getRealPath();
            $data = array_map('str_getcsv', file($path));
            
            // Vérifier que le fichier n'est pas vide
            if (count($data) < 2) {
                return back()->with('error', 'Le fichier CSV est vide ou ne contient pas de données.');
            }

            $header = array_map('strtolower', array_map('trim', $data[0]));
            $requiredColumns = ['client_name', 'address', 'city', 'postal_code', 'time_slot', 'weight', 'status', 'latitude', 'longitude'];
            
            // Vérifier les colonnes requises
            $missingColumns = array_diff($requiredColumns, $header);
            if (!empty($missingColumns)) {
                return back()->with('error', 'Colonnes manquantes dans le CSV: ' . implode(', ', $missingColumns));
            }

            $user = Auth::user();
            $importedCount = 0;
            $errors = [];

            foreach (array_slice($data, 1) as $index => $row) {
                try {
                    // Nettoyer les données
                    $row = array_map('trim', $row);
                    
                    // Combiner avec les en-têtes
                    if (count($row) !== count($header)) {
                        $errors[] = "Ligne " . ($index + 2) . ": Nombre de colonnes incorrect";
                        continue;
                    }

                    $rowData = array_combine($header, $row);
                    
                    // Validation des données
                    if (empty($rowData['client_name']) || empty($rowData['address'])) {
                        $errors[] = "Ligne " . ($index + 2) . ": Données client manquantes";
                        continue;
                    }

                    // Créer la livraison
                    Delivery::create([
                        'user_id' => $user->_id,
                        'client_name' => $rowData['client_name'],
                        'address' => $rowData['address'],
                        'city' => $rowData['city'],
                        'postal_code' => $rowData['postal_code'],
                        'time_slot' => $rowData['time_slot'],
                        'weight' => floatval($rowData['weight']),
                        'status' => $rowData['status'],
                        'latitude' => floatval($rowData['latitude']),
                        'longitude' => floatval($rowData['longitude']),
                    ]);

                    $importedCount++;

                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            $message = $importedCount . " livraison(s) importée(s) avec succès.";
            if (!empty($errors)) {
                $message .= " Erreurs: " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= "... et " . (count($errors) - 5) . " autres erreurs";
                }
            }

            return redirect()->route('deliveries.index')
                            ->with($errors ? 'warning' : 'success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'import: ' . $e->getMessage());
        }
    }

    public function optimizeRoute()
    {
        $user = Auth::user();
        $deliveries = Delivery::where('user_id', $user->_id)->get();
        return view('deliveries.optimize', compact('deliveries'));
    }

    public function showRouteMap()
    {
        $user = Auth::user();
        $deliveries = Delivery::where('user_id', $user->_id)->get();
        return view('deliveries.map', compact('deliveries'));
    }

    public function optimizeSingle($id)
    {
        $delivery = Delivery::where('_id', $id)->firstOrFail();
        $depot = ['latitude' => 33.5731, 'longitude' => -7.5898];

        if (!$delivery->latitude || !$delivery->longitude) {
            return back()->with('error', 'Coordonnées manquantes pour cette livraison.');
        }

        // Essayer d'abord OSRM (plus fiable et gratuit)
        $routeData = $this->getRouteFromOSRM($depot, $delivery);
        
        // Si OSRM échoue, essayer OpenRouteService
        if (!$routeData) {
            $routeData = $this->getRouteFromOpenRouteService($depot, $delivery);
        }

        // Si les deux APIs échouent, utiliser le calcul simple
        if (!$routeData) {
            $routeData = $this->getSimpleRoute($depot, $delivery);
        }

        return view('deliveries.single_route', array_merge(
            ['delivery' => $delivery, 'depot' => $depot],
            $routeData
        ));
    }

    private function getRouteFromOSRM($depot, $delivery)
    {
        try {
            $client = new \GuzzleHttp\Client();
            
            $url = sprintf(
                'http://router.project-osrm.org/route/v1/driving/%s,%s;%s,%s?overview=full&geometries=geojson',
                $depot['longitude'],
                $depot['latitude'],
                $delivery->longitude,
                $delivery->latitude
            );

            $response = $client->get($url, ['timeout' => 15]);
            $data = json_decode($response->getBody(), true);

            if ($data['code'] === 'Ok' && !empty($data['routes'][0])) {
                $route = $data['routes'][0];
                
                return [
                    'distanceKm' => $route['distance'] / 1000,
                    'durationSeconds' => $route['duration'],
                    'geojson' => $route['geometry']
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('OSRM API failed: ' . $e->getMessage());
        }

        return null;
    }

    private function getRouteFromOpenRouteService($depot, $delivery)
    {
        $apiKey = config('services.ors.key');
        if (!$apiKey) {
            return null;
        }

        try {
            $client = new \GuzzleHttp\Client();
            
            $response = $client->post('https://api.openrouteservice.org/v2/directions/driving-car', [
                'headers' => [
                    'Authorization' => $apiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'coordinates' => [
                        [$depot['longitude'], $depot['latitude']],
                        [$delivery->longitude, $delivery->latitude]
                    ],
                    'instructions' => false,
                    'geometry' => true
                ],
                'timeout' => 15,
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['features'][0]['properties']['summary']) && 
                isset($data['features'][0]['geometry'])) {
                
                $summary = $data['features'][0]['properties']['summary'];
                
                return [
                    'distanceKm' => ($summary['distance'] ?? 0) / 1000,
                    'durationSeconds' => $summary['duration'] ?? 0,
                    'geojson' => $data['features'][0]['geometry']
                ];
            }
        } catch (\Exception $e) {
            \Log::warning('OpenRouteService API failed: ' . $e->getMessage());
        }

        return null;
    }

    private function getSimpleRoute($depot, $delivery)
    {
        $distanceKm = $this->calculateHaversineDistance($depot, $delivery);
        $durationSeconds = $this->estimateDrivingTime($distanceKm);
        
        return [
            'distanceKm' => $distanceKm,
            'durationSeconds' => $durationSeconds,
            'geojson' => [
                'type' => 'LineString',
                'coordinates' => [
                    [$depot['longitude'], $depot['latitude']],
                    [$delivery->longitude, $delivery->latitude]
                ]
            ]
        ];
    }

    private function calculateHaversineDistance($point1, $point2)
    {
        $earthRadius = 6371;

        $lat1 = deg2rad($point1['latitude']);
        $lon1 = deg2rad($point1['longitude']);
        $lat2 = deg2rad($point2->latitude);
        $lon2 = deg2rad($point2->longitude);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat/2) * sin($dLat/2) + 
             cos($lat1) * cos($lat2) * 
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    private function estimateDrivingTime($distanceKm)
    {
        // Estimation basée sur une vitesse moyenne de 40 km/h en ville
        $averageSpeed = 40;
        $timeHours = $distanceKm / $averageSpeed;
        
        // Ajouter 25% de temps pour les arrêts et la circulation
        return $timeHours * 1.25 * 3600;
    }

    public function exportSinglePdf($id)
    {
        $delivery = Delivery::where('_id', $id)->firstOrFail();
        return \PDF::loadView('deliveries.pdf_single', compact('delivery'))->download('delivery_'.$id.'.pdf');
    }
}