<?php

namespace App\Repositories\Dosen;

use App\Contracts\Logging;
use App\Models\SeminarPraProposal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dosen\SeminarPraProposalStoreRequest;
use App\Http\Requests\Dosen\SeminarPraProposalUpdateRequest;

class SeminarPraProposalRepository
{

    /**
     * @var \App\Models\SeminarPraProposal
     */
    protected $seminarPraProposal;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarPraProposal  $seminarPraProposal
     */
    public function __construct(
        SeminarPraProposal $seminarPraProposal,
    ) {
        $this->seminarPraProposal = $seminarPraProposal;
    }

    /**
     * @param  \App\Http\Requests\Dosen\SeminarPraProposalUpdateRequest  $request
     * @param  \App\Models\SeminarPraProposal  $seminar_praproposal
     * @return \App\Models\SeminarPraProposal
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(SeminarPraProposalUpdateRequest $request, SeminarPraProposal $seminar_praproposal)
    {
        $input = $request->safe(['status', 'note']);
        $userId = auth()->user()->id;
        $status = $input['status'] ?? $seminar_praproposal->status;
        $now = now();

        $seminar_praproposal->update([
            'status'      => $status,
            'pic'         => $userId,
            'approval_by' => $userId,
            'note'        => $input['note'] ?? $seminar_praproposal->note,
            'approved_at' => $status == SeminarPraProposal::STATUS_APPROVE ? $now : null,
            'declined_at' => $status == SeminarPraProposal::STATUS_DECLINE ? $now : null,
        ]);

        return $seminar_praproposal;
    }
}
