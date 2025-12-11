<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Distributor;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(20);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'wilaya' => 'nullable|string|max:100',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'phone' => $validated['phone'] ?? null,
        ]);

        // Assigner le rôle Spatie
        $user->assignRole($validated['role']);

        // Si c'est un distributeur, créer le profil
        if ($validated['role'] === 'distributor') {
            Distributor::create([
                'user_id' => $user->id,
                'name' => $validated['name'],
                'wilaya' => $validated['wilaya'] ?? 'Alger',
                'phone' => $validated['phone'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'distributorProfile', 'deliveries', 'payments']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'wilaya' => 'nullable|string|max:100',
        ]);

        // Mettre à jour l'utilisateur
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];
        $user->phone = $validated['phone'] ?? null;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        // Mettre à jour les rôles Spatie
        $user->syncRoles([$validated['role']]);

        // Gérer le profil distributeur
        if ($validated['role'] === 'distributor') {
            if ($user->distributorProfile) {
                $user->distributorProfile->update([
                    'name' => $validated['name'],
                    'wilaya' => $validated['wilaya'] ?? 'Alger',
                    'phone' => $validated['phone'] ?? null,
                ]);
            } else {
                Distributor::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'wilaya' => $validated['wilaya'] ?? 'Alger',
                    'phone' => $validated['phone'] ?? null,
                ]);
            }
        } elseif ($user->distributorProfile) {
            // Si ce n'est plus un distributeur, supprimer le profil
            $user->distributorProfile->delete();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Supprimer le profil distributeur s'il existe
        if ($user->distributorProfile) {
            $user->distributorProfile->delete();
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Assigner un rôle à un utilisateur
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);
        $user->update(['role' => $request->role]);

        return back()->with('success', 'Rôle assigné avec succès.');
    }

    /**
     * Rechercher des utilisateurs (pour AJAX)
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $users = User::where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->with('roles')
                    ->limit(10)
                    ->get();

        return response()->json($users);
    }
}