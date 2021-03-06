<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SubscriberType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', null, array('required' => false))
                ->add('email', null, array('required' => false))
                ->add('phoneNumber', null, array('required' => false))
                ->add('notificationType', ChoiceType::class, array(
                    'choices' => array(
                        null => 'Select type notification',
                        3 => 'SMS et Email',
                        2 => 'SMS seulement',
                        1 => 'Email seulement',
                    ),
                    'expanded' => false,
                    'multiple' => false
                ))
                ->add('entreprise', 'entity', array(
                    'class' => 'OGIVEAlertBundle:Entreprise',
                    'property' => 'name',
                    'empty_value' => "Select une entreprise",
                    'multiple' => false,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\EntrepriseRepository $repo) {
                        return $repo->getEntrepriseQueryBuilder();
                    }
                ))
                ->add('subscription', 'entity', array(
                    'class' => 'OGIVEAlertBundle:Subscription',
                    'property' => 'name',
                    'empty_value' => "Select un abonnement",
                    'multiple' => false,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\SubscriptionRepository $repo) {
                        return $repo->getSubscriptionQueryBuilder();
                    }
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\Subscriber'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'ogive_alertbundle_subscriber';
    }

}
