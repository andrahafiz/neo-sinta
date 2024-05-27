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

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \App\Http\Requests\SitInStoreRequest  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(SitInStoreRequest $request)
    // {
    //     $newSitIn = DB::transaction(function () use ($request) {
    //         $newSitIn = $this->sitInRepository
    //             ->store($request);

    //         return $newSitIn;
    //     });
    //     $newSitIn->load(['locations', 'schedules']);

    //     return Response::json(
    //         new SitInResource($newSitIn),
    //         Response::MESSAGE_CREATED,
    //         Response::STATUS_CREATED
    //     );
    // }

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

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \App\Http\Requests\SitInUpdateRequest  $request
    //  * @param  \App\Models\SitIn  $sitIn
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(SitInUpdateRequest $request, SitIn $sitIn)
    // {
    //     $updatedSitIn = DB::transaction(function () use ($request, $sitIn) {
    //         $updatedSitIn = $this->sitInRepository
    //             ->update($request, $sitIn);

    //         return $updatedSitIn;
    //     });
    //     $updatedSitIn->load(['locations', 'schedules']);

    //     return Response::json(new SitInResource($updatedSitIn));
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  \App\Contracts\Request  $request
    //  * @param  \App\Models\SitIn  $sitIn
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy(Request $request, SitIn $sitIn)
    // {
    //     $deletedSitIn = DB::transaction(function () use ($request, $sitIn) {
    //         $deletedSitIn = $this->sitInRepository
    //             ->delete($request, $sitIn);

    //         return $deletedSitIn;
    //     });

    //     return Response::noContent();
    // }
}
