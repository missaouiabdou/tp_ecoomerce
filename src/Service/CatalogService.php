<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;

class CatalogService
{
    /** @var Category[] */
    private array $categories = [];

    /** @var Product[] */
    private array $products = [];

    public function __construct()
    {
        $this->loadSampleData();
    }

    /** @return Category[] */
    public function getCategories(): array
    {
        return array_values($this->categories);
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categories[$slug] ?? null;
    }

    /** @return Product[] */
    public function getProductsByCategory(Category $category): array
    {
        return array_values(array_filter($this->products, static fn(Product $product) => $product->getCategory()?->getSlug() === $category->getSlug()));
    }

    public function getProductBySlug(string $slug): ?Product
    {
        return $this->products[$slug] ?? null;
    }

    private function loadSampleData(): void
    {
        $electronics = new Category(
            'Electronics',
            'electronics',
            'Discover the latest technology, headphones, speakers and smart accessories.'
        );
        $fashion = new Category(
            'Fashion',
            'fashion',
            'Explore seasonal clothing, accessories and footwear for every style.'
        );
        $homeGarden = new Category(
            'Home & Garden',
            'home-garden',
            'Refresh your living space with decor, furniture and gardening essentials.'
        );

        $this->categories = [
            $electronics->getSlug() => $electronics,
            $fashion->getSlug() => $fashion,
            $homeGarden->getSlug() => $homeGarden,
        ];

        $products = [
            new Product(
                'Wireless Headphones',
                'wireless-headphones',
                79.99,
                'Comfortable, noise-reducing headphones for long listening sessions.',
                'High-quality wireless headphones with up to 20 hours of battery life and crystal-clear sound.'
            ),
            new Product(
                'Bluetooth Speaker',
                'bluetooth-speaker',
                59.99,
                'Portable speaker with rich sound and easy pairing.',
                'Compact Bluetooth speaker with deep bass, durable battery life, and water-resistant design.'
            ),
            new Product(
                'Smart Watch Pro',
                'smart-watch-pro',
                199.99,
                'Track your workouts and notifications from your wrist.',
                'Advanced smartwatch with heart-rate monitoring, GPS and sleep tracking.'
            ),
            new Product(
                'Minimalist Sneakers',
                'minimalist-sneakers',
                85.00,
                'Clean sneakers that work with every outfit.',
                'A modern pair of sneakers with removable insoles and a comfortable design.'
            ),
            new Product(
                'Linen Shirt',
                'linen-shirt',
                49.50,
                'Lightweight linen shirt with a relaxed fit.',
                'Perfect for warm weather, this linen shirt stays breathable and wrinkle-resistant.'
            ),
            new Product(
                'Canvas Weekend Bag',
                'canvas-weekend-bag',
                69.90,
                'Durable bag for short trips and everyday use.',
                'Spacious canvas bag with leather handles and multiple pockets for organized travel.'
            ),
            new Product(
                'Organic Cotton Throw',
                'organic-cotton-throw',
                39.99,
                'Soft throw blanket for the living room.',
                'Organic cotton throw with a subtle weave that keeps you comfortable all year round.'
            ),
            new Product(
                'Ceramic Planter Set',
                'ceramic-planter-set',
                34.90,
                'Stylish planters for indoor plants.',
                'Elegant ceramic planter set with three sizes designed to brighten your windowsill.'
            ),
            new Product(
                'Portable Desk Lamp',
                'portable-desk-lamp',
                29.95,
                'Flexible lamp with warm LED lighting.',
                'Minimalist desk lamp with adjustable brightness and a compact footprint.'
            ),
        ];

        // Set images for each product
        $products[0]->setCategory($electronics)->setImage('https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&h=600&fit=crop');
        $products[1]->setCategory($electronics)->setImage('https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800&h=600&fit=crop');
        $products[2]->setCategory($electronics)->setImage('https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&h=600&fit=crop');
        $products[3]->setCategory($fashion)->setImage('https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&h=600&fit=crop');
        $products[4]->setCategory($fashion)->setImage('https://images.unsplash.com/photo-1596521211487-76a137cecf4e?w=800&h=600&fit=crop');
        $products[5]->setCategory($fashion)->setImage('https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&h=600&fit=crop');
        $products[6]->setCategory($homeGarden)->setImage('https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=800&h=600&fit=crop');
        $products[7]->setCategory($homeGarden)->setImage('https://images.unsplash.com/photo-1578500494198-246f612d03b3?w=800&h=600&fit=crop');
        $products[8]->setCategory($homeGarden)->setImage('https://images.unsplash.com/photo-1565636192335-14e8bda3e9c3?w=800&h=600&fit=crop');

        foreach ($products as $product) {
            $this->products[$product->getSlug()] = $product;
        }
    }
}
