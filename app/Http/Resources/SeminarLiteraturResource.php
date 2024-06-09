<?php

namespace App\Http\Resources;

use App\Models\Mahasiswa;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarLiteraturResource extends JsonResource
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

        $approvalBy = $this->approvalBy ? [
            'id' => $this->approvalBy?->id,
            'name' => $this->approvalBy?->name,
        ] : null;

        $lecture = $this->lecture ? [
            'id' => $this->lecture?->id,
            'name' => $this->lecture?->name,
        ] : null;

        return [
            'id'            => $this->id,
            'status'        => $this->status,
            'statusText'    => $this->statusText,
            'date'          => $this->date?->format('c'),
            'filePPT'       => $this->check_in_ppt ? url('/') . '/storage/' . $this->check_in_ppt : null,
            'fileLiteratur' => $this->check_in_literatur ? array_map(function ($item) {
                return   url('/') . '/storage/' . $item;
            }, json_decode($this->check_in_literatur)) : null,
            'mahasiswa'     => $mahasiswa,
            'pic'           => $lecture,
            'approvalBy'    => $approvalBy,
            'note'          => $this->note,
            'createdAt'     => $this->created_at?->format('c'),
            'updatedAt'     => $this->updated_at?->format('c'),
        ];
    }
}
