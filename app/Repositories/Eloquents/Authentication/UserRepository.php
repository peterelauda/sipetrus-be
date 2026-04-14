<?php

namespace App\Repositories\Eloquents\Authentication;

use App\Models\User;
use App\Repositories\Contracts\Authentication\UserRepositoryInterface;
use App\Repositories\Eloquents\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getUser(string $email)
    {
        return $this->model
            ->where('email', $email)
            ->first();
    }
}