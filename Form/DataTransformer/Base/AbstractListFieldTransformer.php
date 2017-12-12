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

namespace Zikula\TrivialModule\Form\DataTransformer\Base;

use Symfony\Component\Form\DataTransformerInterface;
use Zikula\TrivialModule\Helper\ListEntriesHelper;

/**
 * List field transformer base class.
 *
 * This data transformer treats multi-valued list fields.
 */
abstract class AbstractListFieldTransformer implements DataTransformerInterface
{
    /**
     * @var ListEntriesHelper
     */
    protected $listHelper;

    /**
     * ListFieldTransformer constructor.
     *
     * @param ListEntriesHelper $listHelper ListEntriesHelper service instance
     */
    public function __construct(ListEntriesHelper $listHelper)
    {
        $this->listHelper = $listHelper;
    }

    /**
     * Transforms the object values to the normalised value.
     *
     * @param string|null $values The object values
     *
     * @return array Normalised value
     */
    public function transform($values)
    {
        if (null === $values || '' === $values) {
            return [];
        }

        return $this->listHelper->extractMultiList($values);
    }

    /**
     * Transforms an array with values back to the string.
     *
     * @param array $values The values
     *
     * @return string Resulting string
     */
    public function reverseTransform($values)
    {
        if (!$values) {
            return '';
        }

        return '###' . implode('###', $values) . '###';
    }
}
