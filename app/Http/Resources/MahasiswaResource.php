<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class MahasiswaResource extends JsonResource
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
            'nim'         => $this->nim,
            'email'       => $this->email,
            'is_active'   => $this->is_active,
            'theses'    => new ThesisResource($this->whenLoaded('theses'))
            // 'roles'       => $this->roles,
        ];
    }
}
