<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/compte')]
class AccountController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {

    }
    #[Route('/', name: 'app.account')]
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

    #[Route('/add-address', name: 'app.account.add_address')]
    public function addAddress(Request $request): Response
    {
        $adress = new Address();
        $form = $this->createForm(AddressType::class, $adress);
        $form->handleRequest($request); 
        if($form->isSubmitted() && $form->isValid())
        {
            $adress->setClient($this->getUser());

            $this->em->persist($adress);
            $this->em->flush();

            return $this->redirectToRoute('app.account');
        }

        return $this->render('account/add_adresse.html.twig', 
            [
                'form' => $form->createView()
            ]);
    }

    #[Route('/update-address/{id}', name: 'app.account.update_address')]
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
