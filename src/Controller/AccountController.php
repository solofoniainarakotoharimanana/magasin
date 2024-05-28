<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Security\Voter\AddressVoter;
use App\Services\Cart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/compte')]
class AccountController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {

    }
    #[Route('/', name: 'app.account', methods: ['GET'])]
    public function index(): Response
    {
        $addresses = null;
        if ($this->getUser()) {
            $addresses = $this->getUser()->getAddresses()->getValues();
        }
        return $this->render('account/adresse.html.twig', 
            [
                'addresses' => $addresses
            ]
        );
    }

    #[Route('/add-address', name: 'app.account.add_address', methods: ['GET'])]
    public function addAddress(Request $request, Cart $cart): Response
    {
        $adress = new Address();
        $form = $this->createForm(AddressType::class, $adress);
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid())
        {
            $adress->setClient($this->getUser());

            $this->em->persist($adress);
            $this->em->flush();

            if ($cart->getCartDatas()) {
                return $this->redirectToRoute('app.order');
            }
            else{
                return $this->redirectToRoute('app.account');
            }
            
        }

        return $this->render('account/add_adresse.html.twig', 
            [
                'form' => $form->createView()
            ]);
    }

    #[Route('/update-address/{id<\d+>}', name: 'app.account.update_address', methods: ['GET', 'POST'])]
    #[IsGranted(AddressVoter::UPDATE, subject: 'address')]
    public function updateAddress(Request $request, Address $address): Response
    {
        
        if(!$address && $address->getClient() != $this->getUser())
        {
            $this->redirectToRoute('app.account');
        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid())
        {
            $address->setClient($this->getUser());

            //$this->em->persist($address);
            $this->em->flush();

            return $this->redirectToRoute('app.account');
        }

        return $this->render('account/add_adresse.html.twig', 
            [
                'form' => $form->createView()
            ]);
    }
}
