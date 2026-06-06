<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Display the login access portal view.
     */
    public function showLogin()
    {
        // If user is already authenticated, redirect them automatically to their proper workspace
        if (Auth::check()) {
            $role = strtolower(trim(Auth::user()->role));
            if ($role === 'veterinarian' || $role === 'vet') {
                return redirect('/vet_dashboard');
            }
            return redirect('/user_dashboard');
        }

        if (view()->exists('auth.login')) {
            return view('auth.login');
        }
        return view('login');
    }

    /**
     * Handle an inbound login authentication request (via web.php).
     */
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required', 'string'],
            // FIXED: Added 'Regular Pet Owner' to allow your HTML workspace selection form options to pass validation
            'workspace' => ['required', Rule::in(['Regular Pet Owner', 'User', 'Veterinarian'])],
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            $selectedWorkspace = strtolower(trim($credentials['workspace'])); 
            $databaseRole = strtolower(trim($user->role)); 

            // FIXED: Hardened Validation checks against ENUM definitions 'Regular Pet Owner' and 'User' strings
            if ($selectedWorkspace === 'regular pet owner' || $selectedWorkspace === 'user') {
                // If they chose the Client workspace but their database account is a Vet, throw an access block error
                if ($databaseRole === 'veterinarian' || $databaseRole === 'vet') {
                    Auth::logout();
                    return back()->withInput()->withErrors(['email' => 'The selected workspace is invalid for this medical account.']);
                }
            } else {
                // Workspace is Veterinarian: block regular users from entering administrative dashboards
                if ($databaseRole !== 'veterinarian' && $databaseRole !== 'vet') {
                    Auth::logout();
                    return back()->withInput()->withErrors(['email' => 'Access Denied. This account does not possess clinical privileges.']);
                }
            }

            // Regenerate session ID to mitigate session fixation hijacking vectors
            $request->session()->regenerate();
            
            if ($databaseRole === 'veterinarian' || $databaseRole === 'vet') {
                return redirect('/vet_dashboard');
            } 
            
            return redirect('/user_dashboard');
        }

        return back()->withInput()->withErrors(['email' => 'Invalid credentials or matching workspace selection.']);
    }

    /**
     * Handle an inbound AJAX registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8'],
            'role'      => ['required', Rule::in(['Regular Pet Owner', 'Veterinarian', 'User'])],
        ]);

        $user = User::create([
            'full_name' => $validated['full_name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'], 
        ]);

        Auth::login($user);

        // Regenerate and save web session state variables
        $request->session()->regenerate();
        $request->session()->put('auth.password_confirmed_at', time());
        $request->session()->save();

        // Check if user is registered as a veterinarian to supply the matching route endpoint redirection path
        $checkRole = strtolower(trim($user->role));
        $redirectTo = ($checkRole === 'veterinarian' || $checkRole === 'vet') ? url('/vet_dashboard') : url('/user_dashboard');

        return response()->json([
            'status'   => 'success',
            'message'  => 'Account created successfully!',
            'redirect' => $redirectTo
        ]);
    }

    /**
     * Log the user out of the application session and send them back to the login page.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // FIXED: Explicitly targeting named route wrapper to guarantee clean layout fallbacks
        return redirect()->route('login');
    }

    /**
     * Fetch all users flagged under the Veterinarian role.
     */
    public function getVeterinarians() 
    {
        $vets = User::where('role', 'Veterinarian')
                    ->orWhere('role', 'vet')
                    ->orderBy('full_name', 'asc')
                    ->get(['user_id', 'full_name']);
                    
        return response()->json(['success' => true, 'veterinarians' => $vets]);
    }
}