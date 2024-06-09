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
            'statusText' => $this->statusText,
            'pic'        => $lecture,

            'proposedAt'    => $this->proposed_at?->format('c'),
            'approvedAt'    => $this->approved_at?->format('c'),
            'declinedAt'    => $this->declined_at?->format('c'),
            'drafPraPro'  => $this->draf_pra_pro ? url('/') . '/storage/' . $this->draf_pra_pro : null,
            'praProPpt'  => $this->pra_pro_ppt ? url('/') . '/storage/' . $this->pra_pro_ppt : null,
            'dokPersetujuanPraPro'  => $this->dok_persetujuan_pra_pro ? url('/') . '/storage/' . $this->dok_persetujuan_pra_pro : null,

            'mahasiswa'         => $mahasiswa,
            'approvalBy'        => $approvalBy,
            'createdAt'   => $this->created_at?->format('c'),
            'updatedAt'   => $this->updated_at?->format('c'),
        ];
    }
}
