<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Customer;

use App\Actions\Admin\UpdateDeposit;
use App\Actions\Customer\RegisterDeposit;
use App\Http\Controllers\Controller;
use App\Http\Requests\Deposit\{CreateDepositRequest, StatusDepositRequest};
use App\Http\Resources\DepositResource;
use App\Models\Deposit;

use function auth;
use function bcdiv;
use function formatNumber;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\{Inertia, Response};

class DepositController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $this->authorize('viewAny', Deposit::class);

        $result = auth()->user()
            ->deposits()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->simplePaginate();

        $valuePending = auth()->user()
            ->deposits()
            ->whereStatus(null)
            ->sum('value');

        return Inertia::render('Customer/Deposit/Index', [
            'deposits'     => DepositResource::collection($result),
            'valuePending' => formatNumber((float) bcdiv($valuePending ?: '0', '100', 2)),
            'balance'      => formatNumber(auth()->user()->profile->balance),
            'create'       => auth()->user()->can('create', Deposit::class),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Deposit::class);

        return Inertia::render('Customer/Deposit/Create');
    }

    public function store(RegisterDeposit $deposit, CreateDepositRequest $request): DepositResource
    {
        $this->authorize('create', Deposit::class);

        return new DepositResource($deposit->handle($request->validated()));
    }

    public function edit(Deposit $deposit): Response
    {
        $this->authorize('edit', $deposit);

        return Inertia::render('Customer/Deposit/Edit', [
            'deposit' => new DepositResource($deposit),
        ]);
    }

    public function status(StatusDepositRequest $request, Deposit $deposit, UpdateDeposit $status): DepositResource
    {
        $this->authorize('edit', $deposit);

        $status->handle($deposit, (bool) $request->status);

        return new DepositResource($deposit);
    }
}
