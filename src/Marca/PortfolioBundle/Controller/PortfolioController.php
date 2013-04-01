<?php

namespace Marca\PortfolioBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\PortfolioBundle\Entity\Portfolio;
use Marca\PortfolioBundle\Form\PortfolioType;

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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $portset = $course->getPortset();
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->findByUser($user,$course);
        $assessmentset_id = $course->getAssessmentset()->getId();
        $assessmentset = $em->getRepository('MarcaAssessmentBundle:Assessmentset')->find($assessmentset_id);
        return array('portfolio' =>$portfolio, 'portset' => $portset, 'roll'=> $roll, 'assessmentset'=> $assessmentset);
    }
    

    /**
     * Finds and displays a Portfolio entity.
     *
     * @Route("/{courseid}/show", name="portfolio_show")
     * @Template()
     */
    public function showAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $rater = $this->getUser();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->findByUser($user,$course);
        $assessmentset = $course->getAssessmentset();
        $ratingset = $em->getRepository('MarcaAssessmentBundle:Ratingset')->ratingsetByUser($user,$course,$rater);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $portfolio_docs = $paginator->paginate($portfolio,$this->get('request')->query->get('page', 1),1);
        
        return array('portfolio' =>$portfolio, 'portfolio_docs' => $portfolio_docs,'roll'=> $roll,'assessmentset'=> $assessmentset,'ratingset'=> $ratingset);
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

        $files = $em->getRepository('MarcaFileBundle:File')->findFilesForPort($project, $user, $course);
        $projects = $em->getRepository('MarcaCourseBundle:Project')->findProjectsByCourse($course, $resource);

        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $files = $paginator->paginate($files,$this->get('request')->query->get('page', 1),15);
        $count = $files->getTotalItemCount();

        return array('files' => $files, 'count' => $count, 'projects' => $projects, 'active_project' => $project, 'course' => $course, 'roll' => $roll, 'role'=> $role);
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
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $em->getRepository('MarcaUserBundle:User')->find($userid);
        $course = $this->getCourse();
        $rater = $this->getUser();
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->findByUser($user,$course);
        $assessmentset = $course->getAssessmentset();
        $ratingset = $em->getRepository('MarcaAssessmentBundle:Ratingset')->ratingsetByUser($user,$course,$rater);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $portfolio_docs = $paginator->paginate($portfolio,$this->get('request')->query->get('page', 1),1);
        
        return array('portfolio' =>$portfolio, 'portfolio_docs' => $portfolio_docs,'roll'=> $roll,'assessmentset'=> $assessmentset, 'ratingset' => $ratingset);
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
        $file = $em->getRepository('MarcaFileBundle:File')->find($fileid);
        $portitem = $portset->getPortitem()->first();
        $portfolio = new Portfolio();
        $portfolio->setFile($file);
        $portfolio->setUser($user);
        $portfolio->setCourse($course);
        $portfolio->setPortitem($portitem);
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
        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($courseid);

        if (!$portfolio) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $editForm = $this->createForm(new PortfolioType($options), $portfolio);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portfolio'      => $portfolio,
            'roll'=>$roll,
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

        $form->bindRequest($request);

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
}
