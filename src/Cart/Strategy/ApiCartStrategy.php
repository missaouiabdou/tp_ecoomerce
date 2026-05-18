<?php

namespace App\Cart\Strategy;

use App\Cart\Cart;
use App\Cart\CartInterface;
use App\Entity\Product;

class ApiCartStrategy implements CartInterface
{
    public function findCart(): ?Cart
    {
        dd('ApiCartStrategy::findCart called');
    }

    public function saveCart(Cart $cart): void
    {
        dd('ApiCartStrategy::saveCart called', $cart);
    }

    public function addProduct(Cart $cart, Product $product, int $quantity = 1): Cart
    {
        dd('ApiCartStrategy::addProduct called', $product->getSlug(), $quantity);
    }

    public function removeProduct(Cart $cart, string $productSlug): Cart
    {
        dd('ApiCartStrategy::removeProduct called', $productSlug);
    }

    public function clearCart(Cart $cart): Cart
    {
        dd('ApiCartStrategy::clearCart called');
    }

    public function getItems(Cart $cart): array
    {
        dd('ApiCartStrategy::getItems called');
    }

    public function getTotal(Cart $cart): float
    {
        dd('ApiCartStrategy::getTotal called');
    }
}
