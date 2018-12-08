<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class,
                [
                    'label' => 'routes.dateFrom',
                    'widget' => 'single_text',
                    'format' => 'dd.MM.yyyy',
                    'attr' => ['class' => 'datepicker'],
                ]
            )
            ->add('dateTo', DateType::class,
                [
                    'label' => 'routes.dateTo',
                    'widget' => 'single_text',
                    'format' => 'dd.MM.yyyy',
                    'attr' => ['class' => 'datepicker'],
                ]
            )
            ->add('Submit', SubmitType::class, array(
                'label' => 'routes.search',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Filter\RoutesFilter'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_filter';
    }


}
