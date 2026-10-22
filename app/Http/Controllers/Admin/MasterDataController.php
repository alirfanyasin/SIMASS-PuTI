<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CampusLocation;
use App\Models\ServiceCatalog;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MasterDataController extends Controller
{
    public function index(): View
    {
        $units = Unit::withCount('users')->orderBy('name')->get();
        $locations = CampusLocation::with(['parent', 'children'])->orderBy('type')->orderBy('name')->get();
        $services = ServiceCatalog::orderBy('category')->orderBy('name')->get();

        return view('pages.admin.master-data.index', compact('units', 'locations', 'services'));
    }

    // Units
    public function storeUnit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', 'unique:units,code'],
        ]);

        $unit = Unit::create($validated);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'unit.created',
            'model_type' => Unit::class,
            'model_id' => $unit->id,
            'new_values' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.master-data.index', ['tab' => 'units'])->with('success', "Unit {$unit->name} berhasil ditambahkan!");
    }

    public function destroyUnit(Unit $unit): RedirectResponse
    {
        $name = $unit->name;
        $unit->delete();

        return redirect()->route('admin.master-data.index', ['tab' => 'units'])->with('success', "Unit {$name} berhasil dihapus.");
    }

    // Locations
    public function storeLocation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:campus_locations,id'],
            'type' => ['required', 'in:building,floor,room'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
        ]);

        $loc = CampusLocation::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'locations'])->with('success', "Lokasi {$loc->name} ({$loc->type}) berhasil ditambahkan!");
    }

    public function destroyLocation(CampusLocation $location): RedirectResponse
    {
        $name = $location->name;
        $location->delete();

        return redirect()->route('admin.master-data.index', ['tab' => 'locations'])->with('success', "Lokasi {$name} berhasil dihapus.");
    }

    // Service Catalog & SLA
    public function storeService(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'sla_resolution_hours' => ['required', 'integer', 'min:1'],
        ]);

        $service = ServiceCatalog::create($validated);

        return redirect()->route('admin.master-data.index', ['tab' => 'services'])->with('success', "Layanan {$service->name} (SLA {$service->sla_resolution_hours} jam) berhasil disimpan!");
    }

    public function destroyService(ServiceCatalog $service): RedirectResponse
    {
        $name = $service->name;
        $service->delete();

        return redirect()->route('admin.master-data.index', ['tab' => 'services'])->with('success', "Layanan {$name} berhasil dihapus.");
    }
}
