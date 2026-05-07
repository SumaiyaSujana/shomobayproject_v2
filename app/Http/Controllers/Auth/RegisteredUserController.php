<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\NeighborProfile;
use App\Models\User;
use App\Models\VendorProfile;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a new registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'in:neighbor,vendor'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'role' => $request->role,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        Wallet::create([
            'user_id' => $user->id,
            'balance_paisa' => 0,
            'escrow_paisa' => 0,
        ]);

        if ($user->isNeighbor()) {
            NeighborProfile::create([
                'user_id' => $user->id,
            ]);
        }

        if ($user->isVendor()) {
            VendorProfile::create([
                'user_id' => $user->id,
                'is_verified' => false,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}