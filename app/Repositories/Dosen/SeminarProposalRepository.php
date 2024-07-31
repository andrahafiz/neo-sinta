<?php

namespace App\Repositories\Dosen;

use App\Contracts\Logging;
use App\Models\SeminarProposal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dosen\SeminarProposalStoreRequest;
use App\Http\Requests\Dosen\SeminarProposalUpdateRequest;

class SeminarProposalRepository
{

    /**
     * @var \App\Models\SeminarProposal
     */
    protected $seminarProposal;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SeminarProposal  $seminarProposal
     */
    public function __construct(
        SeminarProposal $seminarProposal,
    ) {
        $this->seminarProposal = $seminarProposal;
    }

    /**
     * @param  \App\Http\Requests\Dosen\SeminarProposalUpdateRequest  $request
     * @param  \App\Models\SeminarProposal  $seminar_proposal
     * @return \App\Models\SeminarProposal
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(SeminarProposalUpdateRequest $request, SeminarProposal $seminar_proposal)
    {
        $input = $request->safe(['status', 'note', 'tanggal_seminar_proposal']);
        $userId = auth()->user()->id;
        $status = $input['status'] ?? $seminar_proposal->status;
        $now = now();

        $seminar_proposal->update([
            'status'      => $status,
            'pic'         => $userId,
            'approval_by' => $userId,
            'tanggal_seminar_proposal' => $input['tanggal_seminar_proposal'],
            'note'        => $input['note'] ?? $seminar_proposal->note,
            'approved_at' => $status == SeminarProposal::STATUS_APPROVE ? $now : null,
            'declined_at' => $status == SeminarProposal::STATUS_DECLINE ? $now : null,
        ]);

        return $seminar_proposal;
    }
}
