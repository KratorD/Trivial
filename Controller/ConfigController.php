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

namespace Zikula\TrivialModule\Controller;

use Zikula\TrivialModule\Controller\Base\AbstractConfigController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Zikula\ThemeModule\Engine\Annotation\Theme;

/**
 * Config controller implementation class.
 *
 * @Route("/config")
 */
class ConfigController extends AbstractConfigController
{
    /**
     * This method takes care of the application configuration.
     *
     * @Route("/config",
     *        methods = {"GET", "POST"}
     * )
     * @Theme("admin")
     *
     * @param Request $request Current request instance
     *
     * @return Response Output
     *
     * @throws AccessDeniedException Thrown if the user doesn't have required permissions
     */
    public function configAction(Request $request)
    {
        return parent::configAction($request);
    }

    // feel free to add your own config controller methods here
}
