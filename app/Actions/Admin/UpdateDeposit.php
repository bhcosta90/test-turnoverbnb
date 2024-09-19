<?php

declare(strict_types = 1);

namespace App\Actions\Admin;

use App\Models\Deposit;

use function bcadd;

use DB;

use Throwable;

class UpdateDeposit
{
    public function handle(Deposit $deposit, bool $status): Deposit
    {
        try {
            DB::beginTransaction();
            $deposit->update([
                'status' => $status,
            ]);

            if ($status) {
                $deposit->user->profile->update([
                    'balance' => bcadd((string) $deposit->user->profile->balance, (string) $deposit->value, 2),
                ]);
            }

            DB::commit();

            return $deposit;
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }

    }
}
