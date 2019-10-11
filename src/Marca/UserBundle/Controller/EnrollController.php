<?php

namespace Marca\UserBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Marca\HomeBundle\Controller\Controller;
use Marca\UserBundle\Entity\User;
use Marca\UserBundle\Form\ProfileType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Enroll controller.
 *
 * @Route("/enroll")
 */
class EnrollController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
    }
    
    /**
     * @Route("/find", name="enroll_find")
     * @Template("MarcaUserBundle:Default:index.html.twig")
     */
    public function findCourseAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $id = $user->getId();
        if ($user->getInstitution()->getResearch()===true && $user->getResearch()==0){
        return $this->redirect($this->generateUrl('user_research', array('id' => $id)));
        }
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($user);
        $pending = $em->getRepository('MarcaCourseBundle:Course')->findPendingCourses($user);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findPendingRoll($user);
        $modules = $em->getRepository('MarcaCourseBundle:Course')->findModules($user);
        $archive = $em->getRepository('MarcaCourseBundle:Course')->findArchivedCourses($user);
        $courseids = $em->getRepository('MarcaCourseBundle:Course')->findUserCourseIds($user);

        $find_user = new User();
        $form = $this->createFormBuilder($find_user)
            ->add('lastname',TextType::class, array('label'  => ' ','attr' => array('class' => 'text form-control'),))
            ->getForm();

        $possible_courses = '';

        return array(
            'courses'=>$courses,
            'pending'=>$pending,
            'roll'=>$roll,
            'archive'=>$archive,
            'modules' => $modules,
            'courseids'=>$courseids,
            'possible_courses' => $possible_courses,
            'form'   => $form->createView()
        );
    }  
    
    /**
     * @Route("/list", name="enroll_list")
     * @Template("MarcaUserBundle:Default:index.html.twig")
     */
    public function listCourseAction(Request $request)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $courses = $em->getRepository('MarcaCourseBundle:Course')->findEnrolledCourses($user);
        $pending = $em->getRepository('MarcaCourseBundle:Course')->findPendingCourses($user);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findPendingRoll($user);
        $modules = $em->getRepository('MarcaCourseBundle:Course')->findModules($user);
        $archive = $em->getRepository('MarcaCourseBundle:Course')->findArchivedCourses($user);
        $courseids = $em->getRepository('MarcaCourseBundle:Course')->findUserCourseIds($user);
        
        $find_user = new User();
        $form = $this->createFormBuilder($find_user)
            ->add('lastname',TextType::class, array('label'  => ' ','attr' => array('class' => 'text form-control'),))
            ->getForm();

       $postData = $request->request->get('form');
       $lastname = $postData['lastname'];
       $em = $this->getEm(); 
       $possible_courses = $em->getRepository('MarcaCourseBundle:Course')->findCourseByLastname($lastname);
        
       return array(
           'courses'=>$courses,
           'pending'=>$pending,
           'roll'=>$roll,
           'archive'=>$archive,
           'modules' => $modules,
           'courseids'=>$courseids,
           'possible_courses' => $possible_courses,
           'form'   => $form->createView(),
        );
    }     
    
      /**
     * @Route("/{courseid}/enroll", name="enroll_enroll")
     */
    public function enrollCourseAction(Request $request, $courseid)
    {
       $user = $this->getUser();
       $course = $this->getCourse($request);
       $coupon = $user->getCoupon();
       if($this->get('security.context')->isGranted('ROLE_INSTR') === TRUE){
           $validCoupon = TRUE;
       }
       elseif($coupon == NULL){
           $validCoupon = FALSE;
       }
       elseif ($coupon->getTerm()->getStatus() == 1){
           $validCoupon = TRUE;
       }
       else {
           $validCoupon = FALSE;
       }
       if(($course->getInstitution()->getPaymentType() != 0 && $validCoupon == FALSE))
       {
            return $this->redirect($this->generateUrl('payment', array('courseid'=>$courseid)));
       }
       else
           {
            $em = $this->getEm(); 
            $em->getRepository('MarcaCourseBundle:Roll')->enroll($course, $user);
            return $this->redirect($this->generateUrl('user_home'));
        }  
    
    }  
}
