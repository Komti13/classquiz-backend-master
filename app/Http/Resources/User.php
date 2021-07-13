<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'address' => $this->address,
            'level' => new Level($this->level),
            'school' => $this->school,
            'country' => new Country($this->country),
            'email' => $this->email,
            'image' => $this->activeImage(),
            'avatar_id' => $this->avatar_id,
            'roles' => Role::collection($this->roles),
            'badges' => Badge::collection($this->badges),
            'subscriptions' => Subscription::collection($this->subscriptions),
            'valid_subscriptions' => Subscription::collection($this->validSubscriptions),
            'tokens' => $this->tokens,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}