<?php

namespace OGIVE\AlertBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('phone', null, array('required' => false))
                ->add('email', null, array('required' => false))
                //->add('latitude')
                //->add('longitude')
                ->add('mailBox', null, array('required' => false))
                ->add('place', null, array('required' => false))
                //->add('status')
                ->add('country', CountryType::class, array('placeholder' => 'Selectionner le pays', 'empty_data'=>'CM', 'required' => false))
                ->add('street', null, array('required' => false))
//                ->add('file', FileType::class, array(
//                    'attr' => array('class' => 'inputfile'),
//                    'required' => false
//                ))
               ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OGIVE\AlertBundle\Entity\Address'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ogive_alertbundle_address';
    }


}
