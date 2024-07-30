<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarHasilResource extends JsonResource
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
            'title'            => $this->title,
            'status'            => $this->status,
            'status_text'            => $this->statusText,
            'date'       => $this->date?->format('c'),
            'pic'            => $lecture,

            'proposed_at'    => $this->proposed_at?->format('c'),
            'approved_at'    => $this->approved_at?->format('c'),
            'declined_at'    => $this->declined_at?->format('c'),

            'dok_persetujuan_sem_hasil'  => $this->dok_persetujuan_sem_hasil ? url('/') . '/storage/' . $this->dok_persetujuan_sem_hasil : null,
            'draf_tesis'  => $this->draf_tesis ? url('/') . '/storage/' . $this->draf_tesis : null,
            'tesis_ppt'  => $this->tesis_ppt ? url('/') . '/storage/' . $this->tesis_ppt : null,
            'loa'  => $this->loa ? url('/') . '/storage/' . $this->loa : null,
            'toefl'  => $this->toefl ? url('/') . '/storage/' . $this->toefl : null,
            'plagiarisme'  => $this->plagiarisme ? url('/') . '/storage/' . $this->plagiarisme : null,
            'note' => $this->note,
            'mahasiswa'         => $mahasiswa,
            'approval_by'        => $approvalBy,
            'created_at'   => $this->created_at?->format('c'),
            'updated_at'   => $this->updated_at?->format('c'),
        ];
    }
}
