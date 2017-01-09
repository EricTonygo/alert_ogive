<?php

namespace AppBundle\Twig;

/**
 * Description of AppExtension
 *
 * @author Eric TONYE
 */
class AppExtension extends \Twig_Extension {

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('country_name', function($value) {
                        return \Symfony\Component\Intl\Intl::getRegionBundle()->getCountryName($value);
                    }),
            new \Twig_SimpleFilter('periodicity', function($value) {
                        switch ($value){
                            case 1:
                                return "Annuelle";
                            case 2:
                                return "Mensuelle";
                            case 3:
                                return "Hebdomadaire";
                        }
                    }),
        );
    }

    public function getName() {
        return 'app_extension';
    }

}
