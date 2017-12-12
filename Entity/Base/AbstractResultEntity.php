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

namespace Zikula\TrivialModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use Zikula\UsersModule\Entity\UserEntity;
use Zikula\TrivialModule\Traits\StandardFieldsTrait;
use Zikula\TrivialModule\Validator\Constraints as TrivialAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for result entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractResultEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'result';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @Assert\Type(type="integer")
     * @Assert\NotNull()
     * @Assert\LessThan(value=1000000000)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     *
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @TrivialAssert\ListEntry(entityName="result", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\ManyToOne(targetEntity="Zikula\UsersModule\Entity\UserEntity")
     * @ORM\JoinColumn(referencedColumnName="uid")
     * @Assert\NotBlank()
     * @var UserEntity $player
     */
    protected $player = null;
    
    /**
     * @ORM\Column(type="smallint")
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=10000)
     * @var integer $score
     */
    protected $score = 0;
    
    /**
     * @ORM\Column(type="time")
     * @Assert\NotNull()
     * @Assert\Time()
     * @var time $totalTime
     */
    protected $totalTime;
    
    
    
    /**
     * ResultEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the player.
     *
     * @return UserEntity
     */
    public function getPlayer()
    {
        return $this->player;
    }
    
    /**
     * Sets the player.
     *
     * @param UserEntity $player
     *
     * @return void
     */
    public function setPlayer($player)
    {
        if ($this->player !== $player) {
            $this->player = isset($player) ? $player : '';
        }
    }
    
    /**
     * Returns the score.
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }
    
    /**
     * Sets the score.
     *
     * @param integer $score
     *
     * @return void
     */
    public function setScore($score)
    {
        if (intval($this->score) !== intval($score)) {
            $this->score = intval($score);
        }
    }
    
    /**
     * Returns the total time.
     *
     * @return time
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }
    
    /**
     * Sets the total time.
     *
     * @param time $totalTime
     *
     * @return void
     */
    public function setTotalTime($totalTime)
    {
        if ($this->totalTime !== $totalTime) {
            if (!(null == $totalTime && empty($totalTime)) && !(is_object($totalTime) && $totalTime instanceOf \DateTime)) {
                $totalTime = new \DateTime($totalTime);
            }
            
            if (null === $totalTime || empty($totalTime)) {
                $totalTime = new \DateTime();
            }
            
            if ($this->totalTime != $totalTime) {
                $this->totalTime = $totalTime;
            }
        }
    }
    
    
    
    
    /**
     * Checks whether the player field contains a valid user reference.
     * This method is used for validation.
     *
     * @Assert\IsTrue(message="This value must be a valid user id.")
     *
     * @return boolean True if data is valid else false
     */
    public function isPlayerUserValid()
    {
        return $this['player'] instanceof UserEntity;
    }
    
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array List of resulting arguments
     */
    public function createUrlArgs()
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'zikulatrivialmodule.ui_hooks.results';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects Objects that are added to this array
     * 
     * @return array List of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = [])
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Result ' . $this->getKey();
    }
    
    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    }
}