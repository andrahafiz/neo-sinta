<?php

namespace App\Http\Controllers\Mahasiswa;


use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarLiteratur;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarLiteraturResource;
use App\Http\Resources\SeminarLiteraturCollection;
use App\Repositories\Mahasiswa\SeminarLiteraturRepository;
use App\Http\Requests\Mahasiswa\SeminarLiteraturStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarLiteraturUpdateRequest;


class SeminarLiteraturController extends Controller
{
    /**
     * @var \App\Models\SeminarLiteratur
     */
    protected $seminarLiteraturModel;

    /**
     * @var \App\Repositories\Mahasiswa\SeminarLiteraturRepository
     */
    protected $seminarLiteraturRepository;

    protected $user;

    /**
     * @param  \App\Models\SeminarLiteratur  $seminarLiteraturModel
     * @param  \App\Repositories\SeminarLiteraturRepository  $seminarLiteraturRepository
     */
    public function __construct(
        SeminarLiteratur $seminarLiteraturModel,
        SeminarLiteraturRepository $seminarLiteraturRepository
    ) {
        $this->seminarLiteraturModel = $seminarLiteraturModel;
        $this->seminarLiteraturRepository = $seminarLiteraturRepository;
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
        $submission = $this->seminarLiteraturModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->DataMahasiswa()
            ->paginate($request->query('show'));
        return Response::json(new SeminarLiteraturCollection($submission));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SeminarLiteraturStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeminarLiteraturStoreRequest $request)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = $this->seminarLiteraturRepository
                ->store($request);
            return $submission;
        });

        return Response::json(
            new SeminarLiteraturResource($submission),
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
    public function show(SeminarLiteratur $pengajuan_judul)
    {
        $pengajuan_judul->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarLiteraturResource($pengajuan_judul));
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
     * @param  \App\Http\Requests\Mahasiswa\SeminarLiteraturUpdateRequest  $request
     * @param  \App\Models\SeminarLiteratur $pengajuan_judul
     * @return \Illuminate\Http\Response
     */
    public function update(SeminarLiteraturUpdateRequest $request, SeminarLiteratur $pengajuan_judul)
    {
        $updatedSeminarLiteratur = DB::transaction(function () use ($request, $pengajuan_judul) {
            $updatedSeminarLiteratur = $this->seminarLiteraturRepository
                ->update($request, $pengajuan_judul);
            return $updatedSeminarLiteratur;
        });
        return Response::json(new SeminarLiteraturResource($updatedSeminarLiteratur));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeminarLiteratur $pengajuan_judul)
    {
        $deleteSeminarLiteratur = DB::transaction(function () use ($pengajuan_judul) {
            $deleteSeminarLiteratur = $this->seminarLiteraturRepository
                ->delete($pengajuan_judul);
            return $deleteSeminarLiteratur;
        });

        return Response::noContent();
    }
}
