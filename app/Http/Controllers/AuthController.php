<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{

    public function __construct(public AccountService $accountService) {}

    public function login(LoginRequest $request) : JsonResponse
    {
        try {
            $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::with('account')->where($fieldType, $request->login)->first();
            if ($user === null || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Identifiants incorrects'], 401);
            } else {
                $token = $user->createToken($user->email)->plainTextToken;
                $type_user = $user->account->accountables->getMorphClass();
                $type_user = class_basename($type_user);

                Auth::login($user, true);

                return response()->json([
                    'user' => $user,
                    'token' => $token,
                    'type' => $type_user,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur interne de serveur'], 500);
        }
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        $student = Student::create([
            'university_id' => $validated['university_id'],
            'promotion_id' => $validated['promotion_id']
        ]);

        $result = $this->accountService->createUser(array_merge($validated, ['accountable_type' => Student::class, 'accountable_id' => $student->id]));
            return response()->json([
                'message' => 'Compte créé avec succès',
                'user' => $result,
            ], 201);
    }

    public function activate(Request $request)
    {
        $code = $request->activation_code;
        $user = User::where('activation_code', $code)->first();

        try {
            $activatedUser = $this->accountService->activateStudent($user, $code);
            return response()->json([
                'message' => 'Compte activé avec succès',
                'user' => $activatedUser['user'],
                'token' => $activatedUser['token']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
