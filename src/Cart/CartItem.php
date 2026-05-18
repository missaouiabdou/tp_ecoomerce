<?php

namespace App\Cart;

class CartItem
{
    public function __construct(
        private string $productSlug,
        private string $name,
        private float $unitPrice,
        private int $quantity = 1
    ) {
    }

    public function getProductSlug(): string
    {
        return $this->productSlug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = max(1, $quantity);
    }

    public function incrementQuantity(int $amount = 1): void
    {
        $this->setQuantity($this->quantity + $amount);
    }

    public function getSubtotal(): float
    {
        return $this->unitPrice * $this->quantity;
    }
}
