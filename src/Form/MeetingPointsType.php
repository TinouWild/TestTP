<?php

namespace App\Form;

use App\Entity\MeetingPoint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeetingPointsType extends Applicationtype
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('place', TextType::class, $this->getConfiguration("Please enter the address"))
            ->add('time', TimeType::class)
            ->add('details', TextareaType::class, $this->getConfiguration("Please enter the details !"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MeetingPoint::class,
        ]);
    }
}
