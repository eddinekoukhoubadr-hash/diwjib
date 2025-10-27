<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Affichage du formulaire
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Mise à jour du mot de passe
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Vérifie si l'ancien mot de passe est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Ancien mot de passe incorrect.']);
        }

        // Mise à jour
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }
}
