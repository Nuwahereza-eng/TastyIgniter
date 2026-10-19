<?php

namespace App\Console\Commands;

use App\Services\MarzOrderPayment;
use Illuminate\Console\Command;

class ExpireMarzPayments extends Command
{
    protected $signature = 'payments:expire-marz';

    protected $description = 'Expire Marz Pay prompts that were not approved and cancel abandoned unpaid orders';

    public function handle(MarzOrderPayment $marz): int
    {
        $cancelled = $marz->expireStale();
        if ($cancelled) {
            $this->info("Cancelled {$cancelled} unpaid Marz Pay order(s).");
        }

        return self::SUCCESS;
    }
}
