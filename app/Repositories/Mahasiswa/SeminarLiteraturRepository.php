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
            'title', 'dok_pengajuan_judul', 'konsentrasi_ilmu'
        ]);

        $document = $request->file('dok_pengajuan_judul');
        if ($document instanceof UploadedFile) {
            $rawPath = $document->store('public/dokumen/pengajuan_judul');
            $path = str_replace('public/', '', $rawPath);
        }

        $mahasiswa = auth()->guard('mahasiswa-guard')->user()->id;
        $submission = $this->seminarLiteratur->create([
            'title'         => $input['title'],
            'status'        => $this->seminarLiteratur::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'mahasiswas_id' => $mahasiswa,
            'konsentrasi_ilmu'      => $input['konsentrasi_ilmu'],
            'dok_pengajuan_judul'   => $path ?? null,
        ]);

        Logging::log("CREATE PRODUCT", $submission);
        return $submission;
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
            'dok_pengajuan_judul',
            'konsentrasi_ilmu'
        ]);
        $document = $request->file('dok_pengajuan_judul');
        if ($document instanceof UploadedFile) {
            $rawPath = $document->store('public/dokumen/pengajuan_judul');
            $path = str_replace('public/', '', $rawPath);
        }

        $document = $request->file('dok_pengajuan_judul');
        if ($document instanceof UploadedFile) {
            $file_path = storage_path() . '/app/' . $pengajuan_judul->dok_pengajuan_judul;
            if (File::exists($file_path)) {
                unlink($file_path);
            }
            $filename = $document->store('public/dokumen/pengajuan_judul');
        } else {
            $filename = $pengajuan_judul->dok_pengajuan_judul;
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
            'konsentrasi_ilmu'      => $input['konsentrasi_ilmu'] ?? $pengajuan_judul->konsentrasi_ilmu,
            'dok_pengajuan_judul'   =>  $filename
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
