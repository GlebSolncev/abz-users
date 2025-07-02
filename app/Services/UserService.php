<?php

namespace App\Services;

use App\Exceptions\UserConflictException;
use App\Models\User;

readonly class UserService
{
    public function __construct(
        private PhotoService $photoService
    ) {}

    /**
     * @param array $data
     * @return User
     * @throws UserConflictException
     */
    public function createUser(array $data): User
    {
        if (User::byEmailOrPhone($data['email'], $data['phone'])->exists()) {
            throw new UserConflictException;
        }

        return User::query()->create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'phone'       => $data['phone'],
            'position_id' => $data['position_id'],
            'photo'       => $this->photoService->processAndStore($data['photo']),
        ]);
    }
}