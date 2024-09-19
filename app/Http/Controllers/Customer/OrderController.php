<?php

declare(strict_types = 1);

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\RegisterOrder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\{Order};

use function auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\RedirectResponse;

use Inertia\{Inertia, Response};

use function redirect;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index(): Response
    {
        $this->authorize('viewAny', Order::class);

        $result = auth()->user()
            ->orders()
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->simplePaginate();

        return Inertia::render('Customer/Order/Index', [
            'orders' => OrderResource::collection($result),
            'create' => auth()->user()->can('create', Order::class),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Order::class);

        return Inertia::render('Customer/Order/Create');
    }

    public function store(RegisterOrder $deposit, CreateOrderRequest $request): RedirectResponse
    {
        $this->authorize('create', Order::class);

        $deposit->handle($request->validated());

        return redirect()->route('order.index');
    }
}
