<?php

namespace App\Form;

use App\Entity\Cabinet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CabinetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>"Nom de votre cabinet :",
            ])
            ->add('adresse', TextType::class,[
                'label'=>"L'adresse de votre cabinet :",
            ])
            ->add('zipcode', TextType::class,[
                'label'=>"Le code postal :",
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]{4}$/',
                        'message' => 'Le code postal est composé de 4 chiffres.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'Le code postal ne doit contenir que des chiffres.'
                    ]),
                ]
            ])
            ->add('contactEmail', EmailType::class,[
                'label'=>"Votre e-mail de contact :",
            ])
            ->add('numBCE', TextType::class,[
                'label'=>"Votre numéro d'enregistrement BCE :",
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^0[0-9]{9}$/',
                        'message' => 'Le numéro BCE est composé de 10 chiffes dont le premier est 0.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'Le numéro BCE ne doit contenir que des chiffres.'
                    ]),
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => 'save-btn'
                ],                
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cabinet::class,
        ]);
    }
}
