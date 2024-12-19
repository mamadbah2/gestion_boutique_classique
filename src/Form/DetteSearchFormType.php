<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Dette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;
class DetteSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('data', DateType::class, [
        //     'widget' => 'single_text',
        //     'required' => false,
        //     'label' => 'Date',
        //     'attr' => [
        //         'placeholder' => 'Choisir une date',
        //         'class' => 'shadow appearance-none border rounded w-[40%] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
        //     ],
        //     'empty_data' => '',
        // ])
        // ->add('montant', NumberType::class, [
        //     'required' => true,
        //     'label' => 'Montant',
        //     'attr' => [
        //         'placeholder' => 'Saisir le montant',
        //         'class' => 'shadow appearance-none border rounded w-[40%] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
        //     ],
        //     'constraints' => [
        //         new NotBlank(['message' => 'Le montant est requis']),
        //     ],
        // ])
        // ->add('montantVerser', NumberType::class, [
        //     'required' => false,
        //     'label' => 'Montant Versé',
        //     'attr' => [
        //         'placeholder' => 'Saisir le montant versé',
        //         'class' => 'shadow appearance-none border rounded w-[40%] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
        //     ],
        // ])
        ->add('client', EntityType::class, [
            'class' => Client::class,
            'choice_label' => 'id',
            'required' => true,
            'label' => 'Client',
            'attr' => [
                'class' => 'shadow appearance-none border rounded w-[40%] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
            ],
        ])
        ->add('status', ChoiceType::class, [
            'choices' => [
                'Actif' => 'actif',
                'Inactif' => 'inactif',
            ],
            'label' => 'Statut',
            'required' => true,
            'attr' => [
                'class' => 'shadow appearance-none border rounded w-[40%] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
            ],
        ])
        ->add('Search', SubmitType::class, [
            'attr' => [
                'class' => 'bg-[#1E375A] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline',
            ],
        ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dette::class,
        ]);
    }
}
