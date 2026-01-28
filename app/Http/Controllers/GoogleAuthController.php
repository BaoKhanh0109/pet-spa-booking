<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    // ==================== WEB METHODS ====================
    
    /**
     * Redirect to Google authentication page
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google authentication callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Find or create user
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // User exists, update google_id if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            } else {
                // Create new user
                $user = new User();
                $user->name = $googleUser->getName();
                $user->email = $googleUser->getEmail();
                $user->google_id = $googleUser->getId();
                $user->password = Hash::make(Str::random(24));
                $user->role = 'user';
                $user->phone = ''; // Required field, set empty for Google users
                $user->address = ''; // Set empty address
                $user->save();
            }
            
            // Login the user
            Auth::login($user);
            
            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại: ' . $e->getMessage());
        }
    }
    
    // ==================== API METHODS ====================
    
    /**
     * API: Get Google OAuth redirect URL
     */
    public function apiGetRedirectUrl()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
        
        return response()->json([
            'success' => true,
            'data' => [
                'url' => $url
            ],
            'message' => 'Lấy URL đăng nhập Google thành công'
        ]);
    }

    /**
     * API: Handle Google authentication callback
     */
    public function apiHandleCallback(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(24)),
                    'role' => 'user',
                    'phone' => '',
                    'address' => '',
                ]);
            }
            
            $token = $user->createToken('google-auth-token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
                'message' => 'Đăng nhập Google thành công!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đăng nhập Google thất bại: ' . $e->getMessage()
            ], 401);
        }
    }
}
