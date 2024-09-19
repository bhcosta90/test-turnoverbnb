<?php

declare(strict_types = 1);

namespace App\Actions\Customer;

use App\Exceptions\InsufficientBalanceException;
use App\Interfaces\UserInterface;
use App\Models\Order;

use function bcsub;

use DB;

use Throwable;

class RegisterOrder
{
    public function __construct(protected UserInterface $user)
    {
    }

    public function handle(array $data): Order
    {
        try {
            DB::beginTransaction();

            if (($profile = ($user = $this->user->getUserLogin())->profile)->balance < $data['value']) {
                throw new InsufficientBalanceException();
            }

            $order = $user->orders()->create([
                'value'       => $data['value'],
                'description' => $data['description'],
            ]);

            $profile->update([
                'balance' => bcsub((string) $profile->balance, (string) $data['value'], 2),
            ]);

            DB::commit();

            return $order;
        } catch (Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
