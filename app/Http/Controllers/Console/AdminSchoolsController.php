<?php

namespace App\Http\Controllers\Console;

use App\DataTables\AdminSchoolDataTable;
use App\DataTables\Scopes\AdminSchoolScope;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreAdminSchool;
use App\Models\AdminSchool;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSchoolsController extends Controller
{
    public function index(Request $request, AdminSchoolDataTable $dataTable)
    {
        $schools = School::all();
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', UserRole::ADMIN);
        })->get();

        return $dataTable
            ->addScope(new AdminSchoolScope($request))
            ->render('console.admin_schools.index', compact('schools', 'admins'));
    }

    public function create()
    {
        $schools = School::all();
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', UserRole::ADMIN);
        })->get();

        return view('console.admin_schools.create', compact('schools', 'admins'));
    }

    public function store(RequestStoreAdminSchool $request)
    {
        $school = School::findOrFail($request->school_id);
        $admin = User::findOrFail($request->admin_id);

        if ($school->admins()->where('admin_id', $admin->id)->exists()) {
            return redirect()->back()->with('error', 'Admin is already assigned to this school.')->withInput()->withErrors([
                'admin_id' => 'Admin is already assigned to this school.',
            ]);
        }

        $school->admins()->attach($admin);

        return redirect()->route('adminschools.index')->with('success', 'Admin assigned to school successfully.');
    }

    public function edit(AdminSchool $adminSchool)
    {
        $schools = School::all();
        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', UserRole::ADMIN);
        })->get();

        return view('console.admin_schools.edit', compact('adminSchool', 'schools', 'admins'));
    }

    public function update(RequestStoreAdminSchool $request, AdminSchool $adminSchool)
    {
        $school = School::findOrFail($request->school_id);
        $admin = User::findOrFail($request->admin_id);

        if ($adminSchool->school_id !== $school->id || $adminSchool->admin_id !== $admin->id) {
            if ($school->admins()->where('admin_id', $admin->id)->exists()) {
                return redirect()->back()->with('error', 'Admin is already assigned to this school.')->withInput()->withErrors([
                    'admin_id' => 'Admin is already assigned to this school.',
                ]);
            }

            $adminSchool->update([
                'school_id' => $school->id,
                'admin_id' => $admin->id,
            ]);
        }

        return redirect()->route('adminschools.index')->with('success', 'Admin updated successfully.');
    }

    public function destroy(AdminSchool $adminSchool)
    {
        $adminSchool->delete();

        return redirect()->route('adminschools.index')->with('success', 'Admin removed from school successfully.');
    }
}
