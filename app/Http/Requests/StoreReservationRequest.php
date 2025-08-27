<?php 

// app/Http/Requests/StoreReservationRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\ReservationStatus; // optional if you use PHP Enums

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // or use policies/roles
    }

    public function rules(): array
    {
        return [
            'purpose'            => ['required','string','max:500'],
            'attention_to'       => ['required','string','max:100'],
            'vote_ptj'           => ['required','string','max:100'],
            'dept_faculty'       => ['required','string','max:150'],
            'officer_email'      => ['required','email','max:150'],
            'vehicle_id'         => ['required', 'exists:fl_vehicle,type'],
            'no_vehicle'         => ['required','integer','min:1','max:50'],
            'program'            => ['required','string','max:200'],
            'booking_type'       => ['required','string','in:SEND AND FETCH,SEND ONLY,FETCH ONLY'],
            'pickup_point'       => ['required','string','max:1000'],
            'pickup_state'       => ['required','string','max:100'], // or exists:states,code
            'destination'        => ['required','string','max:1000'],
            'destination_state'  => ['required','string','max:100'],
            'start_date'         => ['required','date'],
            'start_time'         => ['required','date_format:H:i'],
            'end_date'           => ['required','date'],
            'end_time'           => ['required','date_format:H:i'],
            'agree'              => ['accepted'], // nicer than required|boolean
        ];
    }

    public function messages(): array
    {
        return [
            'agree.accepted' => 'You must agree to the disclaimer.',
        ];
    }
}
