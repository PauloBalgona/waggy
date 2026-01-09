<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\DogBreeds;


class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showSignup(Request $request)
    {
        return view('auth.signup', [
            'certificate_path' => null,
            'certificate_name' => null,
        ]);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect non-admin users only
            if (!auth()->user()->is_admin) {
                // Check if certificate was rejected
                if (auth()->user()->certificate_rejected_at) {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your certificate has been rejected by our admin team. Please upload a new certificate or contact support.',
                    ])->onlyInput('email');
                }

                if (!auth()->user()->certificate_verified) {
                    return redirect()->route('certificate');
                }
                return redirect()->route('home');
            }

            // Admin users should use admin login
            Auth::logout();
            return back()->withErrors([
                'email' => 'Please use admin login portal.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            // Only allow addresses that are exactly username@gmail.com
            'email' => ['required','email','unique:users','regex:/^[A-Za-z0-9._%+\-]+@gmail\.com$/i'],
            'password' => 'required|string|min:6',
            'pet_name' => 'required|string',
            'breedtype' => 'required|string',
            'dog_age' => 'required|numeric',
            'gendertype' => 'required|string|in:Male,Female',
            'features' => 'nullable|string',
            'certificate' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        // Validate that breed is a real dog breed
        if (!DogBreeds::isValid($request->breedtype)) {
            return back()->withErrors([
                'breedtype' => 'This platform only supports dog breeds. "' . $request->breedtype . '" is not a recognized dog breed. Please select a valid dog breed.'
            ])->onlyInput('email', 'pet_name', 'breedtype', 'dog_age', 'gendertype');
        }

        // Upload certificate file if present
        $certificatePath = null;
        if ($request->hasFile('certificate')) {
            $certificatePath = $request->file('certificate')->store('certificates', 'public');
        }

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),

            // Pet details (matching your DB)
            'pet_name' => $validated['pet_name'],
            'pet_breed' => $validated['breedtype'],
            'pet_age' => $validated['dog_age'],
            'pet_gender' => $validated['gendertype'],
            'pet_features' => $validated['features'] ?? null,

            // Certificate
            'certificate_path' => $certificatePath,
            'certificate_verified' => false,
        ]);

        Auth::login($user);

        // Redirect based on certificate verification status
        if (!$user->certificate_verified) {
            return redirect()->route('certificate');
        }

        return redirect()->route('home');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
