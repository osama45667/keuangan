<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('theme_remove')) {
            if ($request->user()->theme_bg_path) {
                Storage::disk('public')->delete($request->user()->theme_bg_path);
            }
            $data['theme_bg_path'] = null;
        }

        if ($request->hasFile('theme_background')) {
            if ($request->user()->theme_bg_path) {
                Storage::disk('public')->delete($request->user()->theme_bg_path);
            }
            $data['theme_bg_path'] = $request->file('theme_background')->store('themes', 'public');
        }

        $request->user()->fill($data);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        $response = Redirect::route('profile.edit')->with('status', 'profile-updated')->with('bg_ts', time());

        // Also set cookie so cache-bust works across ALL pages
        $response->withCookie(cookie('bg_ts', time(), 60 * 24 * 365));

        $cookieMinutes = 60 * 24 * 365;
        if ($request->boolean('theme_remove') || !$request->user()->theme_bg_path) {
            $response->withCookie(cookie()->forget('theme_bg_path'));
            $response->withCookie(cookie()->forget('theme_bg_size'));
            $response->withCookie(cookie()->forget('theme_overlay'));
            $response->withCookie(cookie()->forget('bg_ts'));
        } else {
            $response->withCookie(cookie('theme_bg_path', $request->user()->theme_bg_path, $cookieMinutes));
            $response->withCookie(cookie('theme_bg_size', $request->user()->theme_bg_size ?? 'cover', $cookieMinutes));
            $response->withCookie(cookie('theme_overlay', $request->user()->theme_overlay ?? 'auto', $cookieMinutes));
        }

        return $response;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
