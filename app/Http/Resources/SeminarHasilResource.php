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
            'date'       => $this->date?->format('c'),
            'pic'            => $lecture,

            'proposedAt'    => $this->proposed_at?->format('c'),
            'approvedAt'    => $this->approved_at?->format('c'),
            'declinedAt'    => $this->declined_at?->format('c'),

            'dokPersetujuanSemHasil'  => $this->dok_persetujuan_sem_hasil ? url('/') . '/storage/' . $this->dok_persetujuan_sem_hasil : null,
            'drafTesis'  => $this->draf_tesis ? url('/') . '/storage/' . $this->draf_tesis : null,
            'tesisPpt'  => $this->tesis_ppt ? url('/') . '/storage/' . $this->tesis_ppt : null,
            'loa'  => $this->loa ? url('/') . '/storage/' . $this->loa : null,
            'toefl'  => $this->toefl ? url('/') . '/storage/' . $this->toefl : null,
            'plagiarisme'  => $this->plagiarisme ? url('/') . '/storage/' . $this->plagiarisme : null,

            'mahasiswa'         => $mahasiswa,
            'approvalBy'        => $approvalBy,
            'createdAt'   => $this->created_at?->format('c'),
            'updatedAt'   => $this->updated_at?->format('c'),
        ];
    }
}
