<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

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
     * 
     * @OA\Get(
     *     path="/auth/google/url",
     *     summary="Lấy URL đăng nhập Google",
     *     tags={"Google Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="url", type="string", example="https://accounts.google.com/o/oauth2/...")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
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
     * 
     * @OA\Post(
     *     path="/auth/google/callback",
     *     summary="Xử lý callback từ Google",
     *     tags={"Google Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"code"},
     *             @OA\Property(property="code", type="string", description="Authorization code từ Google")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đăng nhập thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="access_token", type="string"),
     *                 @OA\Property(property="token_type", type="string", example="Bearer")
     *             ),
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Đăng nhập Google thất bại"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
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
