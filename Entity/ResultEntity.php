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

use Zikula\TrivialModule\Entity\Base\AbstractResultEntity as BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the concrete entity class for result entities.
 * @ORM\Entity(repositoryClass="Zikula\TrivialModule\Entity\Repository\ResultRepository")
 * @ORM\Table(name="zikula_trivial_result",
 *     indexes={
 *         @ORM\Index(name="workflowstateindex", columns={"workflowState"})
 *     }
 * )
 */
class ResultEntity extends BaseEntity
{
    // feel free to add your own methods here
}