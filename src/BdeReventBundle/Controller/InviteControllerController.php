<?php

namespace BdeReventBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Route("/{key}/invite", name="return_mail")
     * @Template()
     */
    public function inviteAction($key)
    {
        $id = $this->get("bde.main.mailer_service")->_decrypt_data($key);
        if ($id == null) {
            throw $this->createNotFoundException();
        }
        $participant = $this->get("doctrine.orm.entity_manager")->getRepository("BdeReventBundle:Participant")
            ->find($id);
        if ($participant == null) {
            throw $this->createNotFoundException();
        }
        return array(
            'participant' => $participant
        );
    }

}
