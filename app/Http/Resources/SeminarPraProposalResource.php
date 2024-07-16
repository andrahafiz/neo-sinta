<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarPraProposalResource extends JsonResource
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
            'date'       => $this->date?->format('c'),
            'title'      => $this->title,
            'status'     => $this->status,
            'status_text' => $this->statusText,
            'pic'        => $lecture,

            'proposed_at' => $this->proposed_at?->format('c'),
            'approved_at' => $this->approved_at?->format('c'),
            'declined_at' => $this->declined_at?->format('c'),
            'draf_pra_pro' => $this->draf_pra_pro ? url('/') . '/storage/' . $this->draf_pra_pro : null,
            'pra_pro_ppt'  => $this->pra_pro_ppt ? url('/') . '/storage/' . $this->pra_pro_ppt : null,
            'ok_persetujuan_pra_pro'  => $this->dok_persetujuan_pra_pro ? url('/') . '/storage/' . $this->dok_persetujuan_pra_pro : null,

            'mahasiswa'   => $mahasiswa,
            'approval_by'  => $approvalBy,
            'note'        => $this->note,
            'created_at'   => $this->created_at?->format('c'),
            'updated_at'   => $this->updated_at?->format('c'),
        ];
    }
}
