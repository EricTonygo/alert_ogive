<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('email')
                ->add('phoneNumber')
                ->add('entreprise', 'entity', array(
                    'class' => 'OGIVEAlertBundle:Entreprise',
                    'property' => 'name',
                    'empty_value' => "Selectionner une entreprise",
                    'multiple' => false,
                    'required' => false,
                    'query_builder' => function(\OGIVE\AlertBundle\Repository\EntrepriseRepository $repo) {
                        return $repo->getEntrepriseQueryBuilder();
                    }
                ))
                ->add('subscription', 'entity', array(
                    'class' => 'OGIVEAlertBundle:Subscription',
                    'property' => 'name',
                    'empty_value' => "Selectionner un abonnement",
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\Subscriber'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ogive_alertbundle_subscriber';
    }


}
