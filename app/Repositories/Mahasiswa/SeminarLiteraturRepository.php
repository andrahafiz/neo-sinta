<?php

namespace App\Repositories\Mahasiswa;

use App\Models\SeminarLiteratur;
use App\Contracts\Logging;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Mahasiswa\SeminarLiteraturUpdateRequest;
use App\Http\Requests\Mahasiswa\SeminarLiteraturStoreRequest;

class SeminarLiteraturRepository
{

    /**
     * @var \App\Models\SeminarLiteratur
     */
    protected $seminarLiteratur;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarLiteratur  $seminarLiteratur
     */
    public function __construct(
        SeminarLiteratur $seminarLiteratur,
    ) {
        $this->seminarLiteratur = $seminarLiteratur;
    }

    /**
     * @param  \App\Http\Requests\SeminarLiteraturStoreRequest  $request
     * @return \App\Models\SeminarLiteratur
     */
    public function store(SeminarLiteraturStoreRequest $request)
    {
        $input = $request->safe([
            'file_ppt', 'file_literatur'
        ]);

        $file_ppt = $request->file('file_ppt');
        if ($file_ppt instanceof UploadedFile) {
            $rawPath = $file_ppt->store('public/dokumen/seminarliteratur');
            $path_ppt = str_replace('public/', '', $rawPath);
        }

        $file_literatur = $request->file('file_literatur');
        $path_literatur = array();
        foreach ($file_literatur as $photo) {
            if ($photo instanceof UploadedFile) {
                $rawPath = $photo->store('public/dokumen/seminarliteratur');
                $path_literatur[] = str_replace('public/', '', $rawPath);
            }
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user()->id;
        $seminarLiteratur = $this->seminarLiteratur->create([
            'status'        => $this->seminarLiteratur::STATUS_PROPOSED,
            'date'          => now(),
            'mahasiswas_id' => $mahasiswa,
            'check_in_ppt'  => $path_ppt ?? null,
            'check_in_literatur' => json_encode($path_literatur) ?? null,
        ]);

        Logging::log("CREATE SEMINAR LITERARUR", $seminarLiteratur);
        return $seminarLiteratur;
    }

    /**
     * @param  \App\Http\Requests\SeminarLiteraturUpdateRequest  $request
     * @param  \App\Models\SeminarLiteratur  $pengajuan_judul
     * @return \App\Models\SeminarLiteratur
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SeminarLiteraturUpdateRequest $request, SeminarLiteratur $pengajuan_judul)
    {
        $input = $request->safe([
            'title',
            'status',
            'pic',
            'mahasiswas_id',
            'proposed_at',
            'in_review_at',
            'approved_at',
            'declined_at',
            'file_ppt',
            'file_literatur'
        ]);
        $document = $request->file('file_ppt');
        if ($document instanceof UploadedFile) {
            $rawPath = $document->store('public/dokumen/pengajuan_judul');
            $path_ppt = str_replace('public/', '', $rawPath);
        }

        $document = $request->file('file_ppt');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/' . $pengajuan_judul->file_ppt;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filename = $document->store('public/dokumen/pengajuan_judul');
        } else {
            $filename = $pengajuan_judul->file_ppt;
        };
        $pengajuan_judul->update([
            'title'         => $input['title'] ?? $pengajuan_judul->title,
            'status'        =>  $input['status'] ?? $pengajuan_judul->status,
            'mahasiswas_id' => $input['mahasiswas_id'] ?? $pengajuan_judul->mahasiswas_id,
            'pic'           => $input['pic'] ?? $pengajuan_judul->pic,
            'proposed_at'   => $input['proposed_at'] ?? $pengajuan_judul->proposed_at,
            'in_review_at'  => $input['in_review_at'] ?? $pengajuan_judul->in_review_at,
            'approved_at'   => $input['approved_at'] ?? $pengajuan_judul->approved_at,
            'declined_at'   => $input['declined_at'] ?? $pengajuan_judul->declined_at,
            'file_literatur'      => $input['file_literatur'] ?? $pengajuan_judul->file_literatur,
            'file_ppt'   =>  $filename
        ]);

        Logging::log("EDIT PRODUCT", $pengajuan_judul);
        return $pengajuan_judul;
    }

    /**
     * @param  \App\Models\SeminarLiteratur  $pengajuan_judul
     * @return \App\Models\SeminarLiteratur
     */
    public function delete(SeminarLiteratur $pengajuan_judul): bool
    {
        Logging::log("DELETE PRODUCT", $pengajuan_judul);
        $pengajuan_judul = $pengajuan_judul->delete();
        return $pengajuan_judul;
    }
}
