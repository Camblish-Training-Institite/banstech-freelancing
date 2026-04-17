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
            'theme' => 'required|in:default,ocean,sunset',
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
                'name' => 'Banstech Default',
                'description' => 'Deep indigo and green tones that match the current product identity.',
                'swatches' => ['#182340', '#3f67d8', '#1fa97a', '#f5f7fb'],
            ],
            [
                'key' => 'ocean',
                'name' => 'Ocean Slate',
                'description' => 'Cool teal and blue accents with a softer dashboard backdrop.',
                'swatches' => ['#102a43', '#0f766e', '#2563eb', '#ecfeff'],
            ],
            [
                'key' => 'sunset',
                'name' => 'Sunset Ember',
                'description' => 'Warm coral and amber accents for a brighter, energetic workspace.',
                'swatches' => ['#4a1d1f', '#dd6b20', '#c2410c', '#fff7ed'],
            ],
        ];
    }
}
