<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;


class SubscriptionType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', null, array('required' => false))
                ->add('periodicity', ChoiceType::class, array(
                    'choices' => array(
                        1 => 'Annuelle',
                        2 => 'Semestrielle',
                        3 => 'Trimestrielle',
                        4 => 'Mensuelle',
                        5 => 'Free' //the customer has subscribed for the Free Plan: Validity 2 Weeks
                    ),
                    'required' => false,
                    'placeholder' => 'Selectionner la périodicité',
                    'empty_data' => null
                ))
                ->add('price', null , array('required' => false))
                ->add('currency', CurrencyType::class , array('placeholder' => 'Selectionner la dévise','required' => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\Subscription'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'ogive_alertbundle_subscription';
    }

}
