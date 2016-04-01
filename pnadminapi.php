<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadminapi.php 31 2008-12-23 20:55:41Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function Trivial_adminapi_chkFechas($args)
{

	// Valida los parámetros requeridos
  if (!isset($args['fechIni']) || !isset($args['fechFin']) ) {
		return LogUtil::registerError (_MODARGSERROR);
	}
	$ts_fecha_ini = DateUtil::makeTimestamp($args['fechIni']);
	$ts_fecha_fin = DateUtil::makeTimestamp($args['fechFin']);
	if ($ts_fecha_ini > $ts_fecha_fin){
		return false;
	}

	return true;

}

function Trivial_adminapi_insertComp($args)
{
	
	if (!isset($args) || empty($args)) {
		return LogUtil::registerError (_MODARGSERROR);
	}
	extract($args);
	unset($args);

	if (!isset($Nombre) ||
			!isset($F_Inicio) ||
			!isset($F_Fin) ||
			!isset($N_Preguntas) ){
		
		return LogUtil::registerError ('Error! Campo vacio al intentar insertar registro en BD.');
			
	}
	$args['Nombre'] 		 = pnVarPrepForStore($Nombre);
	$args['F_Inicio'] 	 = pnVarPrepForStore($F_Inicio);
	$args['F_Fin'] 			 = pnVarPrepForStore($F_Fin);
	if ($Activa == 'on'){
		$args['Activa'] 	 = 1;
	}else{
		$args['Activa'] 	 = 0;
	}	
	$args['N_Preguntas'] = pnVarPrepForStore($N_Preguntas);
	if ($Unica == 'on'){
		$args['Unica'] 		 = 1;
	}else{
		$args['Unica'] 		 = 0;
	}
	
	return DBUtil::insertObject($args, 'trivial_competiciones','ID', false, true);
	
}

/**
 * Actualizar una competición
 * @param $args['id'] ID de la competición
 * @return bool true on success, false on failure
 */
function Trivial_adminapi_updateCompeticion($args)
{
	//Mandatory
	if (!isset($args['ID']) || empty($args['ID'])) {
		return LogUtil::registerError (_MODARGSERROR);
	}
	$ID = $args['ID'];
	extract ($args);
	if ($Nombre != ''){
		$cadena.= "`Nombre` = '".$Nombre."'";
		$mas = ",";
	}
	if ($F_Inicio != ''){
		$cadena.= $mas."`F_Inicio` = '".$F_Inicio."'";
		$mas = ",";
	}else{
		$cadena.= $mas."`F_Inicio` = NULL";
		$mas = ",";
	}
	if ($F_Fin != ''){
		$cadena.= $mas."`F_Fin` = '".$F_Fin."'";
		$mas = ",";
	}else{
		$cadena.= $mas."`F_Fin` = NULL";
		$mas = ",";
	}	
	if ($Activa == 'on'){		
		$cadena.= $mas."`Activa` = 1";
		$mas = ",";
	}else{
		$cadena.= $mas."`Activa` = NULL";
		$mas = ",";
	}
	if ($N_Preguntas != ''){
		$cadena.= $mas."`N_Preguntas` = '".$N_Preguntas."'";
		$mas = ",";
	}else{
		$cadena.= $mas."`N_Preguntas` = NULL";
		$mas = ",";
	}
	if ($Unica == 'on'){
		$cadena.= $mas."`Unica` = 1";
		$mas = ",";
	}else{
		$cadena.= $mas."`Unica` = NULL";
		$mas = ",";
	}

	$sql = "UPDATE `hprt_trivial_competiciones` SET ".$cadena." WHERE `ID` = ".$ID;
	//$sql = "UPDATE `z_trivial_competiciones` SET ".$cadena." WHERE `ID` = ".$ID;

	return DBUtil::executeSQL($sql);
	
}

/**
 * Borrar una competición
 * @param $args['id'] ID de la Repeticion
 * @return bool true on success, false on failure
 */
