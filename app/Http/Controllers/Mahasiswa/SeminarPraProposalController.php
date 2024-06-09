<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarPraProposal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarPraProposalResource;
use App\Http\Resources\SeminarPraProposalCollection;
use App\Repositories\Mahasiswa\SeminarPraProposalRepository;
use App\Http\Requests\Mahasiswa\SeminarPraProposalStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarPraProposalUpdateRequest;

class SeminarPraProposalController extends Controller
{
    /**
     * @var \App\Models\SeminarPraProposal
     */
    protected $seminarPraProposalModel;

    /**
     * @var \App\Repositories\Mahasiswa\SeminarPraProposalRepository
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
            ->DataMahasiswa()
            ->paginate($request->query('show'));
        return Response::json(new SeminarPraProposalCollection($submission));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SeminarPraProposalStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeminarPraProposalStoreRequest $request)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = $this->seminarPraProposalRepository
                ->store($request);
            return $submission;
        });

        return Response::json(
            new SeminarPraProposalResource($submission),
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
    public function show(SeminarPraProposal $seminar_praproposal)
    {
        $seminar_praproposal->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarPraProposalResource($seminar_praproposal));
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
     * @param  \App\Http\Requests\Mahasiswa\SeminarPraProposalUpdateRequest  $request
     * @param  \App\Models\SeminarPraProposal $seminar_praproposal
     * @return \Illuminate\Http\Response
     */
    public function update(SeminarPraProposalUpdateRequest $request, SeminarPraProposal $seminar_praproposal)
    {
        $updatedSeminarPraProposal = DB::transaction(function () use ($request, $seminar_praproposal) {
            $updatedSeminarPraProposal = $this->seminarPraProposalRepository
                ->update($request, $seminar_praproposal);
            return $updatedSeminarPraProposal;
        });
        return Response::json(new SeminarPraProposalResource($updatedSeminarPraProposal));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeminarPraProposal $seminar_praproposal)
    {
        $deleteSeminarPraProposal = DB::transaction(function () use ($seminar_praproposal) {
            $deleteSeminarPraProposal = $this->seminarPraProposalRepository
                ->delete($seminar_praproposal);
            return $deleteSeminarPraProposal;
        });

        return Response::noContent();
    }
}
