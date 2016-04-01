/**
 * PostNuke Application Framework
 *
 * @copyright (c) 2002, Zikula Development Team
 * @link http://www.postnuke.com
 * @version $Id: trabajos.js 18677 2006-04-06 12:07:09Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Zikula_3rdParty_Modules
 * @subpackage Trabajos
*/

/**
 * Load options for combo box
 *
 *@params none;
 *@return none;
 *@author Krator
 */
 
function mostrarDiv(id)
{
	var el = document.getElementById(id);
	var indice = document.juegaTorneo.comboTorneo.selectedIndex;
	
	if (document.juegaTorneo.comboTorneo.options[indice].text == 'General'){
		el.style.display = 'block';
	}else{
		el.style.display = 'none';
	}
	
	//el.style.display = (el.style.display == 'none') ? 'block' : 'none';	

}
