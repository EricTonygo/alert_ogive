<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoricalAlertSubscriberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', null, array('required' => false))
//                ->add('subscriber', 'entity', array(
//                    'class' => 'OGIVEAlertBundle:Subscriber',
//                    'property' => 'phoneNumber',
//                    'empty_value' => "Selectionner l'abonné",
//                    'multiple' => false,
//                    'required' => false,
//                    'query_builder' => function(\OGIVE\AlertBundle\Repository\SubscriberRepository $repo) {
//                        return $repo->getSubscriberQueryBuilder();
//                    }
//                ))
                    ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\HistoricalAlertSubscriber'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ogive_alertbundle_historicalAlertSubscriber';
    }


}
