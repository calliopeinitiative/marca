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
class PaymentController extends Controller{
    
    protected function stripeConfig()
    {
        require_once($this->container->getParameter('kernel.root_dir') . '/../src/Marca/AdminBundle/Resources/lib/stripe-php-1.7.15/lib/Stripe.php');
        $stripe_secret = $this->container->getParameter('stripe_secret_key');
        $stripe_publishable = $this->container->getParameter('stripe_publishable_key');
        $stripe = array(
            "secret_key" => $stripe_secret,
            "publishable_key" => $stripe_publishable
        );
        \Stripe::setApiKey($stripe['secret_key']);
    }
    
    /**
     * @Route("/{courseid}", name="payment")
     * @Template()
     */
    public function collectAction($courseid)
    {
        
        $this->stripeConfig();
        $publishableKey = $this->container->getParameter('stripe_publishable_key');
        return array('publishableKey' => $publishableKey, 'courseid' => $courseid);;
    }

    
    /**
     * @Route("/charge/{courseid}", name="charge")
     * @Method("post")
     */
    public function chargeAction($courseid)
    {
        $user = $this->getUser();
        $em = $this->getEm();
        $this->stripeConfig();
        $token  = $_POST['stripeToken'];

        $customer = \Stripe_Customer::create(array(
            'email' => 'customer@example.com',
            'card'  => $token
        ));

        $charge = \Stripe_Charge::create(array(
            'customer' => $customer->id,
            'amount'   => 5000,
            'currency' => 'usd'
        ));
        
        $user->setCustomer_id($customer->id);
        $em->persist($user);
        $em->flush();
        
        return $this->redirect($this->generateUrl('enroll_enroll', array('courseid'=>$courseid)));
    }
}


