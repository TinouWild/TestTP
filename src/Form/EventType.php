<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends Applicationtype
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Please enter a title"))
            ->add('description', TextareaType::class, $this->getConfiguration("Please enter a description"))
            ->add('picture', EntityType::class, ['class' => Picture::class, 'choice_label' => 'id', 'label' => false])
            ->add('meeting_Points', CollectionType::class,
                [
                    'entry_type' => MeetingPointsType::class,
                    'allow_add'=> true,
                    'allow_delete' => true
                ])
            ->add('guest', CollectionType::class,
                [
                    'entry_type' => RegistrationType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
