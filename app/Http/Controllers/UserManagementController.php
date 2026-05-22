<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $query = DB::table('users')
            ->leftJoin('incidents', 'users.id', '=', 'incidents.assigned_to')
            ->select('users.*', DB::raw('COUNT(incidents.id) as assigned_incidents'))
            ->groupBy('users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.role', 'users.remember_token', 'users.created_at', 'users.updated_at');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'ILIKE', '%' . $search . '%')
                  ->orWhere('users.email', 'ILIKE', '%' . $search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('users.role', $request->role);
        }

        $users = $query->orderBy('users.name')
            ->paginate(10)
            ->withQueryString();

        return view('pages.opssight.user-management.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.opssight.user-management.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:ADMIN,OPERATOR'],
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'CREATE_USER',
            'table_name' => 'users',
            'record_id' => $userId,
            'old_values' => null,
            'new_values' => json_encode([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        // Calculate stats
        $totalAssigned = DB::table('incidents')->where('assigned_to', $id)->count();
        $openIncidents = DB::table('incidents')->where('assigned_to', $id)->whereIn('status', ['OPEN', 'IN_PROGRESS'])->count();
        $resolvedIncidents = DB::table('incidents')->where('assigned_to', $id)->whereIn('status', ['RESOLVED', 'CLOSED'])->count();

        // Recent activities
        $logs = DB::table('audit_logs')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('pages.opssight.user-management.show', [
            'user' => $user,
            'totalAssigned' => $totalAssigned,
            'openIncidents' => $openIncidents,
            'resolvedIncidents' => $resolvedIncidents,
            'logs' => $logs,
        ]);
    }

    public function edit($id)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        return view('pages.opssight.user-management.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role' => ['required', 'in:ADMIN,OPERATOR'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::defaults()];
        }

        $request->validate($rules);

        // Prevent admin from changing their own role to OPERATOR
        if (auth()->id() == $id && $request->role !== 'ADMIN' && $user->role === 'ADMIN') {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($updateData);

        // Check if role changed
        if ($user->role !== $request->role) {
            DB::table('audit_logs')->insert([
                'user_id' => auth()->id(),
                'action' => 'CHANGE_ROLE',
                'table_name' => 'users',
                'record_id' => $id,
                'old_values' => json_encode(['role' => $user->role]),
                'new_values' => json_encode(['role' => $request->role]),
                'ip_address' => $request->ip(),
                'created_at' => now(),
            ]);
        }

        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'UPDATE_USER',
            'table_name' => 'users',
            'record_id' => $id,
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        if (auth()->user()->role !== 'ADMIN') {
            abort(403, 'Unauthorized action.');
        }

        $user = DB::table('users')->where('id', $id)->first();

        if (!$user) {
            abort(404);
        }

        // Prevent admin from deleting themselves
        if (auth()->id() == $id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        // Check for related incidents
        $relatedIncidents = DB::table('incidents')
            ->where('reported_by', $id)
            ->orWhere('assigned_to', $id)
            ->count();

        if ($relatedIncidents > 0) {
            return redirect()->route('users.index')->with('error', "Cannot delete {$user->name} as they are related to {$relatedIncidents} incident(s).");
        }

        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'DELETE_USER',
            'table_name' => 'users',
            'record_id' => $id,
            'old_values' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]),
            'new_values' => null,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
