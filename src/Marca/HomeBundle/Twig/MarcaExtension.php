<?php
/**
 * Created by PhpStorm.
 * User: rlbaltha
 * Date: 11/11/14
 * Time: 12:21 PM
 */

// src/Marca/HomeBundle/Twig/MarcaExtension.php
namespace Marca\HomeBundle\Twig;


class MarcaExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('html_raw', array($this, 'filterHtml'), array('is_safe' => array('html')))
        );
    }

    public function filterHtml($string)
    {
        $html_raw = $string;
        return $html_raw;
    }

    public function getName()
    {
        return 'marca_extension';
    }

}