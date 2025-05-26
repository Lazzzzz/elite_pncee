<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        
        return view('user-management', compact('users', 'totalUsers', 'totalAdmins', 'recentUsers'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'is_admin' => ['boolean'],
            ], [
                'name.required' => 'Le nom est obligatoire.',
                'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être une adresse email valide.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $request->boolean('is_admin'),
            ]);

            return redirect()->route('user-management')->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('user-management')
                ->withInput()
                ->with('error', 'Une erreur s\'est produite lors de la création de l\'utilisateur: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                'is_admin' => ['boolean'],
            ], [
                'name.required' => 'Le nom est obligatoire.',
                'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
                'email.required' => 'L\'email est obligatoire.',
                'email.email' => 'L\'email doit être une adresse email valide.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => $request->boolean('is_admin'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('user-management')->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('user-management')
                ->withInput()
                ->with('error', 'Une erreur s\'est produite lors de la mise à jour de l\'utilisateur: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        // Prevent deleting the current user
        if ($user->id === Auth::id()) {
            return redirect()->route('user-management')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('user-management')->with('success', 'Utilisateur supprimé avec succès.');
    }
} 