<?php

namespace Marca\AdminBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Marca\UserBundle\Entity\User;
use FOS\UserBundle\Entity\UserManager;

/**
 * Collects user payments for Marca instances requiring payment
 *
 * @route("/payment", name="payment")
 */
class PaymentController {
    /**
     * @Route("/", name="payment")
     * @Template()
     */
    public function collectAction()
    {
        $testtext = "hello world"; 
        return array('testtext'=>$testtext);
    }
    
    /**
     * @Route("/transaction", name="transaction_modal")
     * @Template()
     */
    public function transactionAction()
    {
        $testtext = "Pay Up!"; 
        return array('testtext'=>$testtext);
    }
}


