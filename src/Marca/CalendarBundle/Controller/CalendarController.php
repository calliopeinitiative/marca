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
     * Create Sidebar fragment
     *
     * @Route("/{courseid}/sidebar", name="calendar_sidebar")
     */
    public function createSidebarAction($courseid)
    {
        $role = $this->getCourseRole();
        return $this->render('MarcaCalendarBundle::sidebar.html.twig', array(
            'role' => $role
        ));
    }


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
        $role = $this->getCourseRole();
        $course = $this->getCourse();
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);

        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $calendar = $paginator->paginate($calendar,$this->get('request')->query->get('page', 1),10);
        
        return $this->render('MarcaCalendarBundle:Calendar:index.html.twig', array(
            'calendar' => $calendar,
            'role' => $role
        ));
    }
    
    /**
     * Lists all Calendar entities.
     *
     * @Route("/{courseid}/upcoming", name="calendar_upcoming")
     */
    public function upcomingAction()
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $course = $this->getCourse();
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseStart($course);
        
        //pagination for files
        $paginator = $this->get('knp_paginator');
        $calendar = $paginator->paginate($calendar,$this->get('request')->query->get('page', 1),10);
        
        return $this->render('MarcaCalendarBundle:Calendar:index.html.twig', array(
            'calendar' => $calendar,
            'role' => $role,
        ));
    }
    
    /**
     * Lists all Calendar entities.
     *
     * @Route("/{courseid}/{eventid}/display", name="calendar_display", defaults={"eventid" = 0})
     */
    public function displayAction($eventid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $course = $this->getCourse();
        $events = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);
        $event = $em->getRepository('MarcaCalendarBundle:Calendar')->find($eventid);
        if (!$event) {
            $event = new Calendar();
            $event->setStartDate(date_create());
        }

        return $this->render('MarcaCalendarBundle:Calendar:display.html.twig', array(
            'events' => $events,
            'role' => $role,
            'event' => $event,
        ));
    }    

    /**
     * Finds and displays a Calendar entity.
     *
     * @Route("/{courseid}/{id}/show_modal", name="calendar_show_modal")
     */
    public function showModalAction($id)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        return $this->render('MarcaCalendarBundle:Calendar:show_modal.html.twig', array(
            'calendar' => $calendar,
            'role' => $role
        ));
    }
    

    /**
     * Displays a form to create a new Calendar entity.
     *
     * @Route("/{courseid}/{set_date}/new", name="calendar_new")
     * @Template()
     */
    public function newAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        
        $startTime = $course->getTime();
        $startDate = date_create();
         
        $calendar = new Calendar();
        $calendar->setDescription('<p> </p>');
        $calendar->setStartTime($startTime);
        $calendar->setEndTime($startTime);
        $calendar->setStartDate($startDate);
        $calendar->setEndDate($startDate);

        $form = $this->createCreateForm($calendar, $courseid);

        return $this->render('MarcaCalendarBundle:Calendar:new.html.twig', array(
            'calendar' => $calendar,
            'role' => $role,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a form to create a Calendar entity.
     *
     * @param Response $response The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Calendar $calendar, $courseid)
    {
        $form = $this->createForm(new CalendarType(), $calendar, array(
            'action' => $this->generateUrl('calendar_create', array('courseid' => $courseid)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }

    
    /**
     * Creates a new Calendar entity.
     *
     * @Route("/{courseid}/create", name="calendar_create")
     * @Method("post")
     */
    public function createAction($courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);

        $role = $this->getCourseRole();
        $user = $this->getUser();       
        $course = $this->getCourse();
        
        $calendar  = new Calendar();
        $calendar->setUser($user);
        $calendar->setCourse($course);
        $request = $this->getRequest();
        $eventid = $calendar->getId();
        $form = $this->createCreateForm($calendar, $courseid);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($calendar);
            $em->flush();

            return $this->redirect($this->generateUrl('calendar_display', array('courseid'=> $courseid,'eventid'=> $eventid,)));
        }

        return $this->render('MarcaCalendarBundle:Calendar:new.html.twig', array(
            'calendar' => $calendar,
            'role' => $role,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Calendar entity.
     *
     * @Route("/{courseid}/{id}/edit", name="calendar_edit")
     */
    public function editAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $course = $this->getCourse();
        $events = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);
        $eventid = $calendar->getId();

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        $editForm = $this->createEditForm($calendar, $courseid);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        return $this->render('MarcaCalendarBundle:Calendar:edit.html.twig', array(
            'events' => $events,
            'role' => $role,
            'eventid' => $eventid,
            'calendar'      => $calendar,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Creates a form to edit a Calendar entity.
     *
     * @param Response $response The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Calendar $calendar, $courseid)
    {
        $form = $this->createForm(new CalendarType(), $calendar, array(
            'action' => $this->generateUrl('calendar_update', array('id' => $calendar->getId(),'courseid' => $courseid)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Post','attr' => array('class' => 'btn btn-primary pull-right'),));
        return $form;
    }


    /**
     * Edits an existing Calendar entity.
     *
     * @Route("/{courseid}/{id}/update", name="calendar_update")
     * @Method("post")
     */
    public function updateAction($id,$courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $role = $this->getCourseRole();
        $course = $this->getCourse();
        $events = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);


        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        $eventid = $calendar->getId();
        $editForm = $this->createEditForm($calendar, $courseid);
        $deleteForm = $this->createDeleteForm($id, $courseid);

        $request = $this->getRequest();
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($calendar);
            $em->flush();

            return $this->redirect($this->generateUrl('calendar_display', array('courseid'=> $courseid,'eventid'=> $eventid,)));
        }

        return $this->render('MarcaCalendarBundle:Calendar:edit.html.twig', array(
            'events' => $events,
            'role' => $role,
            'eventid' => $eventid,
            'calendar'      => $calendar,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    

    /**
     * Deletes a Calendar entity.
     *
     * @Route("/{courseid}/{id}/delete", name="calendar_delete")
     * @Method("post")
     */
    public function deleteAction($id, $courseid)
    {
        $allowed = array(self::ROLE_INSTRUCTOR);
        $this->restrictAccessTo($allowed);

        $form = $this->createDeleteForm($id, $courseid);
        $request = $this->getRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->find($id);

            if (!$calendar) {
                throw $this->createNotFoundException('Unable to find Calendar entity.');
            }

            $em->remove($calendar);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('calendar_display', array('courseid'=> $courseid,)));
    }


    /**
     * Creates a form to delete a Calendar entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, $courseid)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('calendar_delete', array('id' => $id, 'courseid' => $courseid)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Yes','attr' => array('class' => 'btn btn-danger'),))
            ->getForm()
            ;
    }
    

    /**
     * Creates HTML for printing
     *
     * @Route("/{courseid}/print", name="agenda_print")
     */
    public function htmlPrintAction()
    {

        $em = $this->getEm();
        $course = $this->getCourse();
        $ip = $this->get('request')->getClientIp();
        $calendar = $em->getRepository('MarcaCalendarBundle:Calendar')->findCalendarByCourseAll($course);

        if (!$calendar) {
            throw $this->createNotFoundException('Unable to find Calendar entity.');
        }

        return $this->render('MarcaCalendarBundle:Calendar:pdf.html.twig', array(
            'calendar' => $calendar,
            'ip' => $ip
        ));
    }


    /**
     * Creates a pdf of a Agenda for printing.
     *
     * @Route("/{courseid}/pdf", name="agenda_pdf")
     */
    public function createPdfAction($courseid)
    {

        $filename = 'attachment; filename="agenda.pdf"';
        $pageUrl = $this->generateUrl('agenda_print', array('courseid'=> $courseid),  true); // use absolute path!

        return new Response(
            $this->get('knp_snappy.pdf')->getOutput($pageUrl),
            200,
            array('Content-Type'=> 'application/force-download', 'Content-Disposition'  => $filename )
        );
    }

}
