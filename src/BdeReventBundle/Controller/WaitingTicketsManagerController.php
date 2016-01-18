<?php

namespace BdeReventBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MailsManagerController
 * @package BdeReventBundle\Controller
 * @Route("/waiting")
 */
class WaitingTicketsManagerController extends Controller
{
    /**
     * @Route("/", name="waitings_index")
     * @Template()
     */
    public function indexAction()
    {
        return array(
            'tickets' => $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:WaitingTicket')->findAll()
        );
    }

    /**
     * @Route("/{id}/delete")
     * @Template()
     */
    public function deleteAction(Request $request, $id)
    {

        $mail = $this->get('doctrine.orm.entity_manager')->getRepository('BdeReventBundle:WaitingTicket')->find($id);
        if ($mail == null) {
            throw $this->createNotFoundException('Ticket introuvable!');
        }

        if ($request->isMethod('POST')) {
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->remove($mail);
            $em->flush($mail);
            $this->addFlash('success', 'Le ticket a été supprimé');
            return $this->redirectToRoute('waitings_index');

        }

        return array(// ...
        );
    }

}
