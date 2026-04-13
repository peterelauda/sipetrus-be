<?php

namespace App\Repositories\Contracts\Authentication;

use App\Repositories\Contracts\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getUser(string $email);
}