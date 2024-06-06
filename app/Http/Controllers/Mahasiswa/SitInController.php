<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Sitin;
use App\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SitInResource;
use App\Http\Resources\SitInCollection;
use App\Repositories\Mahasiswa\SitInRepository;
use App\Http\Requests\Mahasiswa\SitInCheckInRequest;
use App\Http\Requests\Mahasiswa\SitInCheckOutRequest;

class SitInController extends Controller
{

    /**
     * @var \App\Models\SitIn
     */
    protected $sitInModel;

    /**
     * @var \App\Repositories\Mahasiswa\SitInRepository
     */
    protected $sitInRepository;

    /**
     * @param  \App\Models\SitIn  $sitInModel
     * @param  \App\Repositories\SitInRepository  $sitInRepository
     */
    public function __construct(
        Sitin $sitInModel,
        SitInRepository $sitInRepository
    ) {
        $this->sitInModel = $sitInModel;
        $this->sitInRepository = $sitInRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Contracts\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sitInes = $this->sitInModel->with('mahasiswa')
            ->orderByDesc('created_at')->paginate($request->query('show'));

        return Response::json(new SitInCollection($sitInes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SitInCheckInRequest $request
     * @return \Illuminate\Http\Response
     */
    public function checkIn(SitInCheckInRequest $request)
    {
        $newSitIn = DB::transaction(function () use ($request) {
            $newSitIn = $this->sitInRepository
                ->checkIn($request);

            return $newSitIn;
        });
        $newSitIn->load(['mahasiswa']);
        return Response::json(
            new SitInResource($newSitIn),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Mahasiswa\SitInCheckOutRequest $request
     * @param \App\Models\Sitin $sitIn
     * @return \Illuminate\Http\Response
     */
    public function checkOut(SitInCheckOutRequest $request, Sitin $sitIn)
    {
        $newSitIn = DB::transaction(function () use ($request, $sitIn) {
            $newSitIn = $this->sitInRepository
                ->checkOut($request, $sitIn);

            return $newSitIn;
        });
        $newSitIn->load(['mahasiswa']);
        return Response::json(
            new SitInResource($newSitIn),
            Response::MESSAGE_CREATED,
            Response::STATUS_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SitIn  $sitIn
     * @return \Illuminate\Http\Response
     */
    public function show(SitIn $sitin)
    {
        $sitin->load(['mahasiswa']);

        return Response::json(new SitInResource($sitin));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SitIn  $sitIn
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SitIn $sitin)
    {
        $deletedSitIn = DB::transaction(function () use ($request, $sitin) {
            $deletedSitIn = $this->sitInRepository
                ->delete($request, $sitin);

            return $deletedSitIn;
        });

        return Response::noContent();
    }
}
