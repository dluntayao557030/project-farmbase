<?php

namespace App\Http\Controllers;

use App\Models\Barn;
use App\Models\BarnOwner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    public function showLogin()
    {
        $this->shareLayoutData();
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('username', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();
            $request->session()->regenerate();

            if ($user->user_type === 'barn_staff') {
                $barnStaff = $user->barnStaff()->first();

                if (!$barnStaff || $barnStaff->staff_status !== 'active') {
                    Auth::logout();
                    return back()
                        ->withInput($request->only('username'))
                        ->with('error', 'Your account has been deactivated. Please contact the barn owner.');
                }
            }

            $this->shareLayoutData();
            return $this->redirectByRole($user);
        }

        return back()
            ->withInput($request->only('username'))
            ->with('error', 'Invalid username or password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegister()
    {
        $this->shareLayoutData();
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'email'         => 'required|email|unique:users,email',
            'username'      => 'required|string|max:50|unique:users,username',
            'password'      => 'required|string|min:8|confirmed',

            'barn_name'     => 'required|string|max:150',
            'country'       => 'required|string|max:100',
            'region'        => 'required|string|max:100',
            'city'          => 'required|string|max:100',
            'farm_type'     => 'required|string|max:100',
            'permit_number' => 'required|string|max:100',
            'permit_doc'    => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $permitDocPath = null;
        if ($request->hasFile('permit_doc')) {
            $permitDocPath = $request->file('permit_doc')->store('permits', 'public');
        }

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'username'   => $validated['username'],
            'password'   => Hash::make($validated['password']),
            'user_type'  => 'barn_owner',
        ]);

        BarnOwner::create([
            'user_id' => $user->id,
            'account_status' => 'active',
        ]);

        Barn::create([
            'barn_owner_id'   => $user->id,
            'barn_name'       => $validated['barn_name'],
            'country'         => $validated['country'],
            'city'            => $validated['city'],
            'region'          => $validated['region'],
            'farm_type'       => $validated['farm_type'],
            'permit_number'   => $validated['permit_number'],
            'permit_doc_path' => $permitDocPath ?? 'not_uploaded',
        ]);

        return redirect()->route('register')
                         ->with('success', 'Registration successful! You can now login.');
    }

    protected function shareLayoutData()
    {
        $user = Auth::user();

        if (!$user) {
            View::share('currentBarn', null);
            View::share('currentUser', null);
            return;
        }

        $currentBarn = null;

        if ($user->user_type === 'barn_owner') {
            $currentBarn = Barn::where('barn_owner_id', $user->id)->first();
        } 
        elseif ($user->user_type === 'barn_staff') {
            $barnStaff = $user->barnStaff()->first();
            // Only load barn if staff is active
            if ($barnStaff && $barnStaff->staff_status === 'active') {
                $currentBarn = $barnStaff->barn;
            }
        }

        View::share('currentBarn', $currentBarn);
        View::share('currentUser', $user);
    }

    private function redirectByRole(User $user)
    {
        return match ($user->user_type) {
            'barn_owner' => redirect()->route('dashboard'),
            'barn_staff' => redirect()->route('transactions.index'),
            default      => redirect()->route('login'),
        };
    }
}