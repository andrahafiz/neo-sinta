<?php

namespace App\Http\Controllers\Dosen;


use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarProposal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarProposalResource;
use App\Http\Resources\SeminarProposalCollection;
use App\Repositories\Dosen\SeminarProposalRepository;
use App\Http\Requests\Dosen\SeminarProposalStoreRequest;
use App\Http\Requests\Dosen\SeminarProposalUpdateRequest;


class SeminarProposalController extends Controller
{
    /**
     * @var \App\Models\SeminarProposal
     */
    protected $seminarProposalModel;

    /**
     * @var \App\Repositories\Dosen\SeminarProposalRepository
     */
    protected $seminarProposalRepository;

    protected $user;

    /**
     * @param  \App\Models\SeminarProposal  $seminarProposalModel
     * @param  \App\Repositories\SeminarProposalRepository  $seminarProposalRepository
     */
    public function __construct(
        SeminarProposal $seminarProposalModel,
        SeminarProposalRepository $seminarProposalRepository
    ) {
        $this->seminarProposalModel = $seminarProposalModel;
        $this->seminarProposalRepository = $seminarProposalRepository;
        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contracts\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $submission = $this->seminarProposalModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->paginate($request->query('show'));
        return Response::json(new SeminarProposalCollection($submission));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SeminarProposal $seminar_proposal)
    {
        $seminar_proposal->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarProposalResource($seminar_proposal));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\SeminarProposalUpdateRequest  $request
     * @param  \App\Models\SeminarProposal $seminar_proposal
     * @return \Illuminate\Http\Response
     */
    public function confirm(SeminarProposalUpdateRequest $request, SeminarProposal $seminar_proposal)
    {
        $updatedSeminarProposal = DB::transaction(function () use ($request, $seminar_proposal) {
            $updatedSeminarProposal = $this->seminarProposalRepository
                ->confirm($request, $seminar_proposal);
            return $updatedSeminarProposal;
        });
        return Response::json(new SeminarProposalResource($updatedSeminarProposal));
    }
}
