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

namespace Zikula\TrivialModule\Validator\Constraints\Base;

use Symfony\Component\Validator\Constraint;

/**
 * List entry validation constraint.
 */
abstract class AbstractListEntry extends Constraint
{
    /**
     * Entity name
     * @var string
     */
    public $entityName = '';

    /**
     * Property name
     * @var string
     */
    public $propertyName = '';

    /**
     * Whether multiple list values are allowed or not
     * @var boolean
     */
    public $multiple = false;

    /**
     * Minimum amount of values for multiple lists
     * @var integer
     */
    public $min;

    /**
     * Maximum amount of values for multiple lists
     * @var integer
     */
    public $max;

    /**
     * @inheritDoc
     */
    public function validatedBy()
    {
        return 'zikula_trivial_module.validator.list_entry.validator';
    }
}
