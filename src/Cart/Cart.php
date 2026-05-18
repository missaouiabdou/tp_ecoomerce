<?php

namespace App\Cart;

class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    /**
     * @return CartItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $item): void
    {
        $key = $item->getProductSlug();

        if (isset($this->items[$key])) {
            $this->items[$key]->incrementQuantity($item->getQuantity());
            return;
        }

        $this->items[$key] = $item;
    }

    public function removeItem(string $productSlug): void
    {
        unset($this->items[$productSlug]);
    }

    public function clear(): void
    {
        $this->items = [];
    }

    public function getTotal(): float
    {
        return array_reduce($this->items, static fn(float $sum, CartItem $item) => $sum + $item->getSubtotal(), 0.0);
    }
}
