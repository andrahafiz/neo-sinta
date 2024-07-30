<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class ThesisResource extends JsonResource
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
            'id'                => $this->id,
            'judul_thesis'      => $this->judul_thesis,
            'konsentrasi_ilmu'  => $this->konsentrasi_ilmu,
            'deskripsi'         => $this->deskripsi,
            'mahasiswa'         => new MahasiswaSimpleResource($this->mahasiswa),
            'pembimbing_1'      => new DosenSimpleResource($this->pembimbing1),
            'pembimbing_2'      => new DosenSimpleResource($this->pembimbing2),
        ];
    }
}
