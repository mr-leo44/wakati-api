<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\AdminFac;
use App\Models\Professor;
use App\Models\SuperAdmin;
use App\Services\AccountService;
use App\Http\Requests\RegisterRequest;

class UserController extends Controller
{
    public function __construct(public AccountService $accountService) {}

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        if ($validated['type'] === 'superAdmin') {
            $accountable = SuperAdmin::create();
            $result = $this->getCreateUserByService(SuperAdmin::class, $validated, $accountable->id);
        } elseif ($validated['type'] === 'admin') {
            $accountable = Admin::create([
                'university_id' => $validated['university_id']
            ]);
            $result = $this->getCreateUserByService(Admin::class, $validated, $accountable->id);
        } elseif ($validated['type'] === 'adminFac') {
            $accountable = AdminFac::create([
                'university_id' => $validated['university_id'],
                'faculty_id' => $validated['faculty_id'],
            ]);
            $result = $this->getCreateUserByService(AdminFac::class, $validated, $accountable->id);
        } else {
            $accountable = Professor::create([]);
            $result = $this->getCreateUserByService(Professor::class, $validated, $accountable->id);
        }

        return response()->json([
            'message' => 'Compte créé avec succès',
            'user' => $result,
        ], 201);
    }

    private function getCreateUserByService($class, array $request, int $accountable_id)
    {
        return $this->accountService->createUser(array_merge($request, ['accountable_type' => $class, 'accountable_id' => $accountable_id]));
    }

    public function activate($token)
    {
        $user = User::where('activation_code', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Erreur d\'activation de compte'
            ], 400);
        }
        
        $activatedUser = $this->accountService->activateUser($user);
        return response()->json([
            'message' => 'Compte activé avec succès',
            'user' => $activatedUser['user'],
            'token' => $activatedUser['token']
        ]);
    }
}
