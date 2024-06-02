<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class TitleSubmissionResource extends JsonResource
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

        $pic = $this->lecture ? [
            'id' => $this->lecture?->id,
            'name' => $this->lecture?->name,
        ] : null;

        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'status'    => $this->status,
            'pic'       => $pic,
            'mahasiswa' => $mahasiswa,
            'dokPengajuanJudul' => $this->dok_pengajuan_judul ? url('/') . '/storage/' . $this->dok_pengajuan_judul : null,
            'konsentrasiIlmu'   => $this->konsentrasi_ilmu,

            'proposedAt'    => $this->proposed_at?->format('c'),
            'inReviewAt'    => $this->in_review_at?->format('c'),
            'approvedAt'    => $this->approved_at?->format('c'),
            'declinedAt'    => $this->declined_at?->format('c'),
            'createdAt'     => $this->created_at?->format('c'),
            'updatedAt'     => $this->updated_at?->format('c'),
        ];
    }
}
