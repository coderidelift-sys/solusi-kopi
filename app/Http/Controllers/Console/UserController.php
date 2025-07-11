<?php

namespace App\Http\Controllers\Console;

use App\DataTables\Scopes\UserRoleScope;
use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreUser;
use App\Http\Requests\RequestUserDetailUpdate;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Import DB facade
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request, UserDataTable $dataTable)
    {
        $roles = Role::select('id', 'name')->get();

        return $dataTable
            ->addScope(new UserRoleScope($request->input('role_id')))
            ->render('console.users.index', compact('roles'));
    }

    public function store(RequestStoreUser $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated(); // Get validated data

            // Handle avatar upload for store if needed (currently not in UI, but rule exists)
            if ($request->hasFile('file_avatar')) {
                $data['avatar'] = handleUpload('file_avatar', '/avatars');
            }

            $user = User::create($data);
            $user->assignRole(Role::find($request->role_id));

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to create user.');
        }
    }

    public function show(User $user)
    {
        try {
            $roles = Role::select('id', 'name')->get();
            $user->role_id = optional($user->roles->first())->id;

            return view('console.users.show', compact('user', 'roles'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return back()->with('error', 'User not found.');
        }
    }

    public function edit(User $user)
    {
        try {
            $roles = Role::select('id', 'name')->get();
            $user->role_id = optional($user->roles->first())->id;

            return view('console.users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return back()->with('error', 'User not found.');
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated(); // Get validated data

            if ($request->password) {
                $data['password'] = bcrypt($request->password);
            }

            if ($request->hasFile('file_avatar')) {
                // Delete old avatar if exists and not default
                if ($user->avatar && $user->avatar !== '/avatars/1.png') {
                    Storage::disk('public')->delete($user->avatar);
                }
                $data['avatar'] = handleUpload('file_avatar', '/avatars');
            } elseif (isset($data['remove_avatar']) && $data['remove_avatar'] == 1) {
                if ($user->avatar && $user->avatar !== '/avatars/1.png') {
                    Storage::disk('public')->delete($user->avatar);
                }
                $data['avatar'] = null; // Set avatar to null if removed
            }

            $user->update($data);
            $user->syncRoles(Role::find($request->role_id));

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to update user.');
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            if ($user->avatar && $user->avatar != '/avatars/1.png') {
                File::delete(public_path($user->avatar));
            }

            $user->delete();

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to delete user.');
        }
    }
}
