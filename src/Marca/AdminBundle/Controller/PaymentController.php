<?php

namespace Marca\AdminBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Marca\UserBundle\Entity\User;
use Marca\AdminBundle\Entity\Coupon;
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
        $course=$this->getCourse();
        if($course->getInstitution()->getPaymentType() == 2){
            $publishableKey = $this->container->getParameter('stripe_publishable_key');
            return array('publishableKey' => $publishableKey, 'courseid' => $courseid, 'paymenttype' => $course->getInstitution()->getPaymentType());
        }
        if($course->getInstitution()->getPaymentType()==1 || $course->getInstitution()->getPaymentType() == 3){
            $coupon = new Coupon();
            $coupon_form = $this->createFormBuilder($coupon)->add('code')->getForm();
            return array('courseid'=>$courseid, 'paymenttype' => $course->getInstitution()->getPaymentType(), 'coupon_form' => $coupon_form->createView());
        }
        
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

    /**
     * @Route("/coupon_validate/{courseid}", name="coupon_validate")
     * 
     */
    public function couponValidateAction($courseid)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $institution = $user->getInstitution();
        
        $termQuery = $em->createQuery(
                'SELECT t 
                 FROM MarcaCourseBundle:Term t
                 WHERE t.institution = :institution
                 AND t.status = 1'
                )->setParameter('institution', $institution);
        $term = $termQuery->getSingleResult();
        $termId = $term->getId();
        
        $coupon = New Coupon(); 
        $coupon_form = $this->createFormBuilder()->add('code')->getForm();
        $request = $this->get('request');
        $postData = $request->request->get('form');
        $code = $postData['code'];
        $codeQuery = $em->createQuery(
                'SELECT c
                 FROM MarcaAdminBundle:Coupon c
                 WHERE c.term = :term
                 AND c.code = :code')->setParameter('term', $term)->setParameter('code', $code);
        try{ 
            $validCode = $codeQuery->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {
            $validCode = null;
        }
        if($validCode){
            $user->setCoupon($validCode);
            $validCode->setUser($user);
            $em->persist($user);
            $em->persist($validCode);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('enroll_enroll', array('courseid'=>$courseid)));
    }
    
    
}


