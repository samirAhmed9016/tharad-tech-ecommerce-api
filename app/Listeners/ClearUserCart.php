<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Repositories\Carts\V1\CartRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClearUserCart
{

    protected $cartRepo;

    /**
     * Create the event listener.
     */
    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $this->cartRepo->empty($event->userId);
    }
}
