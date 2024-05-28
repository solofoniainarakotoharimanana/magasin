<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class, 
            [
                'label' => 'Choisissez votre adresse de livraison',
                'required' => true,
                'class' => Address::class,
                'multiple' => false,
                'expanded' => true,
                'choices' => $user->getAddresses(),
                'attr' => [
                    'class' => 'check-info'
                ]
            ])
            ->add('carriers', EntityType::class, 
            [
                'label' => 'Choisissez transporteur preferé',
                'required' => true,
                'class' => Carrier::class,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'check-info'
                ]
                
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider la commande',
                'attr' => [
                    'class' => 'btn btn-success btn-sm w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => []
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