function Trivial_adminapi_deleteCompeticion($args)
{
	
	// Argument check
  if (!isset($args['id'])) {
  	return LogUtil::registerError (_MODARGSERROR);
  }

  //Confirmamos que el registro que queremos borrar, existe.
	$item = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', array('idTorneo' => $args['id']));

  if ($item === false) {
  	return LogUtil::registerError (_NOSUCHITEM);
  }

  if (!DBUtil::deleteObjectByID('trivial_competiciones', $args['id'], 'ID')) {
  	return LogUtil::registerError (_DELETEFAILED);
  }

  // The item has been modified, so we clear all cached pages of this item.
  $pnRender = pnRender::getInstance('Trivial');
  $pnRender->clear_cache(null, $args['id']);
  $pnRender->clear_cache('Trivial_admin_viewCompeticiones.htm');

  return true;
}

function Trivial_adminapi_insertPreg($args)
{
	
	if (!isset($args) || empty($args)) {
		return LogUtil::registerError (_MODARGSERROR);
	}
	extract($args);
	unset($args);

	if (!isset($Pregunta) ||
			!isset($Resp1) ||
			!isset($Resp2) ||
			!isset($RespCor) ||
			!isset($Heroes) ){
		
		return LogUtil::registerError ('Error! Campo vacio al intentar insertar registro en BD.');
			
	}
	$args['Pregunta'] = pnVarPrepForStore($Pregunta);
	$args['Resp1'] 	  = pnVarPrepForStore($Resp1);
	$args['Resp2'] 		= pnVarPrepForStore($Resp2);
	$args['Resp3'] 		= pnVarPrepForStore($Resp3);
	$args['Resp4'] 		= pnVarPrepForStore($Resp4);
	$args['RespCor']  = pnVarPrepForStore($RespCor);
	$args['Heroes']   = pnVarPrepForStore($Heroes);
	
	return DBUtil::insertObject($args, 'trivial_preg','ID', false, true);
	
}

function Trivial_adminapi_updateQuestion($args)
{
	//Mandatory
	if (!isset($args['ID']) || empty($args['ID'])) {
		return LogUtil::registerError (_MODARGSERROR);
	}
	$ID = $args['ID'];
	extract ($args);
	if ($Pregunta != ''){
		$cadena.= "`Pregunta` = '".$Pregunta."'";
		$mas = ",";
	}
	if ($Resp1 != ''){
		$cadena.= $mas."`Resp1` = '".$Resp1."'";
		$mas = ",";
	}
	if ($Resp2 != ''){
		$cadena.= $mas."`Resp2` = '".$Resp2."'";
		$mas = ",";
	}
	if ($Resp3 != ''){
		$cadena.= $mas."`Resp3` = '".$Resp3."'";
		$mas = ",";
	}else{
		$cadena.= $mas."`Resp3` = NULL";
		$mas = ",";
	}
	if ($Resp4 != ''){
		$cadena.= $mas."`Resp4` = '".$Resp4."'";
		$mas = ",";
	}else{
		$cadena.= $mas."`Resp4` = NULL";
		$mas = ",";
	}
	if ($RespCor != ''){
		$cadena.= $mas."`RespCor` = '".$RespCor."'";
		$mas = ",";
	}
	if ($Heroes != ''){
		$cadena.= $mas."`Heroes` = '".$Heroes."'";
		$mas = ",";
	}
	$sql = "UPDATE `hprt_trivial_preg` SET ".$cadena." WHERE `ID` = ".$ID;
	//$sql = "UPDATE `z_trivial_preg` SET ".$cadena." WHERE `ID` = ".$ID;
	
	return DBUtil::executeSQL($sql);
	
}

/**
 * Borrar una pregunta
 * @param $args['id'] ID de la Pregunta
 * @return bool true on success, false on failure
 */
function Trivial_adminapi_deletePregunta($args)
{
	
	// Argument check
	if (!isset($args['id'])) {
		return LogUtil::registerError (_MODARGSERROR);
	}

	//Confirmamos que el registro que queremos borrar, existe.
	$item = pnModAPIFunc('Trivial', 'user', 'getQuestion', array('idPreg' 	 => $args['id']));

	if ($item === false) {
		return LogUtil::registerError (_NOSUCHITEM);
	}

	if (!DBUtil::deleteObjectByID('trivial_preg', $args['id'], 'ID')) {
		return LogUtil::registerError (_DELETEFAILED);
	}

	// The item has been modified, so we clear all cached pages of this item.
	$pnRender = pnRender::getInstance('Trivial');
	$pnRender->clear_cache(null, $args['id']);
	$pnRender->clear_cache('Trivial_admin_viewPreguntas.htm');

	return true;
}