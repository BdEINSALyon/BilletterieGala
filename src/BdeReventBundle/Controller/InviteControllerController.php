<?php

namespace BdeReventBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InviteControllerController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array(// ...
        );
    }

    /**
     * @Route("/{key}/endPayment", name="end_payment")
     */
    public function endPaymentAction(\Symfony\Component\HttpFoundation\Request $request, $key)
    {

        $id = $this->get("bde.revent.token_service")->decrypt_data($key);
        if ($id == null) {
            throw $this->createNotFoundException();
        }
        $em = $this->get("doctrine.orm.entity_manager");
        $participant = $em->getRepository("BdeReventBundle:Participant")
            ->find($id);
        if ($participant == null) {
            throw $this->createNotFoundException();
        }
        if ($participant->getUsed()) {
            throw $this->createNotFoundException();
        }

        $participant->setUsed(true);
        $participant->setGuestsByWeezevent($request->get('nbInvite'));
        $em->persist($participant);
        $em->flush();

        $response = new Response(json_encode(array('status' => 'ok')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/{key}/ticket_office", name="return_mail")
     * @Template()
     */
    public function ticketsAction($key)
    {
        $id = $this->get("bde.revent.token_service")->decrypt_data($key);
        if ($id == null) {
            return $this->render('@BdeRevent/InviteController/deny.html.twig');
        }
        $participant = $this->get("doctrine.orm.entity_manager")->getRepository("BdeReventBundle:Participant")
            ->find($id);
        if ($participant == null) {
            throw $this->createNotFoundException();
        }
        if ($participant->getUsed()) {
            $this->addFlash('info', 'Vous avez déjà achetez vos places, mais vous pouvez inviter vos proches !');
            return $this->redirectToRoute('invite', array('key' => $key));
        }
        return array(
            'participant' => $participant,
            'token' => $key
        );
    }

    /**
     * @Route("/{key}/invite", name="invite")
     * @Template()
     */
    public function inviteAction($key)
    {
        $id = $this->get("bde.revent.token_service")->decrypt_data($key);
        if ($id == null) {
            return $this->render('@BdeRevent/InviteController/deny.html.twig');
        }
        $participant = $this->get("doctrine.orm.entity_manager")->getRepository("BdeReventBundle:Participant")
            ->find($id);
        if ($participant == null) {
            throw $this->createNotFoundException();
        }
        if (!$participant->getUsed())
            return $this->redirectToRoute('return_mail', array('key' => $key));
        if ($participant->getType()->getCanInvite())
        return array(
            'participant' => $participant,
            'token' => $key
        );
        else
            return $this->render('@BdeRevent/InviteController/can_not_invite.html.twig');
    }

}
