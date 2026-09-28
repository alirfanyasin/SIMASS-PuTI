<?php

namespace App\Console\Commands;

use App\Models\Presence;
use App\Services\PresenceTimeCalculator;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('presence:auto-checkout')]
#[Description('Automatically check out open presences at the configured work end time')]
class AutoCheckoutPresences extends Command
{
    public function handle(PresenceTimeCalculator $timeCalculator): int
    {
        $now = Carbon::now();
        $automaticCheckout = Carbon::parse($now->toDateString().' '.$timeCalculator->workEnd());

        if ($now->lessThan($automaticCheckout)) {
            return self::SUCCESS;
        }

        Presence::where('tanggal', $now->toDateString())
            ->whereNull('jam_pulang')
            ->where('jam_masuk', '<=', $timeCalculator->workEnd())
            ->each(function (Presence $presence) use ($automaticCheckout, $timeCalculator): void {
                $presence->update(['jam_pulang' => $automaticCheckout->format('H:i:s')]);
                $presence->recalculateTotalJam();
                $timeCalculator->syncEarlyOvertime($presence);
            });

        return self::SUCCESS;
    }
}
