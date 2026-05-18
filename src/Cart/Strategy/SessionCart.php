<?php

namespace App\Cart\Strategy;

use App\Cart\Cart;
use App\Cart\CartInterface;
use App\Cart\CartItem;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionCart implements CartInterface
{
    private const CART_SESSION_KEY = 'cart';

    public function __construct(private RequestStack $requestStack)
    {
    }

    private function getSession(): SessionInterface
    {
        $session = $this->requestStack->getSession();

        if (!$session) {
            throw new \RuntimeException('Unable to access session from RequestStack.');
        }

        return $session;
    }

    public function findCart(): ?Cart
    {
        return $this->getSession()->get(self::CART_SESSION_KEY);
    }

    public function saveCart(Cart $cart): void
    {
        $this->getSession()->set(self::CART_SESSION_KEY, $cart);
    }

    public function addProduct(Cart $cart, Product $product, int $quantity = 1): Cart
    {
        $cart->addItem(new CartItem($product->getSlug(), $product->getName(), $product->getPrice(), $quantity));
        $this->saveCart($cart);

        return $cart;
    }

    public function removeProduct(Cart $cart, string $productSlug): Cart
    {
        $cart->removeItem($productSlug);
        $this->saveCart($cart);

        return $cart;
    }

    public function clearCart(Cart $cart): Cart
    {
        $cart->clear();
        $this->saveCart($cart);

        return $cart;
    }

    public function getItems(Cart $cart): array
    {
        return $cart->getItems();
    }

    public function getTotal(Cart $cart): float
    {
        return $cart->getTotal();
    }
}
