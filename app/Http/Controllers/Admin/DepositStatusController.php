<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Inertia\Inertia;

class DepositStatusController extends Controller
{
    use AuthorizesRequests;

    public function __invoke()
    {
        $this->authorize('viewAprove', Deposit::class);

        $result = Deposit::query()
            ->orderBy('created_at')
            ->orderBy('id')
            ->whereStatus(null)
            ->simplePaginate();

        return Inertia::render('Customer/Deposit/Status', [
            'deposits' => DepositResource::collection($result),
        ]);
    }
}
