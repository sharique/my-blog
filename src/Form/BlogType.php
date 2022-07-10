<?php

namespace App\Form;

use App\Entity\Blog;
use App\Entity\Tag;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('body',CKEditorType::class,['config' => array('toolbar' => 'basic'),])
            ->add('Tags', EntityType::class, [
              'class' => Tag::class,
              'choice_label' => 'name',
              'placeholder' => 'Please select tags.',
              'autocomplete' => true,
              'multiple' =>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
}
