<?php

namespace Marca\DocBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response; 
use Marca\DocBundle\Entity\Doc;
use Marca\DocBundle\Entity\Autosave;
use Marca\DocBundle\Form\DocType;
use Marca\FileBundle\Entity\File;
use Marca\CourseBundle\Entity\Project;

/**
 * Rubric controller
 * 
 * @Route("/rubric", name="rubric")
 */
class RubricController extends Controller

{
    /**
     * Displays a form to create a new Doc entity.
     *
     * @Route("/new", name="rubric_new")
     * @Template("MarcaDocBundle:Rubric:new.html.twig")
     */
    public function newAction()
    {
        //$allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        //$this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();

        $markupsets = $user->getMarkupsets();
        
        $doc = new Doc();
        $doc->setBody('<p></p>');

        $form   = $this->createForm(new DocType(), $doc);

        return array(
            'doc' => $doc,
            'markupsets'      => $markupsets,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Doc entity.
     *
     * @Route("/{resource}/create", name="rubric_create")
     * @Method("post")
     * @Template("MarcaDocBundle:Doc:new.html.twig")
     */
    public function createAction($courseid, $resource)
    {
        $allowed = array(self::ROLE_INSTRUCTOR, self::ROLE_STUDENT);
        $this->restrictAccessTo($allowed);
        
        $em = $this->getEm();
        $user = $this->getUser();
        $tag = '0';

        $course = $em->getRepository('MarcaCourseBundle:Course')->find(1101);
        $project = $em->getRepository('MarcaCourseBundle:Project')->findOneByCourse(1101);
              
        $file = new File();
        $file->setName('New eDoc');
        $file->setUser($user);
        $file->setProject($project);
        $file->setCourse(null);
        
        $doc  = new Doc();    
        $doc->setFile($file); 
        $request = $this->getRequest();
        $form    = $this->createForm(new DocType(), $doc);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($file);
            $em->persist($doc);
            $em->flush();

            return $this->redirect($this->generateUrl('file_edit', array('id' => $file->getId(),'courseid'=> $courseid,'resource'=> $resource, 'tag' => $tag)));
            
        }

        return array(
            'doc' => $doc,
            'form'   => $form->createView()
        );
    }
}


