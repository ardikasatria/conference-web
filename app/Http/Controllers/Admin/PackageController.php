<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Conference;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $packages = Package::with('conference')
            ->withCount('registrations')
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->when($request->conference_id, fn($q, $c) => $q->where('conference_id', $c))
            ->orderBy('order')
            ->paginate(15)
            ->withQueryString();

        $conferences = Conference::orderBy('name')->get(['id', 'name']);

        return view('admin.packages.index', compact('packages', 'conferences'));
    }

    public function create()
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        return view('admin.packages.create', compact('conferences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_capacity' => 'nullable|integer|min:0',
            'benefits' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        if (!empty($validated['benefits'])) {
            $validated['benefits'] = array_map('trim', explode("\n", $validated['benefits']));
        }

        Package::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        $conferences = Conference::orderBy('name')->get(['id', 'name']);
        return view('admin.packages.edit', compact('package', 'conferences'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'conference_id' => 'required|exists:conferences,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'max_capacity' => 'nullable|integer|min:0',
            'benefits' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
        ]);

        if (!empty($validated['benefits'])) {
            $validated['benefits'] = array_map('trim', explode("\n", $validated['benefits']));
        }

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }
}
