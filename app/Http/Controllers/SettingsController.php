<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $context = $this->resolveContext($request);

        return view('settings.edit', [
            'user' => $context['user'],
            'layout' => $context['layout'],
            'settingsRouteName' => $context['settingsRouteName'],
            'dashboardRouteName' => $context['dashboardRouteName'],
            'pageTitle' => $context['pageTitle'],
            'themes' => $this->themes(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $context = $this->resolveContext($request);

        $validated = $request->validate([
            'theme' => 'required|in:default,dark,high-contrast',
        ]);

        $context['user']->forceFill([
            'theme' => $validated['theme'],
        ])->save();

        return redirect()
            ->route($context['settingsRouteName'])
            ->with('status', 'theme-updated');
    }

    protected function resolveContext(Request $request): array
    {
        $routeName = $request->route()?->getName() ?? '';

        if (str_starts_with($routeName, 'admin.')) {
            return [
                'user' => Auth::guard('admin')->user(),
                'layout' => 'layouts.admin',
                'settingsRouteName' => 'admin.settings.edit',
                'dashboardRouteName' => 'admin.dashboard',
                'pageTitle' => 'Admin Settings',
            ];
        }

        if (str_starts_with($routeName, 'pm.')) {
            return [
                'user' => $request->user(),
                'layout' => 'layouts.project-manager',
                'settingsRouteName' => 'pm.settings.edit',
                'dashboardRouteName' => 'pm.dashboard',
                'pageTitle' => 'Project Manager Settings',
            ];
        }

        if (str_starts_with($routeName, 'client.')) {
            return [
                'user' => $request->user(),
                'layout' => 'layouts.client',
                'settingsRouteName' => 'client.settings.edit',
                'dashboardRouteName' => 'client.dashboard',
                'pageTitle' => 'Client Settings',
            ];
        }

        return [
            'user' => $request->user(),
            'layout' => 'layouts.freelancer',
            'settingsRouteName' => 'freelancer.settings.edit',
            'dashboardRouteName' => 'freelancer.dashboard',
            'pageTitle' => 'Freelancer Settings',
        ];
    }

    protected function themes(): array
    {
        return [
            [
                'key' => 'default',
                'name' => 'Default',
                'description' => 'The standard Banstech workspace with bright surfaces and colored navigation.',
                'swatches' => ['#182340', '#3f67d8', '#1fa97a', '#f5f7fb'],
            ],
            [
                'key' => 'dark',
                'name' => 'Dark Mode',
                'description' => 'A darker interface for lower-glare project and admin work.',
                'swatches' => ['#070b13', '#111827', '#22c55e', '#e5e7eb'],
            ],
            [
                'key' => 'high-contrast',
                'name' => 'High Contrast',
                'description' => 'Maximum separation between text, controls, and page regions.',
                'swatches' => ['#000000', '#ffffff', '#ffd400', '#00e5ff'],
            ],
        ];
    }
}
