<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class SitInResource extends JsonResource
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

        $approvalBy = $this->lecture ? [
            'id' => $this->lecture?->id,
            'name' => $this->lecture?->name,
        ] : null;

        return [
            'id'         => $this->id,
            'date'       => $this->date?->format('c'),
            'checkIn'    => $this->check_in?->format('H:i'),
            'checkOut'   => $this->check_out?->format('H:i'),
            'duration'   => $this->duration ?? 0,
            'checkInProof'      => $this->check_in_proof ? url('/') . '/storage/' . $this->check_in_proof : null,
            'checkOutProof'     => $this->check_out_proof ? url('/') . '/storage/' . $this->check_out_proof : null,
            'checkOutDocument'  => $this->check_out_document ? url('/') . '/storage/' . $this->check_out_document : null,
            'status'            => $this->status,
            'mahasiswa'         => $mahasiswa,
            'approvalBy'        => $approvalBy,
            'createdAt'   => $this->created_at?->format('c'),
            'updatedAt'   => $this->updated_at?->format('c'),
        ];
    }
}
