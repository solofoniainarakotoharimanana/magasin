<?php

namespace App\Services;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart 
{

  public function __construct(private RequestStack $requestStack, 
                      private EntityManagerInterface $em,
                      private ProductsRepository $productsRepository)
  {

  }

  public function addToCart(int $productId, int $quantity)
  {
    $cart = $this->requestStack->getSession()->get('cart', []);

    if ( !empty($cart[$productId]) ) {
      $cart[$productId] += $quantity; 
    }
    else{
      $cart[$productId] = $quantity;
    }

    $this->requestStack->getSession()->set('cart', $cart);
  }

  public function getCart()
  {
    return $this->requestStack->getSession()->get('cart');
  }

  public function removeFromCart(int $productId)
  {
    $cart = $this->requestStack->getSession()->get('cart');

    unset($cart[$productId]);

    return $this->requestStack->getSession()->set('cart', $cart);  
  }

  public function getCartDatas()
  {
    $cartData = [];
    $fullCart = $this->getCart();

    if ( $fullCart ) {
      foreach ($fullCart as $productId => $quantity) {
        $product = $this->em->getRepository(Products::class)->find($productId);
        if ( $product ) {
          $cartData[] = [
            'product' => $product,
            'quantity' => $quantity,
            'total' => $product->getPrice() * $quantity
          ];
        } else
          $this->removeFromCart($productId);
      }
    }

    return $cartData;
  }

  public function decreaseQuantity(Products $product)
  {
    $cart = $this->requestStack->getSession()->get('cart');
    if ( $cart[$product->getId()] > 1) {
      $cart[$product->getId()]--;
    } else
      $cart[$product->getId()] = 1;

    $this->requestStack->getSession()->set('cart', $cart);
  }

  public function increaseQuantity(Products $product)
  {
    $cart = $this->requestStack->getSession()->get('cart');
    $cart[$product->getId()]++;

    $this->requestStack->getSession()->set('cart', $cart);
  }

  public function deleteFromCart(Products $product)
  {
    $cart = $this->requestStack->getSession()->get('cart');
    unset($cart[$product->getId()]);

    $this->requestStack->getSession()->set('cart', $cart);   
  }
}