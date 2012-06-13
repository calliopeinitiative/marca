<?php

namespace Marca\ForumBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Marca\ForumBundle\Entity\Forum;
use Marca\ForumBundle\Form\ForumType;

/**
 * Forum controller.
 *
 * @Route("/forum")
 */
class ForumController extends Controller
{
    /**
     * Lists all Forum entities.
     *
     * @Route("/{set}/page", name="forum")
     * @Template()
     */
    public function indexAction($set)
    {
        $em = $this->getEm();
        $username = $this->get('security.context')->getToken()->getUsername();
        $set = $set;
        $userid = $this->getDoctrine()->getEntityManager()->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        $entities = $em->getRepository('MarcaForumBundle:Forum')->findForumRecent($userid, $set);
        return array('entities' => $entities,'set' => $set);
    }

    /**
     * Finds and displays a Forum entity.
     *
     * @Route("/{id}/show", name="forum_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Forum entity.
     *
     * @Route("/new", name="forum_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Forum();
        $entity->setBody('<p></p>');
        $form   = $this->createForm(new ForumType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Forum entity.
     *
     * @Route("/create", name="forum_create")
     * @Method("post")
     * @Template("MarcaForumBundle:Forum:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getEm();
        $username = $this->get('security.context')->getToken()->getUsername();
        $userid = $em->getRepository('MarcaUserBundle:Profile')->findOneByUsername($username)->getId(); 
        $courseid = $this->get('request')->getSession()->get('courseid');
        $entity  = new Forum();
        $entity->setUserid($userid);
        $entity->setCourseid($courseid);
        $request = $this->getRequest();
        $form    = $this->createForm(new ForumType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('forum', array('set' => 0)));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Forum entity.
     *
     * @Route("/{id}/edit", name="forum_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $editForm = $this->createForm(new ForumType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Forum entity.
     *
     * @Route("/{id}/update", name="forum_update")
     * @Method("post")
     * @Template("MarcaForumBundle:Forum:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getEm();

        $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forum entity.');
        }

        $editForm   = $this->createForm(new ForumType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('forum', array('set' => 0)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Forum entity.
     *
     * @Route("/{id}/delete", name="forum_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $entity = $em->getRepository('MarcaForumBundle:Forum')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Forum entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('forum'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
