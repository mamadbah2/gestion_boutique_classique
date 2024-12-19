<?php

namespace App\Form;

use App\Dtos\ClientDto;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchClientFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('surname', TextType::class, [
            'required' => false,
            'label' => 'Surname',
            'attr' => [
                'placeholder' => 'surname du client',
                'class' => 'shadow appearance-none border rounded w-[40%] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
            ],
            'empty_data' => '',
            'constraints' => [
             
            ]
        ])
        ->add('telephone', TelType::class, [
            'required' => false,
            'empty_data' => '',
            'label' => '',
            'attr' => [
                'placeholder' => 'Téléphone du client',
                'class' => 'shadow appearance-none border rounded w-[40%] py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
            ],
            'constraints' => [
                // new NotBlank([
                //     'message'=> 'Veuillez saisir un numero valide'
                // ]),
              

                new Regex('/^(7[07-8][0-9]{7})$/', 'Format : 77XXXXXX ou 78XXXXXX ou 76XXXXXX')
            ]
        ])

        ->add(  'Search', SubmitType::class, [
            'attr' => [
                'class' => 'bg-[#1E375A] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline'
            ]
        ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
             'data_class' => ClientDto::class,
        ]);
    }
}
