<?php

namespace App\Rules;

use App\Models\Booking;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class TidakBentrok implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $lapanganId = $this->data['lapangan_id'] ?? null;
        $tanggal = $this->data['tanggal'] ?? null;
        $durasi = (int) ($this->data['durasi'] ?? 0);

        if (! $lapanganId || ! $tanggal || ! $value || $durasi < 1) {
            return;
        }

        try {
            $mulai = Carbon::createFromFormat('H:i', $value);
        } catch (\Exception $e) {
            return;
        }

        $selesai = $mulai->copy()->addHours($durasi);

        if ($selesai->format('H:i') > '23:00' && $selesai->format('H:i') !== '00:00') {
            $fail('Jam selesai melewati jam operasional (23:00).');
            return;
        }
        if ($mulai->hour + $durasi > 23) {
            $fail('Jam selesai tidak boleh melewati pukul 23:00.');
            return;
        }

        $bentrok = Booking::bentrok(
            (int) $lapanganId,
            $tanggal,
            $mulai->format('H:i'),
            $selesai->format('H:i')
        )->exists();

        if ($bentrok) {
            $fail("Jam {$mulai->format('H:i')}–{$selesai->format('H:i')} sudah dipesan.");
        }
    }
}