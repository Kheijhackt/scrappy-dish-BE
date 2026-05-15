<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPreference;

class UserPreferencesService
{
    public function getUserPreference(User $user): ?UserPreference
    {
        return $user->preferences;
    }

    public function updateUserPreference(User $user, array $data): UserPreference
    {
        $userPreference = $user->preferences;
        $userPreference->updateOrFail($data);

        return $userPreference;
    }
}
