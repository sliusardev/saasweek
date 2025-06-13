<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\DataTableTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    use DataTableTrait;
    public function users(Request $request)
    {
        $query = User::query()->with('roles');

        // Define searchable and sortable columns
        $searchableColumns = ['name', 'email'];
        $sortableColumns = ['id', 'name', 'email', 'phone', 'created_at'];

        // Apply data table filters, sorting, and pagination
        $users = $this->applyDataTableFilters(
            $query,
            $request,
            $searchableColumns,
            $sortableColumns
        );

        // Format data for frontend
        $users = $this->formatDataTableResults($users, function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'created_at' => $user->created_at->format('Y-m-d'),
                'roles' => $user->getRolesNames(),
            ];
        });

        return Inertia::render('users/List', ['users' => $users]);
    }
}
