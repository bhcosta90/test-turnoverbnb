<?php

declare(strict_types = 1);

namespace App\Actions\Customer;

use App\Interfaces\UserInterface;
use App\Models\Deposit;

use DB;

use Storage;

use Throwable;

class RegisterDeposit
{
    public function __construct(protected UserInterface $user)
    {
    }

    public function handle(array $data): Deposit
    {
        try {
            DB::beginTransaction();

            $deposit = $this->user->getUserLogin()->deposits()->create([
                'value'       => $data['value'],
                'description' => $data['description'],
                'receipt'     => $pathReceipt = $data['receipt']->store('deposits/' . $this->user->getUserLogin()->id),
            ]);

            DB::commit();

            return $deposit;
        } catch (Throwable $e) {
            DB::rollBack();

            if (isset($pathReceipt)) {
                Storage::delete($pathReceipt);
            }

            throw $e;
        }
    }
}
