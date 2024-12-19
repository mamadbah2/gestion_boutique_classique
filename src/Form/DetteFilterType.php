<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Dette;
use App\enum\StatusDette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DetteFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status' , ChoiceType::class, [
                'choices' => [
                    'Tous' => StatusDette::All->value,
                    'Paye'=> StatusDette::Paye->value,
                    'Impaye'=> StatusDette::Impaye->value,
                ],
                'attr' => [
                    'class' => 'block w-full md:w-64 px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-base focus:border-base'
                ],
                'label' => 'status',
                'required'=> false,
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
            'data_class' => Dette::class,
        ]);
    }
}
