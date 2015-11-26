<?php

namespace BdeReventBundle\Controller;

use BdeReventBundle\Entity\Mail;
use BdeReventBundle\Form\MailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MailsManagerController
 * @package BdeReventBundle\Controller
 * @Route("/mails")
 */
class MailsManagerController extends Controller
{
    /**
     * @Route("/", name="mails_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'mails' => $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Mail')->findAll()
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
        $form = $this->createForm(new MailType());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $mail = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($mail);
            $em->flush($mail);
            return $this->redirectToRoute('mails_index');
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

        $mail = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Mail')->find($id);
        if ($mail == null) {
            throw $this->createNotFoundException('Mail introuvable!');
        }

        if ($request->isMethod('POST')) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->remove($mail);
            $em->flush($mail);
            $this->addFlash('success', 'Le mail a été supprimé');
            return $this->redirectToRoute('mails_index');

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

        $mail = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:Mail')->find($id);
        if ($mail == null) {
            throw $this->createNotFoundException('Mail introuvable!');
        }

        $form = $this->createForm(new MailType(), $mail);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $mail = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($mail);
            $em->flush($mail);
        }

        return array(
            'form' => $form->createView(),
        );
    }

}
