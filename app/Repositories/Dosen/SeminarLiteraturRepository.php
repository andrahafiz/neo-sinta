<?php

namespace App\Repositories\Dosen;

use App\Contracts\Logging;
use App\Models\SeminarLiteratur;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dosen\SeminarLiteraturStoreRequest;
use App\Http\Requests\Dosen\SeminarLiteraturUpdateRequest;

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
     * @param  \App\Http\Requests\Dosen\SeminarLiteraturUpdateRequest  $request
     * @param  \App\Models\SeminarLiteratur  $seminar_literatur
     * @return \App\Models\SeminarLiteratur
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(SeminarLiteraturUpdateRequest $request, SeminarLiteratur $seminar_literatur)
    {
        $input = $request->safe(['status', 'note']);
        $userId = auth()->user()->id;
        $status = $input['status'] ?? $seminar_literatur->status;
        $now = now();

        $seminar_literatur->update([
            'status'      => $status,
            'pic'         => $userId,
            'approval_by' => $userId,
            'note'        => $input['note'] ?? $seminar_literatur->note,
            'approved_at' => $status == SeminarLiteratur::STATUS_APPROVE ? $now : null,
            'declined_at' => $status == SeminarLiteratur::STATUS_DECLINE ? $now : null,
        ]);

        return $seminar_literatur;
    }
}
