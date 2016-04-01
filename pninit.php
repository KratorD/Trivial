<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pninit.php 31 2008-12-23 20:55:41Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function Trivial_init()
{
    $tables = array('trivial_preg', 'trivial_clas', 'trivial_competiciones', 'trivial_resultados');
    foreach ($tables as $table) {
        if (!DBUtil::createTable($table)) {
            return false;
        }
    }
    
    pnModSetVar('Trivial', 'modulestylesheet', 'Trivial.css');
    return true;
}

function Trivial_upgrade($oldversion)
{
    return true;
}

function Trivial_delete()
{
    $tables = array('trivial_preg', 'trivial_clas', 'trivial_competiciones', 'trivial_resultados');
    foreach ($tables as $table) {
        if (!DBUtil::dropTable($table)) {
            return false;
        }
    }
    
    pnModDelVar('Trivial');
    return true;
}
