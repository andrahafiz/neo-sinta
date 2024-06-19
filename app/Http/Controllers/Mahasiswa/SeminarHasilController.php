<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarHasil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarHasilResource;
use App\Http\Resources\SeminarHasilCollection;
use App\Repositories\Mahasiswa\SeminarHasilRepository;
use App\Http\Requests\Mahasiswa\SeminarHasilStoreRequest;
use App\Http\Requests\Mahasiswa\SeminarHasilUpdateRequest;

class SeminarHasilController extends Controller
{
    /**
     * @var \App\Models\SeminarHasil
     */
    protected $seminarHasilModel;

    /**
     * @var \App\Repositories\Mahasiswa\SeminarHasilRepository
     */
    protected $seminarHasilRepository;

    protected $user;

    /**
     * @param  \App\Models\SeminarHasil  $seminarHasilModel
     * @param  \App\Repositories\SeminarHasilRepository  $seminarHasilRepository
     */
    public function __construct(
        SeminarHasil $seminarHasilModel,
        SeminarHasilRepository $seminarHasilRepository
    ) {
        $this->seminarHasilModel = $seminarHasilModel;
        $this->seminarHasilRepository = $seminarHasilRepository;
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
        $submission = $this->seminarHasilModel->with('mahasiswa')
            ->orderByDesc('created_at')
            ->DataMahasiswa()
            ->paginate($request->query('show'));
        return Response::json(new SeminarHasilCollection($submission));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SeminarHasilStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(SeminarHasilStoreRequest $request)
    {
        $submission = DB::transaction(function () use ($request) {
            $submission = $this->seminarHasilRepository
                ->store($request);
            return $submission;
        });

        return Response::json(
            new SeminarHasilResource($submission),
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
    public function show(SeminarHasil $seminar_hasil)
    {
        $seminar_hasil->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarHasilResource($seminar_hasil));
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
     * @param  \App\Http\Requests\Mahasiswa\SeminarHasilUpdateRequest  $request
     * @param  \App\Models\SeminarHasil $seminar_hasil
     * @return \Illuminate\Http\Response
     */
    public function update(SeminarHasilUpdateRequest $request, SeminarHasil $seminar_hasil)
    {
        $updatedSeminarHasil = DB::transaction(function () use ($request, $seminar_hasil) {
            $updatedSeminarHasil = $this->seminarHasilRepository
                ->update($request, $seminar_hasil);
            return $updatedSeminarHasil;
        });
        return Response::json(new SeminarHasilResource($updatedSeminarHasil));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SeminarHasil $seminar_hasil)
    {
        $deleteSeminarHasil = DB::transaction(function () use ($seminar_hasil) {
            $deleteSeminarHasil = $this->seminarHasilRepository
                ->delete($seminar_hasil);
            return $deleteSeminarHasil;
        });

        return Response::noContent();
    }
}
