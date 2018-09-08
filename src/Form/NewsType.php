<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\News;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('image')
            //->add('createdAt')
            // je peux pas écrire ->add('category') car c'est une relation
            // il faut que je précise ce qu'est ce champs
            ->add('category', EntityType::class, [
                'class' => Category::class, // option class obligatoire, dit de quelle entity on parle
                'choice_label' => 'title' // expliquer à mon champs ce qu'il doit présenter comme information
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class,
        ]);
    }
}
