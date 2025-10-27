<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PDF;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_blocked', false)->count();
        $blockedUsers = User::where('is_blocked', true)->count();
        $totalDeliveries = Delivery::count();

        $deliveriesStatusCount = Delivery::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $deliveriesPerDay = Delivery::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $deliveries = Delivery::whereNotNull('latitude')->whereNotNull('longitude')->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'activeUsers',
            'blockedUsers',
            'totalDeliveries',
            'deliveriesStatusCount',
            'deliveriesPerDay',
            'deliveries'
        ));
    }

    public function users(Request $request)
    {
        $search = $request->input('search');
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->orderBy('id', 'desc')->get();

        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur ajouté avec succès.');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }

    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $deliveries = Delivery::where('user_id', $id)->get();

        $total = $deliveries->count();
        $livree = $deliveries->where('status', 'livrée')->count();
        $en_cours = $deliveries->where('status', 'en cours')->count();
        $en_attente = $deliveries->where('status', 'en attente')->count();

        return view('admin.user_details', compact('user', 'deliveries', 'total', 'livree', 'en_cours', 'en_attente'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour.');
    }

    public function exportPdf()
    {
        $deliveries = Delivery::all();
        $pdf = PDF::loadView('admin.pdf_deliveries', compact('deliveries'));
        return $pdf->download('livraisons.pdf');
    }
}
