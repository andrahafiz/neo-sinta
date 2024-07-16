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
            'status_text'     => $this->statusText,
            'date'       => $this->date?->format('c'),
            'pic'        => $lecture,

            'proposed_at' => $this->proposed_at?->format('c'),
            'approved_at' => $this->approved_at?->format('c'),
            'declined_at' => $this->declined_at?->format('c'),
            'dok_per_sem_proyek'  => $this->dok_per_sem_proyek ? url('/') . '/storage/' . $this->dok_per_sem_proyek : null,
            'note'       => $this->note,
            'mahasiswa'  => $mahasiswa,
            'created_at'  => $this->created_at?->format('c'),
            'updated_at'  => $this->updated_at?->format('c'),
        ];
    }
}
