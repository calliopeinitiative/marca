<?php

namespace Marca\AdminBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Marca\UserBundle\Entity\User;
use Marca\AdminBundle\Entity\Coupon;


/**
 * Collects user payments for Marca instances requiring payment
 *
 * @route("/payment", name="payment")
 */
class PaymentController extends Controller{
    
    protected function stripeConfig()
    {
        $stripe_secret = $this->container->getParameter('stripe_secret_key');
        $stripe_publishable = $this->container->getParameter('stripe_publishable_key');
        $stripe = array(
            "secret_key" => $stripe_secret,
            "publishable_key" => $stripe_publishable
        );
        \Stripe::setApiKey($stripe['secret_key']);
    }
    
    /**
     * @Route("/select/{courseid}", name="select_payment")
     */
    public function selectPaymentAction (Request $request, $courseid)
    {
        $defaultData = array('message' => 'Type your message here');
        $form = $this->createFormBuilder($defaultData)
        ->add('name', 'text')
        ->add('email', 'email')
        ->add('message', 'textarea')
        ->add('send', 'submit')
        ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
        }
    }
    
    /**
     * @Route("/{courseid}", name="payment")
     * @Template()
     */
    public function collectAction(Request $request, $courseid)
    {
        
        $this->stripeConfig();
        $course=$this->getCourse($request);
        if($course->getInstitution()->getPaymentType() == 3){
            $publishableKey = $this->container->getParameter('stripe_publishable_key');
            $coupon = new Coupon();
            $coupon_form = $this->createFormBuilder($coupon)->add('code')->getForm();
            return array('publishableKey' => $publishableKey, 'courseid' => $courseid, 'paymenttype' => $course->getInstitution()->getPaymentType(), 'paymentamount' => $course->getInstitution()->getSemesterPrice(), 'coupon_form' => $coupon_form->createView());
        }
        if($course->getInstitution()->getPaymentType() == 2){
            $publishableKey = $this->container->getParameter('stripe_publishable_key');
            return array('publishableKey' => $publishableKey, 'courseid' => $courseid, 'paymenttype' => $course->getInstitution()->getPaymentType(), 'paymentamount' => $course->getInstitution()->getSemesterPrice());
        }
        if($course->getInstitution()->getPaymentType()== 1){
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
 
        $this->stripeConfig();
        $token  = $_POST['stripeToken'];

        try{ 
            $customer = \Stripe_Customer::create(array(
            'email' => $user->getEmail(),
            'card'  => $token
        ));
        }
        catch(\Stripe_CardError $e){
            return $this->redirect($this->generateUrl('payment', array('courseid'=>$courseid)));
        }

       try{ 
       $charge = \Stripe_Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $institution->getSemesterPrice(),
            'currency' => 'usd'
        ));
       }
       catch(\Stripe_CardError $e){
            return $this->redirect($this->generateUrl('payment', array('courseid'=>$courseid)));
        }
 
        if($user->getCoupon())
        {
            $coupon = $user->getCoupon();
            $user->addOldcoupon($coupon);
            $coupon->setPastuser($user);
            $coupon->setUser(NULL);
            $user->setCoupon(NULL);
            $em->persist($coupon);
            $em->persist($user);
            $em->flush();
        }
        $coupon = New Coupon();
        $coupon->setCode($customer->id);
        $coupon->setTerm($term);
        $coupon->setUser($user);
        $user->setCoupon($coupon);
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
                 WHERE c.code = :code
                 AND c.user IS NULL
                 AND c.pastuser IS NULL')->setParameter('code', $code);
        try{ 
            $validCode = $codeQuery->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {
            $validCode = null;
        }
        if($validCode){
            if($user->getCoupon())
            {
                $oldcoupon = $user->getCoupon();
                $user->addOldcoupon($oldcoupon);
                $oldcoupon->setPastuser($user);
                $oldcoupon->setUser(NULL);
                $user->setCoupon(NULL);
                $em->persist($oldcoupon);
                $em->persist($user);
                $em->flush();
            }
            $user->setCoupon($validCode);
            $validCode->setUser($user);
            $validCode->setTerm($term);
            $em->persist($user);
            $em->persist($validCode);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('enroll_enroll', array('courseid'=>$courseid)));
    }
    
    
}


