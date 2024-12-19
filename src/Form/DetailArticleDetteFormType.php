<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\DetailArticleDette;
use App\Entity\Dette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class DetailArticleDetteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('article', EntityType::class, [
            'class' => Article::class,
            'choice_label' => 'libelle',
            'label' => 'Article',
        ])
        ->add('qte', NumberType::class, [
            'label' => 'Quantité',
        ])
        // ->add('total', NumberType::class, [
        //     'label' => 'Prix unitaire',
        // ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DetailArticleDette::class,
        ]);
    }
}
