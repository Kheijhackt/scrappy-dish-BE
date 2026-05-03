<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaveRecipeResource extends JsonResource
{
    protected bool $success;
    protected string $message;

    public function __construct($resource, bool $success, string $message)
    {
        parent::__construct($resource);
        $this->success = $success;
        $this->message = $message;
    }
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message
        ];
    }
}
