<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Str;
use App\Mail\ActivationMail;
use App\Mail\PasswordGeneratedMail;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AccountService
{
    private function generateRandomPassword(): string
    {
        $length = rand(8, 15);
        $specials_chars = ['@', '$', '!', '%', '*', '#', '?', '&'];
        $base_string = Str::random($length - 2);
        $base_string .= strtoupper(Str::random(1));
        $base_string .= $specials_chars[array_rand($specials_chars)];
        return str_shuffle($base_string);
    }

    public function createUser(array $data)
    {
        $password = $data['type'] === 'student' ? Hash::make($data['password']) : $this->generateRandomPassword();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'],
            'phone' => $data['phone'],
            'password' => $password,
            'password_set' => $data['type'] === 'student',
        ]);

        $account = Account::create([
            'user_id' => $user->id,
            'accountable_type' => $data['accountable_type'],
            'accountable_id' => $data['accountable_id'],
        ]);

        if ($data['type'] === 'student') {
            $activationCode = rand(100000, 999999);
            $user->activation_code = $activationCode;
            $user->save();

            Mail::to($user->email, $user->name)->send(new ActivationMail($user, $activationCode));
        } else {
            $activationToken = Str::random(64);
            $user->activation_token = $activationToken;
            $user->save();

            Mail::to($user->email, $user->name)->send(new PasswordGeneratedMail($user, $password, $activationToken));
        }

        return $user;
    }

    public function activateStudent(User $user, int $code)
    {
        if ($user->activation_code !== $code) {
            throw new \Exception('code d\'activation invalide');
        }

        $user->activation_code = null;
        $user->save();

        $user->account->update(['is_active' => true]);

        return [
            'user' => $user,
            'token' => $user->createToken($user->name)->plainTextToken
        ];
    }

    public function activateUser(User $user)
    {
        $user->password_set = true;
        $user->activation_token = null;
        $user->save();

        $user->account->update(['is_active' => true]);

        return [
            'user' => $user,
            'token' => $user->createToken($user->name)->plainTextToken
        ];
    }

    public function loginUser(array $data)
    {
        $fieldType = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::with('account')->where($fieldType, $data['login'])->first();
        if ($user === null || !Hash::check($data['password'], $user->password)) {
            return response()->json(['error' => 'Identifiants incorrects'], 401);
        } else {
            $token = $user->createToken($user->name)->plainTextToken;
            $type_user = $user->account->accountables->getMorphClass();
            $type_user = class_basename($type_user);
            Auth::login($user, true);
        }

        return [
            'user' => $user,
            'token' => $token,
            'type_user' => $type_user,
        ];
    }

    public function sendResetCode(string $email): bool
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return false;
        }

        $resetCode = rand(10000, 99999);
        $user->update([
            'reset_code' => $resetCode,
            'reset_code_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        Mail::to($user->email, $user->name)->send(new PasswordResetCodeMail($user, $resetCode));
        
        return true;
    }

    public function verifyResetCode(string $email, int $resetCode): bool
    {
        $user = User::where('email', $email)->first();
        if (!$user || $user->reset_code !== $resetCode || $user->reset_code_expires_at->isPast()) {
            return false;
        }
        return true;
    }
    
    public function resetPassword(string $email, string $newPassword): bool
    {
        $user = User::where('email', $email)->first();
        if(!$user) {
            return false;
        }

        $user->update([
            'password' => Hash::make($newPassword),
            'reset_code' => null,
            'reset_code_expires_at' => null
        ]);
        return true;
    }
}
