<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCarController extends Controller
{
    public function index()
    {
        $cars = Car::latest()->paginate(15);
        return view('admin.cars.index', compact('cars'));
    }

    public function create()
    {
        return view('admin.cars.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:1',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'rarity'      => 'nullable|in:common,rare,epic,legendary,iconic', // <-- ADDED
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file     = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('cars', $filename, 'public');
            $data['image'] = $filename;
        }

        $data['is_available'] = $request->has('is_available');
        $data['rarity']       = $request->input('rarity', 'common');  // <-- ADDED
        $data['is_featured']  = $request->boolean('is_featured');      // <-- ADDED

        $car = Car::create($data);
        AuditLogService::log('CAR_CREATED', 'Cars', "Car added: {$car->name}");

        return redirect()->route('admin.cars.index')
            ->with('success', 'Car added successfully.');
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'price'       => 'required|numeric|min:1',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'rarity'      => 'nullable|in:common,rare,epic,legendary,iconic', // <-- ADDED
        ]);

        $old = $car->toArray();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($car->image && Storage::disk('public')->exists('cars/' . $car->image)) {
                Storage::disk('public')->delete('cars/' . $car->image);
            }

            $file     = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('cars', $filename, 'public');
            $data['image'] = $filename;
        } else {
            $data['image'] = $car->image;
        }

        $data['is_available'] = $request->has('is_available');
        $data['rarity']       = $request->input('rarity', 'common');  // <-- ADDED
        $data['is_featured']  = $request->boolean('is_featured');      // <-- ADDED

        $car->update($data);
        AuditLogService::log('CAR_UPDATED', 'Cars', "Car updated: {$car->name}", $old, $car->toArray());

        return redirect()->route('admin.cars.index')
            ->with('success', 'Car updated successfully.');
    }

    public function destroy(Car $car)
    {
        if ($car->image && Storage::disk('public')->exists('cars/' . $car->image)) {
            Storage::disk('public')->delete('cars/' . $car->image);
        }

        $name = $car->name;
        $car->delete();
        AuditLogService::log('CAR_DELETED', 'Cars', "Car deleted: {$name}");

        return back()->with('success', 'Car deleted.');
    }
}