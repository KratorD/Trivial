<?php
/**
 * Trivial.
 *
 * @copyright Krator (Zikula)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Krator <kratord@hotmail.com>.
 * @link https://www.heroesofmightandmagic.es
 * @link http://zikula.org
 * @version Generated by ModuleStudio 1.2.0 (https://modulestudio.de).
 */

namespace Zikula\TrivialModule\Event\Base;

use Symfony\Component\EventDispatcher\Event;
use Zikula\TrivialModule\Entity\TournamentEntity;

/**
 * Event base class for filtering tournament processing.
 */
class AbstractFilterTournamentEvent extends Event
{
    /**
     * @var TournamentEntity Reference to treated entity instance.
     */
    protected $tournament;

    /**
     * @var array Entity change set for preUpdate events.
     */
    protected $entityChangeSet = [];

    /**
     * FilterTournamentEvent constructor.
     *
     * @param TournamentEntity $tournament Processed entity
     * @param array $entityChangeSet Change set for preUpdate events
     */
    public function __construct(TournamentEntity $tournament, array $entityChangeSet = [])
    {
        $this->tournament = $tournament;
        $this->entityChangeSet = $entityChangeSet;
    }

    /**
     * Returns the entity.
     *
     * @return TournamentEntity
     */
    public function getTournament()
    {
        return $this->tournament;
    }

    /**
     * Returns the change set.
     *
     * @return array Entity change set
     */
    public function getEntityChangeSet()
    {
        return $this->entityChangeSet;
    }
}
