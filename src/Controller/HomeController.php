<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

  public function __construct(private ProductsRepository $productsRepository)
  {

  }

  #[Route(path:"/", name:"app.home")]
  public function home(Request $request, PaginatorInterface $paginator):Response
  {
    $products = $this->productsRepository->getAllProductsActive($request->query->getInt('page', 1));
    //dd($products);
    return $this->render("/home/index.html.twig", 
      [
        'products' => $products
      ]
    );
  }

}