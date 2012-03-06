<?php

namespace Marca\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Course;
use Marca\CourseBundle\Form\CourseType;

/**
 * Course controller.
 *
 * @Route("/course")
 */
class CourseController extends Controller
{
    /**
     * Lists all Course entities.
     *
     * @Route("/", name="course")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        if ($this->get('security.context')->isGranted('ROLE_ADMIN')) {
        $entities = $em->getRepository('MarcaCourseBundle:Course')->findAll();
        return array('entities' => $entities);
        } else {    
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        $entities = $em->getRepository('MarcaCourseBundle:Course')->findByUserid($userid);
        return array('entities' => $entities);
        }
    }

    /**
     * Finds and displays a Course entity.
     *
     * @Route("/{id}/show", name="course_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Course')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Course entity.
     *
     * @Route("/new", name="course_new")
     * @Template()
     */
    public function newAction()
    {
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $this->getDoctrine()->getEntityManager()->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        
        $entity = new Course();
        $entity->setPortRubricId(1);
        $entity->setProjectDefaultId(1);
        $entity->setAssessmentId(1);
        $entity->setPortStatus(1);
        $entity->setParentId(1);
        $entity->setEnroll(true);
        $entity->setPost(true);
        $entity->setMulticult(true);
        $entity->setNotes(true);
        $entity->setJournal(true);
        $entity->setPortfolio(true);
        $entity->setZine(false);
        $entity->setStudentForum(false);
        $entity->setUserid($userid);
        
        $form   = $this->createForm(new CourseType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Course entity.
     *
     * @Route("/create", name="course_create")
     * @Method("post")
     * @Template("MarcaCourseBundle:Course:new.html.twig")
     */
    public function createAction()
    {
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $this->getDoctrine()->getEntityManager()->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        
        $entity  = new Course();
        $entity->setUserid($userid);
        $request = $this->getRequest();
        $form    = $this->createForm(new CourseType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Course entity.
     *
     * @Route("/{id}/edit", name="course_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Course')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createForm(new CourseType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Course entity.
     *
     * @Route("/{id}/update", name="course_update")
     * @Method("post")
     * @Template("MarcaCourseBundle:Course:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('MarcaCourseBundle:Course')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm   = $this->createForm(new CourseType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('course_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Course entity.
     *
     * @Route("/{id}/delete", name="course_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('MarcaCourseBundle:Course')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Course entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('course'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
