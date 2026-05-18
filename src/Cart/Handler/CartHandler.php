<?php

namespace App\Cart\Handler;

use App\Cart\Cart;
use App\Cart\CartInterface;
use App\Cart\Strategy\SessionCart;
use App\Entity\Product;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CartHandler
{
    public function __construct(
        #[Autowire(service: SessionCart::class)]
        private CartInterface $cartStrategy
    ) {
    }

    public function createCart(): Cart
    {
        return new Cart();
    }

    public function getCart(): Cart
    {
        return $this->cartStrategy->findCart() ?? $this->createCart();
    }

    public function addProduct(Cart $cart, Product $product, int $quantity = 1): Cart
    {
        return $this->cartStrategy->addProduct($cart, $product, $quantity);
    }

    public function removeProduct(Cart $cart, string $productSlug): Cart
    {
        return $this->cartStrategy->removeProduct($cart, $productSlug);
    }

    public function clearCart(Cart $cart): Cart
    {
        return $this->cartStrategy->clearCart($cart);
    }

    public function getItems(Cart $cart): array
    {
        return $this->cartStrategy->getItems($cart);
    }

    public function getTotal(Cart $cart): float
    {
        return $this->cartStrategy->getTotal($cart);
    }
}
