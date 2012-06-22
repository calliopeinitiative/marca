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
    public function indexAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $courseid = $this->get('request')->getSession()->get('courseid');
        $course = $em->getRepository('MarcaCourseBundle:Course')->find($courseid);
        $portset = $course->getPortset();
        $portitems = $em->getRepository('MarcaPortfolioBundle:Portitem')->findByPortset($portset);
        foreach ($portitems as &$portitem) {
        $portitem = $em->getRepository('MarcaFileBundle:File')->findByPortitem($portitem);
        }
        return array('portset' =>$portset, 'portitems' =>$portitems, );
    }

    /**
     * Finds and displays a Portfolio entity.
     *
     * @Route("/{courseid}/{id}/show", name="portfolio_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getEm();

        $portfolios = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);

        if (!$portfolio) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portfolios'      => $portfolios,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Portfolio entity.
     *
     * @Route("/{courseid}/new", name="portfolio_new")
     * @Template()
     */
    public function newAction()
    {
        $portfolio = new Portfolio();
        $form   = $this->createForm(new PortfolioType(), $portfolio);

        return array(
            'portfolio' => $portfolio,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Portfolio entity.
     *
     * @Route("/{courseid}/create", name="portfolio_create")
     * @Method("post")
     * @Template("MarcaPortfolioBundle:Portfolio:new.html.twig")
     */
    public function createAction()
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        $newPortfolio  = new Portfolio();
        $newPortfolio->setUser($user);
        $newPortfolio->setCourse($course);
        $request = $this->getRequest();
        $form    = $this->createForm(new PortfolioType(), $newPortfolio);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getEm();
            $em->persist($newPortfolio);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio_show', array('courseid' => $courseid)));
            
        }

        return array(
            'newPortfolio' => $newPortfolio,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Portfolio entity.
     *
     * @Route("/{courseid}/{id}/edit", name="portfolio_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getEm();

        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);

        if (!$portfolio) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $editForm = $this->createForm(new PortfolioType(), $portfolio);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'portfolio'      => $portfolio,
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
    public function updateAction($id)
    {
        $em = $this->getEm();

        $portfolio = $em->getRepository('MarcaPortfolioBundle:Portfolio')->find($id);

        if (!$portfolio) {
            throw $this->createNotFoundException('Unable to find Portfolio entity.');
        }

        $editForm   = $this->createForm(new PortfolioType(), $portfolio);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($portfolio);
            $em->flush();

            return $this->redirect($this->generateUrl('portfolio_edit', array('id' => $id)));
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
    public function deleteAction($id)
    {
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

        return $this->redirect($this->generateUrl('portfolio'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
