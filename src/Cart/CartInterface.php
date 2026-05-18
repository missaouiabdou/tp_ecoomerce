<?php

namespace App\Cart;

use App\Cart\Cart;
use App\Entity\Product;

interface CartInterface
{
    public function findCart(): ?Cart;

    public function saveCart(Cart $cart): void;

    public function addProduct(Cart $cart, Product $product, int $quantity = 1): Cart;

    public function removeProduct(Cart $cart, string $productSlug): Cart;

    public function clearCart(Cart $cart): Cart;

    public function getItems(Cart $cart): array;

    public function getTotal(Cart $cart): float;
}
