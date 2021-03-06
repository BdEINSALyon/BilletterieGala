<?php

namespace BdeReventBundle\Entity;

/**
 * WaitingTicketRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WaitingTicketRepository extends \Doctrine\ORM\EntityRepository
{
    public function getRankOf(Participant $participant)
    {
        $ticket = $this->findOneBy(array('participant' => $participant));
        $parent_ticket = $this->findOneBy(array('participant' => $participant->getInvitedBy()));
        if ($parent_ticket != null && $ticket->getTime() > $parent_ticket->getTime())
            $ticket = $parent_ticket;
        if ($ticket == null)
            return -1;
        return $this->createQueryBuilder('ticket')->select("count(ticket.id)")
            ->where('ticket.time < :date')->setParameter('date', $ticket->getTime())->getQuery()->getSingleScalarResult() + 1;
    }
}
