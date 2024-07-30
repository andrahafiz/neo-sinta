<?php

namespace App\Repositories\Dosen;

use App\Contracts\Logging;
use App\Models\SeminarProyek;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dosen\SeminarProyekStoreRequest;
use App\Http\Requests\Dosen\SeminarProyekUpdateRequest;

class SeminarProyekRepository
{

    /**
     * @var \App\Models\SeminarProyek
     */
    protected $seminarProyek;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarProyek  $seminarProyek
     */
    public function __construct(
        SeminarProyek $seminarProyek,
    ) {
        $this->seminarProyek = $seminarProyek;
    }

    /**
     * @param  \App\Http\Requests\Dosen\SeminarProyekUpdateRequest  $request
     * @param  \App\Models\SeminarProyek  $seminar_proyek
     * @return \App\Models\SeminarProyek
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(SeminarProyekUpdateRequest $request, SeminarProyek $seminar_proyek)
    {
        $input = $request->safe(['status', 'note', 'tanggal_seminar_proyek']);
        $userId = auth()->user()->id;
        $status = $input['status'] ?? $seminar_proyek->status;
        $now = now();

        $seminar_proyek->update([
            'status'      => $status,
            'pic'         => $userId,
            'approval_by' => $userId,
            'note'        => $input['note'] ?? $seminar_proyek->note,
            'tanggal_seminar_proyek' => $input['tanggal_seminar_proyek'],
            'approved_at' => $status == SeminarProyek::STATUS_APPROVE ? $now : null,
            'declined_at' => $status == SeminarProyek::STATUS_DECLINE ? $now : null,
        ]);

        return $seminar_proyek;
    }
}
