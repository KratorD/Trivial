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

namespace Zikula\TrivialModule\Controller\Base;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zikula\Core\Controller\AbstractController;

/**
 * Ajax controller base class.
 */
abstract class AbstractAjaxController extends AbstractController
{
    
    /**
     * Retrieve item list for finder selections in Forms, Content type plugin and Scribite.
     *
     * @param string $ot      Name of currently used object type
     * @param string $sort    Sorting field
     * @param string $sortdir Sorting direction
     *
     * @return JsonResponse
     */
    public function getItemListFinderAction(Request $request)
    {
        if (!$this->hasPermission('ZikulaTrivialModule::Ajax', '::', ACCESS_EDIT)) {
            return true;
        }
        
        $objectType = $request->query->getAlnum('ot', 'tournament');
        $controllerHelper = $this->get('zikula_trivial_module.controller_helper');
        $contextArgs = ['controller' => 'ajax', 'action' => 'getItemListFinder'];
        if (!in_array($objectType, $controllerHelper->getObjectTypes('controllerAction', $contextArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('controllerAction', $contextArgs);
        }
        
        $repository = $this->get('zikula_trivial_module.entity_factory')->getRepository($objectType);
        $entityDisplayHelper = $this->get('zikula_trivial_module.entity_display_helper');
        $descriptionFieldName = $entityDisplayHelper->getDescriptionFieldName($objectType);
        
        $sort = $request->query->getAlnum('sort', '');
        if (empty($sort) || !in_array($sort, $repository->getAllowedSortingFields())) {
            $sort = $repository->getDefaultSortingField();
        }
        
        $sdir = strtolower($request->query->getAlpha('sortdir', ''));
        if ($sdir != 'asc' && $sdir != 'desc') {
            $sdir = 'asc';
        }
        
        $where = ''; // filters are processed inside the repository class
        $searchTerm = $request->query->get('q', '');
        $sortParam = $sort . ' ' . $sdir;
        
        $entities = [];
        if ($searchTerm != '') {
            list ($entities, $totalAmount) = $repository->selectSearch($searchTerm, [], $sortParam, 1, 50);
        } else {
            $entities = $repository->selectWhere($where, $sortParam);
        }
        
        $slimItems = [];
        $component = 'ZikulaTrivialModule:' . ucfirst($objectType) . ':';
        foreach ($entities as $item) {
            $itemId = $item->getKey();
            if (!$this->hasPermission($component, $itemId . '::', ACCESS_READ)) {
                continue;
            }
            $slimItems[] = $this->prepareSlimItem($repository, $objectType, $item, $itemId, $descriptionFieldName);
        }
        
        // return response
        return $this->json($slimItems);
    }
    
    /**
     * Builds and returns a slim data array from a given entity.
     *
     * @param EntityRepository $repository       Repository for the treated object type
     * @param string           $objectType       The currently treated object type
     * @param object           $item             The currently treated entity
     * @param string           $itemId           Data item identifier(s)
     * @param string           $descriptionField Name of item description field
     *
     * @return array The slim data representation
     */
    protected function prepareSlimItem($repository, $objectType, $item, $itemId, $descriptionField)
    {
        $previewParameters = [
            $objectType => $item
        ];
        $contextArgs = ['controller' => $objectType, 'action' => 'display'];
        $previewParameters = $this->get('zikula_trivial_module.controller_helper')->addTemplateParameters($objectType, $previewParameters, 'controllerAction', $contextArgs);
    
        $previewInfo = base64_encode($this->get('twig')->render('@ZikulaTrivialModule/External/' . ucfirst($objectType) . '/info.html.twig', $previewParameters));
    
        $title = $this->get('zikula_trivial_module.entity_display_helper')->getFormattedTitle($item);
        $description = $descriptionField != '' ? $item[$descriptionField] : '';
    
        return [
            'id'          => $itemId,
            'title'       => str_replace('&amp;', '&', $title),
            'description' => $description,
            'previewInfo' => $previewInfo
        ];
    }
}