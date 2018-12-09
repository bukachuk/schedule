<?php

namespace AppBundle\Form\Type;

use AppBundle\Form\DataTransformer\RegionTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class RoutesType extends AbstractType
{
    private $regionTransformer;

    public function __construct(RegionTransformer $regionTransformer)
    {
        $this->regionTransformer = $regionTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class,
                [
                    'label' => 'routes.date',
                    'widget' => 'single_text',
                    'format' => 'dd.MM.yyyy',
                    'attr' => ['class' => 'datepicker'],
                ]
            )
            ->add('courier', EntityType::class, array(
                'class' => 'AppBundle\Entity\Courier',
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('p')->orderBy('p.id', 'DESC');
                },
                'label' => 'routes.courier',
                'empty_data'  => null,
            ))
            ->add('region', TextType::class, array(
                'label' => 'routes.region',
                'attr' => ['class' => 'autocomplete', 'placeholder' => 'routes.placeholder'],
            ))
            ->add('returnDate', TextType::class, array(
                'label' => 'routes.returnDate',
                'attr' => ['readonly' => true, 'class' => 'returnDate'],
                'mapped' => false,
            ))
            ->add('Submit', SubmitType::class, array(
                'label' => 'routes.add',
            ));
        $builder->get('region')->addModelTransformer($this->regionTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Routes'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_route';
    }


}
