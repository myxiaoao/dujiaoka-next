<?php

declare(strict_types=1);
/**
 * This file is part of dujiaoka next server projects.
 */

namespace App\Livewire\Components;

use App\Models\Goods;
use Livewire\Component;

class ProductCard extends Component
{
    public Goods $product;

    public function mount(Goods $product): void
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.components.product-card');
    }
}
