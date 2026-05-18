<?php

namespace App\Controller;

use App\Cart\Cart;
use App\Cart\Handler\CartHandler;
use App\Service\CatalogService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogController extends AbstractController
{
    public function __construct(private CartHandler $cartHandler)
    {
    }

    #[Route('/', name: 'browse_categories')]
    public function browseCategories(CatalogService $catalogService): Response
    {
        return $this->render('files/browse_categories.html.twig', [
            'categories' => $catalogService->getCategories(),
        ]);
    }

    #[Route('/category/{slug}', name: 'products_by_category')]
    public function productsByCategory(string $slug, CatalogService $catalogService): Response
    {
        $category = $catalogService->getCategoryBySlug($slug);

        if (!$category) {
            throw new NotFoundHttpException('Category not found.');
        }

        return $this->render('files/products_by_category.html.twig', [
            'category' => $category,
            'products' => $catalogService->getProductsByCategory($category),
        ]);
    }

    #[Route('/product/{slug}', name: 'product_details')]
    public function productDetails(string $slug, CatalogService $catalogService): Response
    {
        $product = $catalogService->getProductBySlug($slug);

        if (!$product) {
            throw new NotFoundHttpException('Product not found.');
        }

        return $this->render('files/product_details.html.twig', [
            'product' => $product,
            'category' => $product->getCategory(),
        ]);
    }

    #[Route('/cart/add', name: 'cart_add', methods: ['POST'])]
    public function addToCart(Request $request, CatalogService $catalogService): RedirectResponse
    {
        $slug = (string) $request->request->get('productSlug', '');
        $quantity = max(1, (int) $request->request->get('quantity', 1));

        $product = $catalogService->getProductBySlug($slug);

        if (!$product) {
            throw new NotFoundHttpException('Product not found.');
        }

        $cart = $this->cartHandler->getCart();
        $this->cartHandler->addProduct($cart, $product, $quantity);

        return $this->redirectToRoute('product_details', ['slug' => $slug]);
    }
}
