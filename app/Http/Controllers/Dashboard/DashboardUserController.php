<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UserStoreRequest;
use App\Http\Requests\Dashboard\UserUpdateRequest;
use App\Http\Resources\Dashboard\UserResource;
use App\Http\Resources\Dashboard\UserCollection;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardUserController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Filter by status (active/inactive)
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->where('active', true);
            } elseif ($status === 'inactive') {
                $query->where('active', false);
            }
        }

        // Order by creation date (newest first)
        $query->orderBy('created_at', 'desc');

        $users = $query->get();

        return response()->json([
            'data' => UserResource::collection($users),
            'message' => 'Users retrieved successfully.'
        ]);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);

        // Set active to false for pending approval by default
        $validated['active'] = $validated['active'] ?? false;

        $user = User::create($validated);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User created successfully.'
        ], 201);
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User retrieved successfully.'
        ]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        // Hash password if provided
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User updated successfully.'
        ]);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deletion of the currently authenticated user
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'You cannot delete your own account.'
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }

    /**
     * Activate a user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function activate(User $user): JsonResponse
    {
        $user->update([
            'active' => true
        ]);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User activated successfully.'
        ]);
    }

    /**
     * Deactivate a user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function deactivate(User $user): JsonResponse
    {
        // Prevent deactivation of the currently authenticated user
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'You cannot deactivate your own account.'
            ], 422);
        }

        $user->update([
            'active' => false
        ]);

        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User deactivated successfully.'
        ]);
    }
}
