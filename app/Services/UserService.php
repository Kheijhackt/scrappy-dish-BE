<?php

namespace App\Services;

use App\Models\User;

class UserService
{
  public function updateCurrentUser(User $user, array $data) {
    $user = User::findOrFail($user->id);
    $user->update($data);
    return $user;
  }

  public function deleteCurrentUser(User $user) {
    $user = User::findOrFail($user->id);
    $user->delete();
    return $user;
  }
}