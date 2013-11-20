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
     * Lists all Portfolio entities.
     *
     * @Route("/{courseid}", name="portfolio")
     * @Template()
     */
    public function indexAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT, self::ROLE_PORTREVIEW);
        $this->restrictAccessTo($allowed);

        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
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
        return array('portfolioset' =>$portfolioset, 'portset' => $portset, 'roll'=> $roll, 'assessmentset'=> $assessmentset,'portStatus'=> $portStatus, 'role'=> $role);
    }
    
    
    /**
     * Finds and displays files to be included in the portfolio
     *
     * @Route("/{courseid}/{project}/find", name="portfolio_find")
     * @Template()
     */
    public function findAction($project, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);

        $session = $this->get('session'); 
        $request = $this->getRequest();
        $session->set('port_referrer', $request->getRequestUri());
        
        $em = $this->getEm();
        $user = $this->getUser();
        $role = $this->getCourseRole();

        $resource = 'false';
        $course = $this->getCourse();
        $portStatus = $course->getPortStatus();

        $files = $em->getRepository('MarcaFileBundle:File')->findFilesForPort($project, $user, $course);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);

        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $files = $paginator->paginate($files,$this->get('request')->query->get('page', 1),15);
        $count = $files->getTotalItemCount();

        return array('files' => $files, 'count' => $count, 'projects' => $projects, 'active_project' => $project, 'course' => $course, 'roll' => $roll, 'portStatus'=> $portStatus, 'role'=> $role);
    }   
    
    /**
     * Finds and displays a Portfolio entity for remove confirm.
     *
     * @Route("/{courseid}/{id}/show_modal", name="portfolio_show_modal")
     * @Template("MarcaPortfolioBundle:Portfolio:show_modal.html.twig")
     */
    public function showModalAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);     
        $em = $this->getEm();
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);
        $deleteForm = $this->createDeleteForm($id);
            
        return array('portfolio' =>$portfolio,'delete_form' => $deleteForm->createView(),);
    }    



    /**
     * Finds and displays a Portfolio entity.
     *
     * @Route("/{courseid}/{userid}/{user}/portfolio_byuser", name="portfolio_user")
     * @Template("MarcaPortfolioBundle:Portfolio:show.html.twig")
     */
    public function portByUserAction($courseid, $userid)
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
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $markup = $em->getRepository('MarcaDocBundle:Markup')->findMarkupByCourse($course);
        
        //find default portfolio
        $portfoliosets = $em->getRepository('MarcaPortfolioBundle:Portfolioset')->findByUser($user,$course);
        //select the first in the array
        $portfolioset = reset($portfoliosets);

        $assessmentset = $course->getAssessmentset();
        
        //pagination for portfolio file is there is a portfolio present
        $paginator = $this->get('knp_paginator');
        if ($portfoliosets)
        {
        $portfolio = $portfolioset->getPortfolioitems();
        $portfoliosetid = $portfolioset->getId();
        $portfolio_docs = $paginator->paginate($portfolio,$this->get('request')->query->get('page', 1),1);
        $ratingset = $em->getRepository('MarcaAssessmentBundle:Ratingset')->ratingsetsByPortfolioset($portfolioset);
        }
        else {$portfolio = '';$portfolio_docs = ''; $portfoliosetid = '0';$ratingset = '';}
        
        return array('portfoliosetid' => $portfoliosetid,'portfolio' =>$portfolio, 'portfolio_docs' => $portfolio_docs,'roll'=> $roll,'assessmentset'=> $assessmentset, 'ratingset' => $ratingset,'userid'=>$userid, 'role' => $role, 'markup' => $markup);
    }    

    /**
     * Adds a new Portfolio entity and redirects to edit for Portitem and Portorder.
     *
     * @Route("/{courseid}/{fileid}/new", name="portfolio_new")
     * @Template()
     */
    public function newAction($courseid, $fileid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $portset = $course->getPortset();

        //find default portfolio
        $portfoliosets = $em->getRepository('MarcaPortfolioBundle:Portfolioset')->findByUser($user,$course);
        //select the first in the array
        $portfolioset = reset($portfoliosets);
        
        $file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $portitem = $portset->getPortitem()->first();
        $portfolio = new Portfolio();
        $portfolio->setFile($file);
        $portfolio->setUser($user);
        $portfolio->setCourse($course);
        $portfolio->setPortitem($portitem);
        $portfolio->setPortfolioset($portfolioset);
        $em->persist($portfolio);
        $em->flush();       
        return $this->redirect($this->generateUrl('portfolio_edit', array('id' => $portfolio->getId(),'courseid' => $courseid)));
    }


    /**
     * Displays a form to edit an existing Portfolio entity.
     *
     * @Route("/{courseid}/{id}/edit", name="portfolio_edit")
     * @Template()
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
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        if($portStatus!='true'){
            throw new AccessDeniedException();
        }

        if (!$portfolio) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $editForm = $this->createForm(new PortfolioType($options), $portfolio);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portfolio'      => $portfolio,
            'portStatus'      => $portStatus,
            'roll'=>$roll,
            'role' => $role,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        );
    }

    /**
     * Edits an existing Portfolio entity.
     *
     * @Route("/{courseid}/{id}/update", name="portfolio_update")
     * @Method("post")
     * @Template("MarcaPortfolioBundle:Portfolio:edit.html.twig")
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

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($portfolio);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio', array('courseid' => $courseid)));
        }

        return array(
            'portfolio'      => $portfolio,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
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

        $form->bind($request);

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
