<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarProyekResource extends JsonResource
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

        $lecture = $this->lecture ? [
            'id' => $this->lecture?->id,
            'name' => $this->lecture?->name,
        ] : null;

        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'status'     => $this->status,
            'statusText'     => $this->statusText,
            'date'       => $this->date?->format('c'),
            'pic'        => $lecture,

            'proposedAt' => $this->proposed_at?->format('c'),
            'approvedAt' => $this->approved_at?->format('c'),
            'declinedAt' => $this->declined_at?->format('c'),
            'dokPerSemProyek'  => $this->dok_per_sem_proyek ? url('/') . '/storage/' . $this->dok_per_sem_proyek : null,
            'note'       => $this->note,
            'mahasiswa'  => $mahasiswa,
            'createdAt'  => $this->created_at?->format('c'),
            'updatedAt'  => $this->updated_at?->format('c'),
        ];
    }
}
