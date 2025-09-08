<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Google\Cloud\Core\Exception\NotFoundException;

class AuthController extends Controller
{
    protected $firebase;

    public function __construct()
    {

    }

    public function removeAccount(Request $request){
        $request->validate([
            'reference' => 'required|string',
        ]);

        try {
            $firestoreDB = app('firebase.firestore')->database();
            $firestoreDB->collection('users')->document($request->reference)->delete();
            
            return response()->json([
                'message' => 'Document deleted successfully',
            ], 200);
        } catch (NotFoundException $e) {
            return response()->json([
                'error' => 'Document not found',
            ], 404);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to delete document: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function register(AdminRegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $token = $user->createToken('auth-token')->accessToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed: ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            if($user->status != 1){
                throw ValidationException::withMessages([
                    'validation' => ['Account needs approval.'],
                ]);
            }

            // $firebaseUser = $this->firebase->getUserByEmail($request->email);
            // if (!$firebaseUser) {
            //     throw ValidationException::withMessages([
            //         'email' => ['Firebase authentication failed.'],
            //     ]);
            // }

            Auth::login($user);
            $token = $user->createToken('auth-token')->accessToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed: ' . $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function checkUser()
    {
        return response()->json(['result' => Auth::check()], 200);
    }
}