<?php

namespace App\Repositories\Mahasiswa;

use Exception;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\SitIn;
use App\Models\Product;
use App\Contracts\Logging;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Contracts\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\SitInCreateRequest;
use App\Http\Requests\SitInUpdateRequest;
use App\Repositories\Interface\SitInInterface;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Mahasiswa\SitInCheckInRequest;
use App\Http\Requests\Mahasiswa\SitInCheckOutRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SitInRepository
{

    /**
     * @var \App\Models\SitIn
     */
    protected $sitInModel;

    /**
     * @var \App\Models\Log
     */
    protected $logModel;

    /**
     * @param  \App\Models\SitIn  $sitInModel
     */
    public function __construct(
        SitIn $sitInModel,
    ) {
        $this->sitInModel = $sitInModel;
    }

    /**
     * @param  \App\Http\Requests\Mahasiswa\SitInCheckInRequest  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkIn(SitInCheckInRequest $request)
    {
        $input = $request->safe([
            'check_in_proof',
        ]);

        $photo = $request->file('check_in_proof');
        if ($photo instanceof UploadedFile) {
            $rawPath = $photo->store('public/sitin/checkin');
            $path = str_replace('public/', '', $rawPath);
        }

        $checkin = $this->sitInModel->create([
            'check_in_proof'  => $path,
            'date'      => now(),
            'check_in'  => now()->format('H:i'),
            'mahasiswas_id' => auth()->user()->id,
            'status' => SitIn::STATUS_IN_PROGRESS
        ]);

        return $checkin;
    }

    /**
     * @param  \App\Http\Requests\Mahasiswa\SitInCheckOutRequest  $request
     * @return \App\Models\SitIn $sitIn
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkOut(SitInCheckOutRequest $request, SitIn $sitIn)
    {
        $input = $request->safe([
            'check_out_proof',
            'check_out_document',
        ]);

        $check_out_proof =  $input['check_out_proof'];
        if ($check_out_proof instanceof UploadedFile) {
            $rawPath = $check_out_proof->store('public/sitin/checkout');
            $path_proof = str_replace('public/', '', $rawPath);
        }
        $check_out_document =  $input['check_out_document'];
        if ($check_out_document instanceof UploadedFile) {
            $rawPath = $check_out_document->store('public/sitin/checkout');
            $path_proof_document = str_replace('public/', '', $rawPath);
        }

        $sitIn->update([
            'check_out_proof'          => $path_proof ?? null,
            'check_out_document'          => $path_proof_document ?? null,
            'check_out'    => now(),
            'duration'      => now()->diffInMinutes($sitIn->check_in),
            'status' =>  SitIn::STATUS_CONFIRM
        ]);


        return $sitIn;
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SitIn  $checkin
     * @return \App\Models\SitIn
     */
    public function delete(Request $request, SitIn $checkin)
    {
        $checkin->delete();
        return $checkin;
    }
}
