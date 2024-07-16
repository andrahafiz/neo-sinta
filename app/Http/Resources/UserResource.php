<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'username'    => $this->username,
            'email'       => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'photo'       => $this->photo !== null && $this->photo != 'avatar.jpg' ? config('app.url') . '/storage/' . $this->photo : 'avatar.jpg',
            'roles'       => $this->roles,
            'created_at'   => $this->created_at?->format('c'),
            'updated_at'   => $this->updated_at?->format('c'),
        ];
    }
}
