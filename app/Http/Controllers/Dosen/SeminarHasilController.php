<?php

namespace App\Http\Controllers\Dosen;


use App\Contracts\Response;
use Illuminate\Http\Request;
use App\Models\SeminarHasil;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SeminarHasilResource;
use App\Http\Resources\SeminarHasilCollection;
use App\Repositories\Dosen\SeminarHasilRepository;
use App\Http\Requests\Dosen\SeminarHasilStoreRequest;
use App\Http\Requests\Dosen\SeminarHasilUpdateRequest;


class SeminarHasilController extends Controller
{
    /**
     * @var \App\Models\SeminarHasil
     */
    protected $seminarHasilModel;

    /**
     * @var \App\Repositories\Dosen\SeminarHasilRepository
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
            ->paginate($request->query('show'));
        return Response::json(new SeminarHasilCollection($submission));
    }

    /**
     * Display the specified resource.
     *
     * @param  SeminarHasil $seminar_hasil
     * @return \Illuminate\Http\Response
     */
    public function show(SeminarHasil $seminar_hasil)
    {
        $seminar_hasil->load(['mahasiswa', 'lecture']);
        return Response::json(new SeminarHasilResource($seminar_hasil));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dosen\SeminarHasilUpdateRequest  $request
     * @param  \App\Models\SeminarHasil $seminar_hasil
     * @return \Illuminate\Http\Response
     */
    public function confirm(SeminarHasilUpdateRequest $request, SeminarHasil $seminar_hasil)
    {
        $updatedSeminarHasil = DB::transaction(function () use ($request, $seminar_hasil) {
            $updatedSeminarHasil = $this->seminarHasilRepository
                ->confirm($request, $seminar_hasil);
            return $updatedSeminarHasil;
        });
        return Response::json(new SeminarHasilResource($updatedSeminarHasil));
    }
}
