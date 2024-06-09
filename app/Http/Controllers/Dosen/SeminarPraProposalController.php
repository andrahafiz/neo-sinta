<?php

namespace App\Http\Controllers\Dosen;


use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarPraProposal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarPraProposalResource;
use App\Http\Resources\SeminarPraProposalCollection;
use App\Repositories\Dosen\SeminarPraProposalRepository;
use App\Http\Requests\Dosen\SeminarPraProposalStoreRequest;
use App\Http\Requests\Dosen\SeminarPraProposalUpdateRequest;


class SeminarPraProposalController extends Controller
{
    /**
     * @var \App\Models\SeminarPraProposal
     */
    protected $seminarPraProposalModel;

    /**
     * @var \App\Repositories\Dosen\SeminarPraProposalRepository
     */
    protected $seminarPraProposalRepository;

    protected $user;

    /**
     * @param  \App\Models\SeminarPraProposal  $seminarPraProposalModel
     * @param  \App\Repositories\SeminarPraProposalRepository  $seminarPraProposalRepository
     */
    public function __construct(
        SeminarPraProposal $seminarPraProposalModel,
        SeminarPraProposalRepository $seminarPraProposalRepository
    ) {
        $this->seminarPraProposalModel = $seminarPraProposalModel;
        $this->seminarPraProposalRepository = $seminarPraProposalRepository;
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
        $submission = $this->seminarPraProposalModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->paginate($request->query('show'));
        return Response::json(new SeminarPraProposalCollection($submission));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SeminarPraProposal $seminar_praproposal)
    {
        $seminar_praproposal->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarPraProposalResource($seminar_praproposal));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\SeminarPraProposalUpdateRequest  $request
     * @param  \App\Models\SeminarPraProposal $seminar_praproposal
     * @return \Illuminate\Http\Response
     */
    public function confirm(SeminarPraProposalUpdateRequest $request, SeminarPraProposal $seminar_praproposal)
    {
        $updatedSeminarPraProposal = DB::transaction(function () use ($request, $seminar_praproposal) {
            $updatedSeminarPraProposal = $this->seminarPraProposalRepository
                ->confirm($request, $seminar_praproposal);
            return $updatedSeminarPraProposal;
        });
        return Response::json(new SeminarPraProposalResource($updatedSeminarPraProposal));
    }
}
