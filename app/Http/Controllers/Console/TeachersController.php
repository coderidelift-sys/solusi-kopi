<?php

namespace App\Http\Controllers\Console;

use App\DataTables\Scopes\ByRelationToSchoolScope;
use App\DataTables\Scopes\TeacherRelationToSchoolScope;
use App\DataTables\TeacherDataTable;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreTeacher;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class TeachersController extends Controller
{
    public function index(Request $request, TeacherDataTable $dataTable)
    {
        $schools = School::whereHas('admins', function ($query) {
            $query->where('admin_id', auth()->id());
        })->get();

        return $dataTable
            ->addScope(new TeacherRelationToSchoolScope($request))
            ->render('console.teachers.index', compact('schools'));
    }

    public function create()
    {
        $classes = ClassRoom::withWhereHas('school.admins', function ($query) {
            $query->where('admin_id', auth()->id());
        })->get()->map(function ($class) {
            return [
                'id' => $class->id,
                'name' => $class->name,
                'school_name' => $class->school->name,
            ];
        })->groupBy('school_name')->map(function ($class) {
            return [
                'school_name' => $class->first()['school_name'],
                'classes' => $class,
            ];
        })->values();

        return view('console.teachers.create', compact('classes'));
    }

    public function store(RequestStoreTeacher $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole(UserRole::WALIKELAS);
            $user->teachers()->create([
                'nip' => $request->nip,
                'class_id' => $request->class_id,
            ]);

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to create teacher.');
        }
    }

    public function edit($id)
    {
        DB::beginTransaction();
        try {
            $classes = ClassRoom::withWhereHas('school.admins', function ($query) {
                $query->where('admin_id', auth()->id());
            })->get()->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'school_name' => $class->school->name,
                ];
            })->groupBy('school_name')->map(function ($class) {
                return [
                    'school_name' => $class->first()['school_name'],
                    'classes' => $class,
                ];
            })->values();
            $teacher = Teacher::with('user')->findOrFail($id);

            DB::commit();
            return view('console.teachers.edit', compact('teacher', 'classes'));
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Teacher not found.');
        }
    }

    public function update(RequestStoreTeacher $request, $id)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);
            $user = User::findOrFail($teacher->user_id);
            $userPayload = [
                'name' => $request->name,
                'email' => $request->email,
            ];
            if ($request->password) {
                $userPayload['password'] = bcrypt($request->password);
            }
            $user->update($userPayload);

            $user->teachers()->update([
                'nip' => $request->nip,
                'class_id' => $request->class_id,
            ]);

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to update teacher.');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);
            $user = User::findOrFail($teacher->user_id);
            $user->delete();
            $teacher->delete();

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to delete teacher.');
        }
    }
}
