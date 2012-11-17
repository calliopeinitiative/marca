<?php

/*
 * A data transformer to take colors from the color picker and turn them into css 
 * classes that the ckeditor css will understand
 */

namespace Marca\DocBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\DocBundle\Entity\Markup;

class ColorToCssTransformer implements DataTransformerInterface
{
   /**
    * @var ObjectManager
    */ 
    private $om;
    
    /**
     * @param ObjectManager $om $name Description
     */
    public function __construct(ObjectManager $om) {
        $this->om = $om;
    }
    
    /**
     * Transforms a string color in rgb into a string css class
     * @param string $css_class
     * @return string
     */
    public function transform($css_class) {
        $color = 'reversed';
        switch($css_class){
            case "mc1":
                $color = '#8db14f';
                break;
            case "mc2":
                $color =  'rgb(255,227,98)';
                break;
            case 'mc3':
                $color = "rgb(79,177,169)";
                break;
            case 'mc4':
                $color = "rgb(79,120,177)";
                break;
            case 'mc5':
                $color = "rgb(118,127,177)";
                break;
            case 'mc6':
                $color = "rgb(177,79,139)";
                break;
            case 'mc7':
                $color = "rgb(177,79,79)";
                break;
            case 'mc8':
                $color = "rgb(177,117,79)";
                break;
            case 'mc9':
                $color = "rgb(79,177,175)";
                break;
        }
        return $color;
    }
    
    /**
     * reverse transform
     * @param string $color
     * @return string
     */
    public function reverseTransform($color) {
        $css_class = 'test';
        if($color=='#8db14f'){
                $css_class = "mc1";         
        }
        elseif($color == 'rgb(255, 227, 98)'){
                $css_class = "mc2";
        }
        elseif($color == 'rgb(79, 177, 169)'){
                $css_class = "mc3";
        }
        elseif($color == 'rgb(79, 120, 177)'){
                $css_class = "mc4";
        }
        elseif($color == 'rgb(118, 127, 177)'){
                $css_class = "mc5";
        }
        elseif($color == 'rgb(177, 79, 139)'){
                $css_class = "mc6";
        }
        elseif($color == 'rgb(177, 79, 79)'){
                $css_class = "mc7";
        }
        elseif($color == 'rgb(177, 117, 79)'){
                $css_class = "mc8";
        }
        elseif($color ==  'rgb(79, 177, 175)'){
                $css_class = "mc9";
        }
        return $css_class;
        
        
    }
}
