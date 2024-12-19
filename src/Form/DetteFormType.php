<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Dette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class DetteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('data', DateType::class, [
            'widget' => 'single_text',
            'label' => 'Date de la dette',
        ])
        ->add('client', EntityType::class, [
            'class' => Client::class,
            'choice_label' => 'surname',
            'label' => 'Client',
        ])
        // ->add('montant', MoneyType::class, [
        //     'label' => 'Montant total',
        //     'currency' => 'F',
        // ])
        ->add('detailArticleDettes', CollectionType::class, [
            'entry_type' => DetailArticleDetteFormType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'label' => 'Articles',
        ])
        ->add('montantVerser', MoneyType::class, [
            'label' => 'Montant à verser (optionnel)',
            'currency' => 'F',
            'required' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dette::class,
        ]);
    }
}
