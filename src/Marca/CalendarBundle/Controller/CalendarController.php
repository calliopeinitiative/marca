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
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);
        $gotodate = date("Y-m-d");
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $calendar = $paginator->paginate($calendar,$this->get('request')->query->get('page', 1),10);
        
        return array('calendar' => $calendar, 'gotodate' => $gotodate);
    }
    
    /**
     * Lists all Calendar entities.
     *
     * @Route("/{courseid}/upcoming", name="calendar_upcoming")
     * @Template("MarcaCalendarBundle:Calendar:index.html.twig")
     */
    public function upcomingAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseStart($course);
        $gotodate = date("Y-m-d");
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $calendar = $paginator->paginate($calendar,$this->get('request')->query->get('page', 1),10);
        
        return array('calendar' => $calendar, 'gotodate' => $gotodate);
    }
    
    /**
     * Lists all Calendar entities.
     *
     * @Route("/{courseid}/{gotodate}/display", name="calendar_display")
     * @Template()
     */
    public function displayAction($gotodate)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();
        $events = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);
        
        $startTime = $course->getTime();
        $startDate = date_create();
        
        // for modal new event 
        $calendar = new Calendar();
        $calendar->setDescription('<p> </p>');
        $calendar->setStartTime($startTime);
        $calendar->setEndTime($startTime);
        $calendar->setStartDate($startDate);
        $calendar->setEndDate($startDate);
        
        $form   = $this->createForm(new CalendarType(), $calendar);

        return array('events' => $events, 'gotodate' => $gotodate, 'form'   => $form->createView(),);
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
    public function newAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();

        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        
        $startTime = $course->getTime();
        $startDate = date_create();
         
        $calendar = new Calendar();
        $calendar->setDescription('<p> </p>');
        $calendar->setStartTime($startTime);
        $calendar->setEndTime($startTime);
        $calendar->setStartDate($startDate);
        $calendar->setEndDate($startDate);
        
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
        $postData = $request->request->get('marca_calendarbundle_calendartype');
        $startDate = strtotime($postData['startDate']);
        $gotodate = date("Y-m-d", $startDate);
        $form    = $this->createForm(new CalendarType(), $calendar);
        $form->bindRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($calendar);
            $em->flush();

            return $this->redirect($this->generateUrl('calendar_display', array('courseid'=> $courseid,'gotodate'=> $gotodate,)));
            
        }

        return array(
            'calendar' => $calendar,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Calendar entity.
     *
     * @Route("/{courseid}/{id}/{gotodate}/edit", name="calendar_edit")
     * @Template()
     */
    public function editAction($id, $gotodate)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();
        $events = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        $editForm = $this->createForm(new CalendarType(), $calendar);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'events' => $events,
            'gotodate' => $gotodate,
            'calendar'      => $calendar,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Calendar entity.
     *
     * @Route("/{courseid}/{id}/{gotodate}/update", name="calendar_update")
     * @Method("post")
     * @Template("MarcaCalendarBundle:Calendar:edit.html.twig")
     */
    public function updateAction($id,$courseid,$gotodate)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $course = $this->getCourse();
        $events = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        $editForm   = $this->createForm(new CalendarType(), $calendar);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();
        $postData = $request->request->get('marca_calendarbundle_calendartype');
        $startDate = strtotime($postData['startDate']);
        $gotodate = date("Y-m-d", $startDate);
        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($calendar);
            $em->flush();

            return $this->redirect($this->generateUrl('calendar_display', array('courseid'=> $courseid,'gotodate'=> $gotodate,)));
        }

        return array(
            'events' => $events,
            'gotodate' => $gotodate,
            'calendar'      => $calendar,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Calendar entity.
     *
     * @Route("/{courseid}/{id}/{gotodate}/delete", name="calendar_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid, $gotodate)
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

        return $this->redirect($this->generateUrl('calendar_display', array('courseid'=> $courseid, 'gotodate' => $gotodate)));
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
