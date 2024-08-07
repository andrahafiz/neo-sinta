<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class SidangMejaHijauResource extends JsonResource
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

        $lecture = $this->lecture ? [
            'id' => $this->lecture?->id,
            'name' => $this->lecture?->name,
        ] : null;

        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'status'     => $this->status,
            'status_text' => $this->statusText,
            'pic'        => $lecture,
            'dok_persetujuan_sidang_meja_hijau'  => $this->dok_persetujuan_sidang_meja_hijau ? url('/') . '/storage/' . $this->dok_persetujuan_sidang_meja_hijau : null,
            'tanggal_sidang_meja_hijau' => $this->whenNotNull($this->tanggal_sidang_meja_hijau),
            'proposed_at' => $this->proposed_at?->format('c'),
            'approved_at' => $this->approved_at?->format('c'),
            'declined_at' => $this->declined_at?->format('c'),
            'note'      => $this->note,

            'mahasiswa'   => $mahasiswa,
            'approval_by'  => $approvalBy,
            'created_at'   => $this->created_at?->format('c'),
            'updated_at'   => $this->updated_at?->format('c'),
        ];
    }
}
