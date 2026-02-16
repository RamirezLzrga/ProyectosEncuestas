<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filtros
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('role') && $request->role != 'Todos') {
            $query->where('role', $request->role);
        }

        if ($request->has('status') && $request->status != 'Todos') {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,editor',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        // Log Activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'action' => 'create',
            'description' => 'Creó usuario: ' . $user->name,
            'type' => 'user',
            'ip_address' => $request->ip(),
            'details' => ['user_id' => $user->id]
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,editor',
            'status' => 'required|string|in:active,inactive',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // Log Activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'action' => 'update',
            'description' => 'Actualizó usuario: ' . $user->name,
            'type' => 'user',
            'ip_address' => $request->ip(),
            'details' => ['user_id' => $user->id]
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting self
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $userName = $user->name;
        $user->delete();

        // Log Activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'action' => 'delete',
            'description' => 'Eliminó usuario: ' . $userName,
            'type' => 'user',
            'ip_address' => $request->ip(),
            'details' => ['user_id' => $id]
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
