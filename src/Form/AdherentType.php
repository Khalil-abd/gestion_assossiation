<?php

namespace App\Form;

use App\Entity\Adherent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdherentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Nom']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Prénom']
            ])
            ->add('cin', TextType::class, [
                'label' => 'CIN',
                'attr' => ['placeholder' => "Carte d'identité nationale"]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'adresse']
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['maxlength' => 10, 'placeholder' => 'Numero du téléphone']
            ])
            ->add('dateNaissance', DateType::class, [
                'label' => 'Date de naissance',
                'years' => range(date('Y') - 80, date('Y') - 3),
                'attr' => ['placeholder' => 'Date de naissance']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adherent::class,
        ]);
    }
}
