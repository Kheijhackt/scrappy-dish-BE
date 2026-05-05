<?php

namespace App\Services;

use App\Models\User;

class AuthService
{
  public function deleteCurrentToken(User $user): User
  {
    $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    return $user;
  }
}