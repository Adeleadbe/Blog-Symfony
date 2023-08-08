<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'Titre de l\'article']
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control', 
                    'rows' => 8,
                    'placeholder' => 'Contenu de l\'article']
            ])
            ->add('author', null, [
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'Auteur de l\'article']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
