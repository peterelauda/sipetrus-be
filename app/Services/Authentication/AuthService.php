<?php

namespace App\Services\Authentication;

use App\Repositories\Contracts\Authentication\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(string $email, string $password)
    {
        $user = $this->userRepository->getUser($email);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new Exception('Invalid credentials', 401);
        }

        $token = $user->createToken(
            'sipetrus_login_token',
            ['*'],
            now()->addMonth()
        )->plainTextToken;

        $result = (object) [
            'user' => $user,
            'tokenType' => 'Bearer',
            'accessToken' => $token
        ];

        return $result;
    }
}
