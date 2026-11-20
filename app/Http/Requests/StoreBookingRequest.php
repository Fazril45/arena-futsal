<?php

namespace App\Http\Requests;

use App\Rules\TidakBentrok;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lapangan_id' => ['required', 'exists:lapangan,id'],
            'tanggal'     => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(30)->format('Y-m-d')],
            'jam_mulai'   => ['required', 'date_format:H:i', new TidakBentrok],
            'durasi'      => ['required', 'integer', 'min:1', 'max:3'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $tanggal = $this->input('tanggal');
            $jamMulai = $this->input('jam_mulai');
            $waktuMain = Carbon::parse($tanggal . ' ' . $jamMulai);

            if ($waktuMain->hour < 8) {
                $validator->errors()->add('jam_mulai', 'Jam operasional mulai pukul 08:00.');
            }

            if ($waktuMain->isToday() && $waktuMain->lt(now()->addHour())) {
                $validator->errors()->add('jam_mulai', 'Booking hari ini minimal 1 jam dari waktu sekarang.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'lapangan_id.required'   => 'Lapangan wajib dipilih.',
            'lapangan_id.exists'     => 'Lapangan tidak valid.',
            'tanggal.required'       => 'Tanggal main wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'tanggal.before_or_equal'=> 'Booking maksimal 30 hari ke depan.',
            'jam_mulai.required'     => 'Jam mulai wajib dipilih.',
            'jam_mulai.date_format'  => 'Format jam tidak valid.',
            'durasi.required'        => 'Durasi wajib dipilih.',
            'durasi.min'             => 'Durasi minimal 1 jam.',
            'durasi.max'             => 'Durasi maksimal 3 jam.',
        ];
    }
}