<?php

namespace App\Repositories\Dosen;

use App\Models\TitleSubmission;
use App\Contracts\Logging;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Http\Requests\Dosen\TitleSubmissionUpdateRequest;
use App\Http\Requests\Dosen\TitleSubmissionStoreRequest;

class TitleSubmissionRepository
{

    /**
     * @var \App\Models\TitleSubmission
     */
    protected $titleSubmission;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\TitleSubmission  $titleSubmission
     */
    public function __construct(
        TitleSubmission $titleSubmission,
    ) {
        $this->titleSubmission = $titleSubmission;
    }

    /**
     * @param  \App\Http\Requests\TitleSubmissionStoreRequest  $request
     * @return \App\Models\TitleSubmission
     */
    public function store(TitleSubmissionStoreRequest $request)
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
        $submission = $this->titleSubmission->create([
            'title'         => $input['title'],
            'status'        => $this->titleSubmission::STATUS_PROPOSED,
            'proposed_at'   => now(),
            'mahasiswas_id' => $mahasiswa,
            'konsentrasi_ilmu'      => $input['konsentrasi_ilmu'],
            'dok_pengajuan_judul'   => $path ?? null,
        ]);

        Logging::log("CREATE PRODUCT", $submission);
        return $submission;
    }

    /**
     * @param  \App\Http\Requests\TitleSubmissionUpdateRequest  $request
     * @param  \App\Models\TitleSubmission  $pengajuan_judul
     * @return \App\Models\TitleSubmission
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(TitleSubmissionUpdateRequest $request, TitleSubmission $pengajuan_judul)
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
     * @param  \App\Models\TitleSubmission  $pengajuan_judul
     * @return \App\Models\TitleSubmission
     */
    public function delete(TitleSubmission $pengajuan_judul): bool
    {
        Logging::log("DELETE PRODUCT", $pengajuan_judul);
        $pengajuan_judul = $pengajuan_judul->delete();
        return $pengajuan_judul;
    }
}
