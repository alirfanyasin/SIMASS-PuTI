<?php

namespace App\Http\Controllers;

use App\Models\PortalModule;
use Database\Seeders\PortalModuleSeeder;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $modules = PortalModule::ordered()->get();
        if ($modules->isEmpty()) {
            (new PortalModuleSeeder)->run();
            $modules = PortalModule::ordered()->get();
        }

        $modules = $modules->filter(function (PortalModule $module) use ($user): bool {
            return $module->isAccessibleBy($user);
        });

        return view('pages.portal.index', compact('modules'));
    }
}
