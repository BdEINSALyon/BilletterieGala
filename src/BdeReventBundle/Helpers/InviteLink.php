<?php
/**
 * Created by PhpStorm.
 * User: pvienne
 * Date: 27/11/15
 * Time: 18:30
 */

namespace BdeReventBundle\Helpers;


use BdeReventBundle\Entity\Participant;
use BdeReventBundle\Mail\TokenService;

class InviteLink extends \Twig_Extension
{

    public function __construct(TokenService $tokenService)
    {
        $this->token = $tokenService;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction("invite_token", array($this, 'getToken'))
        );
    }

    public function getToken(Participant $participant)
    {
        return $this->token->crypt_data($participant->getId());
    }


    public function getName()
    {
        return 'invite_link';
    }
}