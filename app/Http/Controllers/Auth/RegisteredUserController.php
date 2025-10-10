<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
   public function create(): View
{
    return view('auth.register', [
        'roles' => array_filter(UserRole::cases(), function($role) {
            return $role !== UserRole::ADMIN;
        })
]);
}

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:'.implode(',', UserRole::values())],
        ]);

        // Déterminer si l'utilisateur doit être approuvé automatiquement
        $role = UserRole::from($request->role);
        $isAutoApproved = $role !== UserRole::CASSE;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'approved' => $isAutoApproved, // true pour client/admin, false pour casse
            'approved_at' => $isAutoApproved ? now() : null, // now() pour client/admin, null pour casse
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Si c'est une casse non approuvée, rediriger vers une page d'attente
        if ($role === UserRole::CASSE && !$user->approved) {
            Auth::logout();
            return redirect()->route('auth.pending-approval')->with('info',
                'Votre compte a été créé avec succès. Il est en attente d\'approbation par un administrateur. Vous recevrez un email une fois votre compte validé.'
            );
        }

        return redirect()->intended(route('dashboard'));
    }

    protected function redirectTo(): string
    {
        $user = Auth::user();

        return match($user->role) {
            UserRole::CASSE => '/casse/dashboard',
            UserRole::CLIENT => '/client/dashboard',
            UserRole::ADMIN => '/admin/dashboard',
            default => '/dashboard'
        };
    }
}




