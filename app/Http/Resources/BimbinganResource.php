<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class BimbinganResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $mahasiswa = $this->mahasiswa ? [
            'id' => $this->mahasiswa?->id,
            'name' => $this->mahasiswa?->name,
        ] : null;

        $dosen_pembimbing = $this->dosen_pembimbing ? [
            'id' => $this->lecture?->id,
            'name' => $this->lecture?->name,
        ] : null;

        return [
            'id'                => $this->id,
            'mahasiswa'         => $mahasiswa,
            'pembahasan'        => $this->pembahasan,
            'catatan'           => $this->catatan,
            'tanggal_bimbingan' => $this->tanggal_bimbingan?->format('c'),
            'type_pembimbing'   => $this->type_pembimbing,
            'dosen_pembimbing'  => $dosen_pembimbing,
            'type_bimbingan'    => $this->bimbingan_type,
            'approved_at'       => $this->approved_at?->format('c'),
            'status'            => $this->status,

            'created_at'   => $this->created_at?->format('c'),
            'updated_at'   => $this->updated_at?->format('c'),
        ];
    }
}
