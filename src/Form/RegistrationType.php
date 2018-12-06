<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends Applicationtype
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Enter your firstname"))
            ->add('lastName', TextType::class, $this->getConfiguration("Enter your lastname"))
            ->add('email', EmailType::class, $this->getConfiguration("Enter your email address"))
            ->add('avatar', UrlType::class, $this->getConfiguration("URL of your avatar"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Choose your password"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirm your password"))
            ->add('intro', TextType::class, $this->getConfiguration("Please introduce your person in few words"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
