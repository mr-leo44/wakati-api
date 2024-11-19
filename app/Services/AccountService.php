<?php

namespace App\Services;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Str;
use App\Mail\ActivationMail;
use App\Mail\PasswordGeneratedMail;
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
        return Str::shuffle($base_string);
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
            $token = $user->createToken('AuthToken')->plainTextToken;
            $result = [
                'user' => $user,
                'token' => $token
            ];
        } else {
            $activationToken = Str::random(64);
            $user->activation_token = $activationToken;
            $user->save();

            Mail::to($user->email, $user->name)->send(new PasswordGeneratedMail($user, $password, $activationToken));
            $result = $user;
        }

        return $result;
    }

    public function activateStudent(User $user, int $code)
    {
        if ($user->activation_code !== $code) {
            throw new \Exception('code d\'activation invalide');
        }

        $user->activation_code = null;
        $user->save();

        $user->account->update(['is_active' => true]);

        return $user;
    }

    public function activateUser(User $user)
    {
        $user->password_set = true;
        $user->account->update(['is_active' => true]);
        $user->save();

        return $user;
    }
}