<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Http\Requests\StoreOutletRequest;
use Illuminate\Http\Request;
use App\DataTables\OutletDataTable;
use Illuminate\Support\Facades\Storage;

class OutletController extends Controller
{
    public function index(OutletDataTable $dataTable)
    {
        return $dataTable->render('console.outlets.index');
    }

    public function create()
    {
        return view('console.outlets.create');
    }

    public function store(StoreOutletRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = handleUpload('logo', 'outlets/logos');
        }

        Outlet::create($data);

        return redirect()->route('outlets.index')->with('success', 'Outlet created successfully.');
    }

    public function show(Outlet $outlet)
    {
        return view('console.outlets.show', compact('outlet'));
    }

    public function edit(Outlet $outlet)
    {
        $outlet->parsed_opening_hours = collect(explode(',', $outlet->opening_hours))
            ->mapWithKeys(function ($item) {
                $item = trim($item);

                // Skip jika tidak ada `:` atau `-`
                if (!str_contains($item, ':') || !str_contains($item, '-')) {
                    return [];
                }

                [$day, $times] = explode(':', $item, 2); // limit = 2 to avoid explode errors
                [$open, $close] = array_map('trim', explode('-', $times, 2));

                // Validasi minimal format jam ada
                if (empty($open) || empty($close)) {
                    return [];
                }

                return [strtolower($day) => [
                    'active' => true,
                    'open' => $open,
                    'close' => $close,
                ]];
            })
            ->toArray();

        return view('console.outlets.edit', compact('outlet'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'remove_logo' => 'nullable|boolean',
            'opening_hours' => 'nullable|array',
        ]);

        $this->validateOpeningHours($request->input('opening_hours', []));

        $formattedOpeningHours = collect($request->input('opening_hours', []))
            ->filter(fn($data) => isset($data['active']))
            ->map(function ($data, $day) {
                return [
                    'day' => $day,
                    'open_time' => $data['open'],
                    'close_time' => $data['close'],
                ];
            })->values();

        $openingHoursString = collect($formattedOpeningHours)
            ->map(fn($item) => "{$item['day']}: {$item['open_time']} - {$item['close_time']}")
            ->implode(', ');

        $payload = $validated;
        $payload['opening_hours'] = $openingHoursString;

        if ($request->hasFile('logo')) {
            $payload['logo'] = handleUpload('logo', 'outlets/logos', $outlet->logo);
        } elseif ($request->boolean('remove_logo') && $outlet->logo) {
            Storage::disk('public')->delete($outlet->logo);
            $payload['logo'] = null;
        }

        $outlet->update($payload);

        return redirect()->route('outlets.index')->with('success', 'Outlet updated successfully.');
    }

    private function validateOpeningHours(array $hours): void
    {
        foreach ($hours as $day => $data) {
            if (isset($data['active']) && (empty($data['open']) || empty($data['close']))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    "opening_hours.{$day}" => "Jam buka dan tutup untuk {$day} wajib diisi.",
                ]);
            }
        }
    }


    public function destroy(Outlet $outlet)
    {
        if ($outlet->logo) {
            Storage::disk('public')->delete($outlet->logo);
        }
        $outlet->delete();

        return redirect()->route('outlets.index')->with('success', 'Outlet deleted successfully.');
    }
}
