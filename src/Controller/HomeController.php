<?php

namespace App\Controller;

use App\Services\Cart;
use App\Entity\Products;
use App\Form\ProductDetailType;
use App\Repository\UserRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Services\Mail;
class HomeController extends AbstractController
{

  public function __construct(private ProductsRepository $productsRepository)
  {

  }

  #[Route(path:"/", name:"app.home", methods: ['GET'])]
  public function home(Request $request, PaginatorInterface $paginator):Response
  {

    //test email
    //$mail = new Mail;
    //$mail->send("solofoniainarakotoharimanana@gmail.com", "solofoniaina", "mail test", "Test envoi email");

    $products = $this->productsRepository->getAllProductsActive($request->query->getInt('page', 1));
    //dd($products);
    return $this->render("/home/index.html.twig", 
      [
        'products' => $products
      ]
    );
  }

  #[Route(path: "/produit/{slug}", name: "product.detail", methods: ['GET', 'POST'])]
  public function productDetail(Request $request,Products $product, Cart $cart): Response
  {
    $cartData = [];
    $form = $this->createForm(ProductDetailType::class);

    $form->handleRequest($request);
    if ( $form->isSubmitted()) {
      $cart->addToCart(intval($request->request->get('product_id')), intval($request->request->get('quantity')));
      
      return $this->redirectToRoute('app.cart');
    }
       
    return $this->render("/home/detail.html.twig", 
      [
        'form' => $form->createView(),
        'product' => $product
      ]);
  }

}