<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnversion.php 31 2008-12-23 20:55:41Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

$dom = ZLanguage::getModuleDomain('Trivial');
$modversion['name']           = 'Trivial';
$modversion['displayname']    = __('Trivial', $dom);
$modversion['url']            = __('Trivial', $dom);
$modversion['version']        = '1.1';
$modversion['description']    = __('Quiz game about the universe Might and Magic', $dom);
$modversion['credits']        = 'pndocs/changelog.txt';
$modversion['help']           = 'pndocs/readme.txt';
$modversion['changelog']      = 'pndocs/changelog.txt';
$modversion['license']        = 'pndocs/license.txt';
$modversion['official']       = 0;
$modversion['author']         = 'Krator';
$modversion['contact']        = 'http://www.heroesofmightandmagic.es';
$modversion['admin']          = 1;
$modversion['user']           = 1;
$modversion['securityschema'] = array('Trivial::' => 'Trivial::');
