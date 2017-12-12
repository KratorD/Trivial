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

namespace Zikula\TrivialModule\Entity;

use Zikula\TrivialModule\Entity\Base\AbstractAnswerEntity as BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for answer entities.
 * @ORM\Entity(repositoryClass="Zikula\TrivialModule\Entity\Repository\AnswerRepository")
 * @ORM\Table(name="zikula_trivial_answer",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class AnswerEntity extends BaseEntity
{
    // feel free to add your own methods here
}