<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class AuthService
{
  public function authenticateUser(string $idToken): array 
  {
    $userInfo = $this->getUserInfoViaGoogle($idToken);
    return $this->signUpOrLogin($userInfo);  
  }

  private function getUserInfoViaGoogle(string $idToken): array
  {

    $apiKey = env('FIREBASE_API_KEY');
    
    $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}", [
        'idToken' => $idToken,
    ]);

    $responseGoogleInfo = $response['users'][0]['providerUserInfo'][0];

    return [
      'name' => $responseGoogleInfo['displayName'],
      'email' => $responseGoogleInfo['email'],
      'google_id' => $responseGoogleInfo['rawId'],
      'avatar' => $responseGoogleInfo['photoUrl']
    ];
  }

  private function signUpOrLogin(array $data): array 
  {
    $user = User::firstOrCreate(
    [
      'google_id' => $data['google_id']
    ],
    [
      'name' => $data['name'],
      'email' => $data['email'],
      'google_id' => $data['google_id'],
      'avatar' => $data['avatar']
    ]);
    $token = $user->createToken('auth-token')->plainTextToken;


    return [
      'name' => $user->name,
      'email' => $user->email,
      'google_id' => $user->google_id,
      'avatar' => $user->avatar,
      'token' => $token
    ];
  }

  public function deleteCurrentToken(User $user): User
  {
    $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    return $user;
  }

  public function deleteAllUserTokens(User $user): User
  {
    $user->tokens()->delete();
    return $user;
  }
}