<?php

namespace BdeReventBundle\Controller;

use BdeReventBundle\Form\TypeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ParticipantsManagerController
 * @package BdeReventBundle\Controller
 * @Route("/types")
 */
class TypesManagerController extends Controller
{
    /**
     * @Route("/", name="types_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'types' => $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Type')->findAll()
        );
    }

    /**
     * @Route("/new")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(new TypeType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $type = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($type);
            $em->flush($type);
            return $this->redirectToRoute('types_index');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/{id}/delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {

        $type = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Type')->find($id);
        if ($type == null) {
            throw $this->createNotFoundException('Participant introuvable!');
        }

        if ($request->isMethod('POST')) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->remove($type);
            $em->flush($type);
            $this->addFlash('success', 'Le type a été supprimé');
            return $this->redirectToRoute('types_index');

        }

        return array(// ...
        );
    }

    /**
     * @Route("/{id}/edit")
     * @Template()
     * @param $request
     * @param $id
     * @return array
     */
    public function editAction(Request $request, $id)
    {

        $type = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Type')->find($id);
        if ($type == null) {
            throw $this->createNotFoundException('Type introuvable!');
        }

        $form = $this->createForm(new TypeType(), $type);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $type = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($type);
            $em->flush($type);
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
