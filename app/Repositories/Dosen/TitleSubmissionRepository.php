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

}
