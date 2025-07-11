<?php

namespace App\Http\Controllers\Console;

use App\DataTables\ClassRoomDataTable;
use App\DataTables\Scopes\ByRelationToSchoolScope;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreClassRoom;
use App\Models\ClassRoom;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassesController extends Controller
{
    public function index(ClassRoomDataTable $dataTable)
    {
        $schools = School::whereHas('admins', function ($query) {
            $query->where('admin_id', auth()->id());
        })->get();

        return $dataTable
            ->addScope(new ByRelationToSchoolScope(request()))
            ->render('console.classrooms.index', compact('schools'));
    }

    public function create()
    {
        $schools = School::whereHas('admins', function ($query) {
            $query->where('admin_id', auth()->id());
        })->get();

        return view('console.classrooms.create', compact('schools'));
    }

    public function store(RequestStoreClassRoom $request)
    {
        DB::beginTransaction();
        try {
            $classRoomPayload = $request->validated();

            ClassRoom::create($classRoomPayload);

            DB::commit();
            return redirect()->route('classes.index')->with('success', 'Class room created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to create ClassRoom.');
        }
    }

    public function edit(ClassRoom $classRoom)
    {
        $schools = School::whereHas('admins', function ($query) {
            $query->where('admin_id', auth()->id());
        })->get();

        return view('console.classrooms.edit', compact('classRoom', 'schools'));
    }

    public function update(RequestStoreClassRoom $request, ClassRoom $classRoom)
    {
        DB::beginTransaction();
        try {
            $classRoomPayload = $request->validated();
            $classRoom->update($classRoomPayload);

            DB::commit();
            return redirect()->route('classes.index')->with('success', 'Class room updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to update ClassRoom.');
        }
    }

    public function destroy(ClassRoom $classRoom)
    {
        DB::beginTransaction();
        try {
            $classRoom->delete();

            DB::commit();
            return redirect()->route('classes.index')->with('success', 'Class room deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to delete ClassRoom.');
        }
    }
}
