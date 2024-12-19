<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> 'Nom',
                'attr' => [
                    'placeholder' => 'Nom de l\'utilisateur',
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                ],
                'required' => false,
                
            ])
            ->add('prenom',TextType::class, [
                'label'=> 'prenom',
                'attr' => [
                    'placeholder' => 'Prenom de l\'utilisateur',
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                ],
                'required' => false,
            ])
            ->add('login', TextType::class, [
                'label'=> 'Login',
                'attr' => [
                    'placeholder' => 'Email de l\'utilisateur',
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                ],
                'required' => false,
            ])
            // ->add('roles', ChoiceType::class, [ 
            //     'choices' => [ 
            //         'Admin' => 'ROLE_ADMIN', 
            //         'Boutiquier' => 'ROLE_BOUTIQUIER', 
            //         'client' => 'ROLE_CLIENT', 
            //     ], 
            //     'multiple' => true, 
            //     'expanded' => true, 
            // ])

            ->add('password', TextType::class, [
                'label'=> 'Password',
                'attr' => [
                    'placeholder' => 'Password',
                    'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline',
                ],
                'required' => false,
            ])
            // ->add('isActive', CheckboxType::class,[
            //     'label'=> 'Compte active ?',
            //     'attr' => [
            //         'placeholder' => '',
            //         'class' => 'form-checkbox h-5 w-5',
            //     ],
            //     'required' => false,
            //     // 'property_path' => 'isActive'
            // ]
            // )
            // ->add('client', EntityType::class, [
            //     'class' => Client::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
