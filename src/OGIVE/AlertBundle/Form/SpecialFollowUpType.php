<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecialFollowUpType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', null, array('required' => false))
                ->add('description', null, array('required'=> false))
//                ->add('subscribers', 'entity', array(
//                    'class' => 'OGIVEAlertBundle:Subscriber',
//                    'property' => 'phoneNumber',
//                    'empty_value' => "Selectionner ces abonnés",
//                    'multiple' => true,
//                    'required' => false,
//                    'query_builder' => function(\OGIVE\AlertBundle\Repository\SubscriberRepository $repo) {
//                        return $repo->getSubscriberEnableOrDisableQueryBuilder();
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
            'data_class' => 'OGIVE\AlertBundle\Entity\SpecialFollowUp'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ogive_alertbundle_specialFollowUp';
    }


}
