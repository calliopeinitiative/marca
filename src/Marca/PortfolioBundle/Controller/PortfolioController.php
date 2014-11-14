<?php

namespace Marca\PortfolioBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\PortfolioBundle\Entity\Portfolio;
use Marca\PortfolioBundle\Form\PortfolioType;
use Marca\PortfolioBundle\Entity\Portfolioset;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Portfolio controller.
 *
 * @Route("/portfolio")
 */
class PortfolioController extends Controller
{

    /**
     * Create ESI Sidebar fragment
     *
     * @Route("/{courseid}/sidebar", name="portfolio_sidebar")
     */
    public function createSidebarAction($courseid)
    {
        $role = $this->getCourseRole();
        $course = $this->getCourse();
        $portStatus = $course->getPortStatus();
        return $this->render('MarcaPortfolioBundle::sidebar.html.twig', array(
            'role'=> $role,
            'portStatus'=> $portStatus
        ));
    }

    /**
     * Lists all Portfolio entities.
     *
     * @Route("/{courseid}", name="portfolio")
     */
    public function indexAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT, self::ROLE_PORTREVIEW);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $portStatus = $course->getPortStatus();

        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $portset = $course->getPortset();
        
        //find default portfolio
        $portfoliosets = $em->getRepository('MarcaPortfolioBundle:Portfolioset')->findByUser($user,$course);
        //select the first in the array
        $portfolioset = reset($portfoliosets);
        
        // if there is no portfolio for this user in this class, create one
        if (!$portfoliosets) {
            $portfolioset = new Portfolioset(); 
            $portfolioset->setCourse($course);
            $portfolioset->setUser($user);
            $em->persist($portfolioset);
            $em->flush(); 
        }
        
        $assessmentset_id = $course->getAssessmentset()->getId();
        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->find($assessmentset_id);

        return $this->render('MarcaPortfolioBundle:Portfolio:index.html.twig', array(
            'portfolioset' =>$portfolioset,
            'portset' => $portset,
            'roll'=> $roll,
            'assessmentset'=> $assessmentset,
            'portStatus'=> $portStatus
        ));
    }
    
    
    /**
     * Finds and displays files to be included in the portfolio
     *
     * @Route("/{courseid}/{project}/{portitemid}/find", name="portfolio_find")
     */
    public function findAction($project, $courseid, $portitemid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $session = $this->get('session'); 
        $request = $this->getRequest();
        $session->set('port_referrer', $request->getRequestUri());
        
        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole();
        $course = $this->getCourse();
        $portStatus = $course->getPortStatus();
        $files = $em->getRepository('MarcaFileBundle:File')->findFilesForPort($user, $course);

        return $this->render('MarcaPortfolioBundle:Portfolio:find.html.twig', array(
            'files' => $files,
            'course' => $course,
            'portStatus'=> $portStatus,
            'role'=> $role,
            'portitemid'=> $portitemid
        ));
    }   
    
    /**
     * Finds and displays a Portfolio entity for remove confirm.
     *
     * @Route("/{courseid}/{id}/show_modal", name="portfolio_show_modal")
     */
    public function showModalAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);     
        $em = $this->getEm();
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);
        $deleteForm = $this->createDeleteForm($id);
            
        return $this->render('MarcaPortfolioBundle:Portfolio:show_modal.html.twig', array(
            'portfolio' =>$portfolio,
            'delete_form' => $deleteForm->createView()
        ));
    }    



    /**
     * Finds and displays a Portfolio entity.
     *
     * @Route("/{courseid}/{userid}/{user}/show", name="portfolio_user")
     */
    public function showAction($courseid, $userid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT, self::ROLE_PORTREVIEW);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        if ($userid == '0')
        {$user = $this->getUser();$userid = $user->getId();}
        else 
        {$user = $em->getRepository('MarcaUserBundle:User')->find($userid);}
        
        $course = $this->getCourse();
        $role = $this->getCourseRole();
        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByCourse($course);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        //find default portfolio
        $portfoliosets = $em->getRepository('MarcaPortfolioBundle:Portfolioset')->findByUser($user,$course);
        //select the first in the array
        $portfolioset = reset($portfoliosets);

        $assessmentset = $course->getAssessmentset();
        
        //pagination for portfolio file is there is a portfolio present
        $paginator = $this->get('knp_paginator');
        if ($portfoliosets)
        {
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->findShownOnly($user,$course);
        $portfoliosetid = $portfolioset->getId();
        $portfolio_docs = $paginator->paginate($portfolio,$this->get('request')->query->get('page', 1),1);
        $ratingset = $em->getRepository('MarcaAssessmentBundle:Ratingset')->ratingsetsByPortfolioset($portfolioset);
        }
        else {$portfolio = '';$portfolio_docs = ''; $portfoliosetid = '0';$ratingset = '';}
        
        return $this->render('MarcaPortfolioBundle:Portfolio:show.html.twig', array(
            'portfoliosetid' => $portfoliosetid,
            'portfolio' =>$portfolio,
            'portfolio_docs' => $portfolio_docs,
            'assessmentset'=> $assessmentset,
            'ratingset' => $ratingset,
            'userid'=>$userid,
            'role' => $role,
            'markup' => $markup,
            'roll' => $roll
        ));
    }    


    /**
     * Displays a form to edit an existing Portfolio entity.
     *
     * @Route("/{courseid}/{id}/edit", name="portfolio_edit")
     */
    public function editAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $course = $this->getCourse();
        $portStatus = $course->getPortStatus();
        $role = $this->getCourseRole();
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);

        if($portStatus!='true'){
            throw new AccessDeniedException();
        }

        if (!$portfolio) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $editForm = $this->createForm(new PortfolioType($options), $portfolio);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MarcaPortfolioBundle:Portfolio:edit.html.twig', array(
            'portfolio'      => $portfolio,
            'portStatus'      => $portStatus,
            'role' => $role,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Edits an existing Portfolio entity.
     *
     * @Route("/{courseid}/{id}/update", name="portfolio_update")
     * @Method("post")
     */
    public function updateAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $options = array('courseid' => $courseid);
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);

        if (!$portfolio) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $editForm   = $this->createForm(new PortfolioType($options), $portfolio);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($portfolio);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio', array('courseid' => $courseid)));
        }

        return $this->render('MarcaPortfolioBundle:Portfolio:edit.html.twig', array(
            'portfolio'      => $portfolio,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Adds a new Portfolio entity and redirects to edit for Portitem and Portorder.
     *
     * @Route("/{courseid}/{fileid}/{portitemid}/add", name="portfolio_add")
     * @Template()
     */
    public function addAction($courseid, $fileid, $portitemid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();

        //find default portfolio
        $portfoliosets = $em->getRepository('MarcaPortfolioBundle:Portfolioset')->findByUser($user,$course);
        //select the first in the array
        $portfolioset = reset($portfoliosets);

        $file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $portitem = $em->getRepository('MarcaPortfolioBundle:Portitem')->find($portitemid);
        $portfolio = new Portfolio();
        $portfolio->setFile($file);
        $portfolio->setUser($user);
        $portfolio->setCourse($course);
        $portfolio->setPortitem($portitem);
        $portfolio->setPortfolioset($portfolioset);
        $em->persist($portfolio);
        $em->flush();
        return $this->redirect($this->generateUrl('portfolio', array('courseid' => $courseid)));
    }


    /**
     * Deletes a Portfolio entity.
     *
     * @Route("/{courseid}/{id}/delete", name="portfolio_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);

            if (!$portfolio) {
                throw $this->createNotFoundException('Unable to find Portfolio entity.');
            }

            $em->remove($portfolio);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('portfolio', array('courseid' => $courseid)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * Finds and displays a Portfolio entity for remove confirm.
     *
     * @Route("/{courseid}/toggle_status", name="port_status_toggle")
     */
    public function toggleStatusAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        $em = $this->getEm();
        $course = $this->getCourse();
        if ($course->getPortStatus()=='true') {
            $course->setPortStatus(false);
        }
        else {
            $course->setPortStatus(true);
        }
        $em->persist($course);
        $em->flush();

        return $this->redirect($this->generateUrl('portfolio', array('courseid' => $courseid)));
    }


}
