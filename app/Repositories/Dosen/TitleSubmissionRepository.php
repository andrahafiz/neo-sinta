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
        $input = $request->safe(['status', 'note']);
        $userId = auth()->user()->id;
        $status = $input['status'] ?? $pengajuan_judul->status;
        $now = now();

        $pengajuan_judul->update([
            'status'      => $status,
            'pic'         => $userId,
            'note'        => $input['note'] ?? $pengajuan_judul->note,
            'approved_at' => $status == TitleSubmission::STATUS_APPROVE ? $now : null,
            'declined_at' => $status == TitleSubmission::STATUS_DECLINE ? $now : null,
        ]);

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
