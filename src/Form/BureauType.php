<?php

namespace App\Form;

use App\Entity\Bureau;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BureauType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'years' => range(date('Y') - 30, date('Y') + 10),
                'attr' => ['placeholder' => 'Date de début']
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin',
                'years' => range(date('Y') - 30, date('Y') + 10),
                'attr' => ['placeholder' => 'Date de fin']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bureau::class,
        ]);
    }
}
