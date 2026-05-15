<?php

namespace App\Services;

use App\Models\UserPreference;
use App\Models\User;

class UserPreferencesService
{
  public function getUserPreference(User $user): ?UserPreference
  {
    return $user->preferences;
  }
}