<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarProposalResource extends JsonResource
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
            'status_text'            => $this->statusText,
            'date'       => $this->date?->format('c'),
            'pic'        => $lecture,

            'proposed_at' => $this->proposed_at?->format('c'),
            'approved_at' => $this->approved_at?->format('c'),
            'declined_at' => $this->declined_at?->format('c'),
            'draf_pro'    => $this->draf_pro ? url('/') . '/storage/' . $this->draf_pro : null,
            'pro_ppt'     => $this->pro_ppt ? url('/') . '/storage/' . $this->pro_ppt : null,
            'dok_persetujuan_pro'  => $this->dok_persetujuan_pro ? url('/') . '/storage/' . $this->dok_persetujuan_pro : null,
            'note'      => $this->note,
            'tanggal_seminar_proposal' => $this->whenNotNull($this->tanggal_seminar_proposal),
            'mahasiswa'   => $mahasiswa,
            'approval_by'  => $approvalBy,
            'created_at'   => $this->created_at?->format('c'),
            'updated_at'   => $this->updated_at?->format('c'),
        ];
    }
}
