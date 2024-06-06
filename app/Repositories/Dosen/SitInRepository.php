<?php

namespace App\Repositories\Dosen;

use Carbon\Carbon;
use App\Models\SitIn;
use Illuminate\Http\Request;
use App\Http\Requests\Dosen\SitInUpdateRequest;
use Illuminate\Validation\ValidationException;

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
     * @return \App\Models\SitIn $sitIn
     * @throws \Illuminate\Validation\ValidationException
     */
    public function confirm(Request $request, SitIn $sitIn)
    {
        $data = $request->validate([
            'action' => ['required', 'in:approve,decline'],
        ]);
        $status = $data['action'] == 'approve' ? SitIn::STATUS_APPROVE : Sitin::STATUS_DECLINE;

        $sitIn->update([
            'status' => $status,
            'approval_by' => auth()->user()->id
        ]);

        return $sitIn;
    }

    /**
     * @param  \App\Http\Requests\SitInUpdateRequest  $request
     * @param  \App\Models\SitIn  $checkin
     * @return \App\Models\SitIn
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SitInUpdateRequest $request, SitIn $sitIn)
    {
        $data = $request->all();

        $checkIn = Carbon::parse($sitIn->check_in);
        $checkOut = Carbon::parse($data['check_out']);

        $data['duration'] = $checkOut->diffInMinutes($checkIn, true);

        if (!$checkIn->lessThan($checkOut)) {
            throw ValidationException::withMessages(['duration' => 'Waktu checkout harus lebih besar dibanding waktu checkin']);
        }

        $sitIn->update($data);
        return $sitIn;
    }
}
