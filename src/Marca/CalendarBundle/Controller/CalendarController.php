<?php

namespace Marca\CalendarBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CalendarBundle\Entity\Calendar;
use Marca\CalendarBundle\Form\CalendarType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Calendar controller.
 *
 * @Route("/calendar")
 */
class CalendarController extends Controller
{
    /**
     * Lists all Calendar entities.
     *
     * @Route("/{courseid}/", name="calendar")
     * @Template()
     */
    public function indexAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourse($course);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $calendar = $paginator->paginate($calendar,$this->get('request')->query->get('page', 1),10);
        
        return array('calendar' => $calendar);
    }
    
    
    /**
     * Lists all Calendar entities.
     *
     * @Route("/{courseid}/display", name="calendar_display")
     * @Template()
     */
    public function displayAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findAll();

        return array('calendar' => $calendar);
    }    

    /**
     * Finds and displays a Calendar entity.
     *
     * @Route("/{courseid}/{id}/show", name="calendar_show")
     * @Template()
     */
    public function showAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'calendar'      => $calendar,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Calendar entity.
     *
     * @Route("/{courseid}/new", name="calendar_new")
     * @Template()
     */
    public function newAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $calendar = new Calendar();
        $calendar->setDescription('<p> </p>');
        $form   = $this->createForm(new CalendarType(), $calendar);

        return array(
            'calendar' => $calendar,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Calendar entity.
     *
     * @Route("/{courseid}/create", name="calendar_create")
     * @Method("post")
     * @Template("MarcaCalendarBundle:Calendar:new.html.twig")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();       
        $course = $this->getCourse();
        
        $calendar  = new Calendar();
        $calendar->setUser($user);
        $calendar->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new CalendarType(), $calendar);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($calendar);
            $em->flush();

            return $this->redirect($this->generateUrl('calendar', array('courseid'=> $courseid,)));
            
        }

        return array(
            'calendar' => $calendar,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Calendar entity.
     *
     * @Route("/{courseid}/{id}/edit", name="calendar_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        $editForm = $this->createForm(new CalendarType(), $calendar);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'calendar'      => $calendar,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Calendar entity.
     *
     * @Route("/{courseid}/{id}/update", name="calendar_update")
     * @Method("post")
     * @Template("MarcaCalendarBundle:Calendar:edit.html.twig")
     */
    public function updateAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        $editForm   = $this->createForm(new CalendarType(), $calendar);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($calendar);
            $em->flush();

            return $this->redirect($this->generateUrl('calendar', array('courseid'=> $courseid,)));
        }

        return array(
            'calendar'      => $calendar,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Calendar entity.
     *
     * @Route("/{courseid}/{id}/delete", name="calendar_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

            if (!$calendar) {
                throw $this->createNotFoundException('Unable to find Calendar entity.');
            }

            $em->remove($calendar);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('calendar'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
     /**
     * Edits an existing Calendar entity.
     * @Template("MarcaCalendarBundle:Calendar:display.json.twig")
     */
 public function feedAction() { 
     
     $em = $this->getEm();
     
     $dql1 = "SELECT c FROM MarcaCalendarBundle:Calendar c";    
     $query = $em->createQuery($dql1);
     $feed = $query->getResult();
     
     return array('feed' => $feed);
    
}


}
