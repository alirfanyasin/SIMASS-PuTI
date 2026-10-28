<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PortalModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PortalModuleController extends Controller
{
    /**
     * Display a listing of portal modules.
     */
    public function index(): View
    {
        $modules = PortalModule::ordered()->get();
        $roles = Role::pluck('name');

        return view('pages.admin.modules.index', compact('modules', 'roles'));
    }

    /**
     * Store a newly created portal module.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:portal_modules,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'credit' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'in:red,blue,emerald,purple,amber,cyan,rose,indigo,dark'],
            'status' => ['required', 'in:active,coming_soon,maintenance'],
            'order' => ['required', 'integer', 'min:0'],
            'link_type' => ['nullable', 'in:route,url,none'],
            'route_name' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'max:2048'],
            'allowed_roles' => ['nullable', 'array'],
            'allowed_roles.*' => ['string'],
        ]);

        $validated['color'] = $validated['color'] ?? 'red';

        if (($validated['link_type'] ?? null) === 'url') {
            $validated['route_name'] = null;
        } elseif (($validated['link_type'] ?? null) === 'route') {
            $validated['url'] = null;
        } elseif (($validated['link_type'] ?? null) === 'none') {
            $validated['route_name'] = null;
            $validated['url'] = null;
        }
        unset($validated['link_type']);

        if (empty($validated['allowed_roles'])) {
            $validated['allowed_roles'] = null;
        }

        $module = PortalModule::create($validated);

        return back()->with('success', "Modul {$module->name} berhasil ditambahkan!");
    }

    /**
     * Update the specified portal module.
     */
    public function update(Request $request, PortalModule $module): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('portal_modules', 'code')->ignore($module->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'credit' => ['nullable', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'in:red,blue,emerald,purple,amber,cyan,rose,indigo,dark'],
            'status' => ['required', 'in:active,coming_soon,maintenance'],
            'order' => ['required', 'integer', 'min:0'],
            'link_type' => ['nullable', 'in:route,url,none'],
            'route_name' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'string', 'max:2048'],
            'allowed_roles' => ['nullable', 'array'],
            'allowed_roles.*' => ['string'],
        ]);

        $validated['color'] = $validated['color'] ?? 'red';

        if (($validated['link_type'] ?? null) === 'url') {
            $validated['route_name'] = null;
        } elseif (($validated['link_type'] ?? null) === 'route') {
            $validated['url'] = null;
        } elseif (($validated['link_type'] ?? null) === 'none') {
            $validated['route_name'] = null;
            $validated['url'] = null;
        }
        unset($validated['link_type']);

        if (empty($validated['allowed_roles'])) {
            $validated['allowed_roles'] = null;
        }

        $module->update($validated);

        return back()->with('success', "Modul {$module->name} berhasil diperbarui!");
    }

    /**
     * Remove the specified portal module.
     */
    public function destroy(PortalModule $module): RedirectResponse
    {
        $name = $module->name;
        $module->delete();

        return back()->with('success', "Modul {$name} berhasil dihapus.");
    }
}
