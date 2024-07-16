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
            'status_text'    => $this->statusText,
            'pic'       => $pic,
            'mahasiswa' => $mahasiswa,
            'dok_pengajuan_judul' => $this->dok_pengajuan_judul ? url('/') . '/storage/' . $this->dok_pengajuan_judul : null,
            'konsentrasi_ilmu'   => $this->konsentrasi_ilmu,

            'proposed_at'    => $this->proposed_at?->format('c'),
            'approved_at'    => $this->approved_at?->format('c'),
            'declined_at'    => $this->declined_at?->format('c'),
            'note'          => $this->note,
            'created_at'     => $this->created_at?->format('c'),
            'updated_at'     => $this->updated_at?->format('c'),
        ];
    }
}
