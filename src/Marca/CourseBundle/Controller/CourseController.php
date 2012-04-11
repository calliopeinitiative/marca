<?php

namespace Marca\CourseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\CourseBundle\Entity\Course;
use Marca\CourseBundle\Entity\Roll;
use Marca\UserBundle\Entity\Profile;
use Marca\CourseBundle\Entity\Project;
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
        $projects = $entity->getProject();
        $projectDefault = $entity->getProjectDefault()->getName();
        $tagsets = $entity->getTagset(); 
        $roll = $em->getRepository('MarcaCourseBundle:Roll')->findRollByCourse($id);
       
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'projects'    => $projects,
            'projectDefault'    => $projectDefault,
            'tagsets'    => $tagsets,
            'roll'        => $roll, 
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
        $entity->setMulticult(false);
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
    {   $em = $this->getDoctrine()->getEntityManager();
        $username = $this->get('security.context')->getToken()->getUsername();
        $profile = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username); 
        $userid = $profile->getId();
        
        $entity  = new Course();
        $entity->setUserid($userid);
        $request = $this->getRequest();
        $form    = $this->createForm(new CourseType(), $entity);
        $form->bindRequest($request);
        
        $roll = new Roll();
        $roll->setRole(1);
        $roll->setProfile($profile);
        $roll->setStatus(1);
        $roll->setCourse($entity);
        
        $project1 = new Project();
        $project1->setName('Paper 1');
        $project1->setUserid($userid);
        $project1->setSortOrder(1);
        $project1->setResource('t');
        $project1->setCourse($entity);
        
        $project2 = new Project();
        $project2->setName('Paper 2');
        $project2->setUserid($userid);
        $project2->setSortOrder(2);
        $project2->setResource('t');
        $project2->setCourse($entity);
        
        $project3 = new Project();
        $project3->setName('Paper 3');
        $project3->setUserid($userid);
        $project3->setSortOrder(3);
        $project3->setResource('t');
        $project3->setCourse($entity);
        
        $project4 = new Project();
        $project4->setName('Portfolio Prep');
        $project4->setUserid($userid);
        $project4->setSortOrder(4);
        $project4->setResource('t');
        $project4->setCourse($entity);        

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->persist($roll);
            $em->persist($project1);
            $em->persist($project2);
            $em->persist($project3);
            $em->persist($project4);
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
        $options = array('courseid' => $id);
        $entity = $em->getRepository('MarcaCourseBundle:Course')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createForm(new CourseType($options), $entity);
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
        $options = array('courseid' => $id);
        $entity = $em->getRepository('MarcaCourseBundle:Course')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Course entity.');
        }

        $editForm = $this->createForm(new CourseType($options), $entity);
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
