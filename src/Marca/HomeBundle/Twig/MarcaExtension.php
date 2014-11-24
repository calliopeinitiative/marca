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
            new \Twig_SimpleFilter('html_raw', array($this, 'filterHtml'), array('is_safe' => array('html'))),
            new \Twig_SimpleFilter('delete_modal', array($this, 'filterDelete_modal'), array('is_safe' => array('html')))
        );
    }

    public function filterHtml($string)
    {
        $html_raw = $string;
        return $html_raw;
    }

    public function filterDelete_modal($string)
    {
        $html_raw = <<<EOF
    <div id="delete_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <span class="modal-title">Delete</span>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete?</p>
                        $string
                    </div>
            </div>
        </div>
    </div>
EOF;

        return $html_raw;
    }


    public function getName()
    {
        return 'marca_extension';
    }

}