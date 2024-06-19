<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarProposal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarProposalResource;
use App\Http\Resources\SeminarProposalCollection;
use App\Repositories\Mahasiswa\SeminarProposalRepository;
use App\Http\Requests\Mahasiswa\SeminarProposalStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarProposalUpdateRequest;

class SeminarProposalController extends Controller
{
    /**
     * @var \App\Models\SeminarProposal
     */
    protected $seminarProposalModel;

    /**
     * @var \App\Repositories\Mahasiswa\SeminarProposalRepository
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
            ->DataMahasiswa()
            ->paginate($request->query('show'));
        return Response::json(new SeminarProposalCollection($submission));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SeminarProposalStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeminarProposalStoreRequest $request)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = $this->seminarProposalRepository
                ->store($request);
            return $submission;
        });

        return Response::json(
            new SeminarProposalResource($submission),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SeminarProposalUpdateRequest  $request
     * @param  \App\Models\SeminarProposal $seminar_proposal
     * @return \Illuminate\Http\Response
     */
    public function update(SeminarProposalUpdateRequest $request, SeminarProposal $seminar_proposal)
    {
        $updatedSeminarProposal = DB::transaction(function () use ($request, $seminar_proposal) {
            $updatedSeminarProposal = $this->seminarProposalRepository
                ->update($request, $seminar_proposal);
            return $updatedSeminarProposal;
        });
        return Response::json(new SeminarProposalResource($updatedSeminarProposal));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeminarProposal $seminar_proposal)
    {
        $deleteSeminarProposal = DB::transaction(function () use ($seminar_proposal) {
            $deleteSeminarProposal = $this->seminarProposalRepository
                ->delete($seminar_proposal);
            return $deleteSeminarProposal;
        });

        return Response::noContent();
    }
}
