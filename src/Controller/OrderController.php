<?php

namespace App\Controller;

use App\Entity\CommandDetail;
use App\Entity\Commande;
use App\Form\OrderType;
use App\Repository\CommandeRepository;
use App\Services\Cart;
use App\Services\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class OrderController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {

    }

    #[Route('/commande', name: 'app.order')]
    
    public function index(Request $request, Cart $cart): Response
    {
        
        if(!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('app.account.add_address');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cartDatas' => $cart->getCartDatas()
        ]);
    }

    #[Route('/commande/recapitulatif', name: 'app.commande.recap', methods: ['POST'])]
    public function add(Request $request,
        Cart $cart, 
        CommandeRepository $commandeRepository, 
        OrderService $orderService)
    {

        $carrier = null;
        $address = null;
        $ordersExist = $commandeRepository->findByOrderByDate();
        
        $cartDatas = $cart->getCartDatas();
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);
        
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {

            $total = 0;
            $carrier = $form->get('carriers')->getData();
            $address = $form->get('addresses')->getData();

            $order = new Commande();
            if( count($ordersExist) > 0){
                $order->setCodeCmd($orderService->createOrderCode($ordersExist[0]));
            }
            else{
                $order->setCodeCmd($orderService->createOrderCode(null));
            }

            $order->setCarrierName($carrier->getName());
            $order->setDelivery($address->getFullName());
            $order->setDateCmd(new \DateTimeImmutable());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setStatus('created');
            $order->setClient($this->getUser());
            $this->em->persist($order);

            $productsForStripe = [];
            foreach ($cartDatas as $cart) {
                $orderDetail = new CommandDetail();
                $orderDetail->setProduct($cart[('product')]);
                $orderDetail->setQuantityCmd($cart['quantity']);
                $orderDetail->setCommand($order);
                $total += $cart['total'];

                $this->em->persist($orderDetail);

                $productsForStripe[] = [
                    'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $cart[('product')]->getPrice() * 100,
                    'product_data' => [
                        'name' => $cart[('product')]->getTitle(),
                        //'image' => $cart[('product')]->getDescription()
                    ],
                    ],
                    
                    'quantity' => $cart[('quantity')],
                ];
            }

            $this->em->flush();

            // Keep your Stripe API key protected by including it as an environment variable
            // or in a private script that does not publicly expose the source code.

            // This is your test secret API key.
            /*$stripeSecretKey = 'sk_test_51PGx8MRt5WnV5Wawtccvjxhg3uE9HlaVF98QUEMvOTrzEyViAEig6qvsHXZk0ni5uK8kWL0ulofGgK5LrIewbIST00P7ygguRc';

            Stripe::setApiKey($stripeSecretKey);
            $YOUR_DOMAIN = 'http://localhost:8000/';
            

            $checkout_session = Session::create([
            'line_items' => [
                $productsForStripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            ]);

            header("HTTP/1.1 303 See Other");
            header("Location: " . $checkout_session->url);*/

            return $this->render('order/add.html.twig', 
            [
                'cartDatas' => $cartDatas,
                'delivery' => $address,
                'carrier' => $carrier,
                'total' => $total,
                //'stripeCheckoutSession' => $checkout_session->id
            ]);
        }

        return $this->redirectToRoute('app.cart');
        
    }
}
