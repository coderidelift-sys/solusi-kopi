<?php

namespace App\Http\Controllers\Console;

use App\DataTables\SchoolDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreSchool;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolsController extends Controller
{
    public function index(SchoolDataTable $dataTable)
    {
        return $dataTable
            ->render('console.schools.index');
    }

    public function create()
    {
        return view('console.schools.create');
    }

    public function store(RequestStoreSchool $request)
    {
        DB::beginTransaction();
        try {
            $schoolPayload = $request->validated();

            if ($request->hasFile('logo')) {
                $schoolPayload['logo'] = handleUpload('logo', 'upload/schools');
            }

            School::create($schoolPayload);

            DB::commit();
            return redirect()->route('schools.index')->with('success', 'School created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to create school.');
        }
    }

    public function edit(School $school)
    {
        return view('console.schools.edit', compact('school'));
    }

    public function update(RequestStoreSchool $request, School $school)
    {
        DB::beginTransaction();
        try {
            $schoolPayload = $request->validated();

            if ($request->hasFile('logo')) {
                deleteFileIfExist($school->logo);
                $schoolPayload['logo'] = handleUpload('logo', 'upload/schools');
            }

            $school->update($schoolPayload);

            DB::commit();
            return redirect()->route('schools.index')->with('success', 'School updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to update school.');
        }
    }

    public function destroy(School $school)
    {
        DB::beginTransaction();
        try {
            deleteFileIfExist($school->logo);
            $school->delete();

            DB::commit();
            return redirect()->route('schools.index')->with('success', 'School deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to delete school.');
        }
    }
}
