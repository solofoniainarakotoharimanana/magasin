<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use App\Services\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    public function __construct(private Cart $cart)
    {

    }

    #[Route('/cart', name: 'app.cart', methods: ['GET'])]
    public function index(): Response
    {
        $cartData = $this->cart->getCartDatas();

        return $this->render('cart/index.html.twig', 
            [
                'cartDatas' => $cartData
            ]);
    }

    #[Route('/cart/decrease-quantity/{slug}', name: 'app.cart.decrease.quantity', methods: ['GET'])]
    public function decreaseQuantity(Products $product): Response
    {
        $this->cart->decreaseQuantity($product);

        return $this->redirectToRoute('app.cart');
    }

    #[Route('/cart/increase-quantity/{slug}', name: 'app.cart.increase.quantity', methods: ['GET'])]
    public function increaseQuantity(Products $product): Response
    {
        $this->cart->increaseQuantity($product);

        return $this->redirectToRoute('app.cart');
    }

    #[Route('/cart/delete-from-cart/{slug}', name: 'app.cart.delete.from.cart', methods: ['GET'])]
    public function deleteFromCart(Products $product): Response
    {
        $this->cart->deleteFromCart($product);

        return $this->redirectToRoute('app.cart');
    }
}
