<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnadmin.php 31 2008-12-23 20:55:41Z Guite $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function Trivial_admin_main()
{
    if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
        return LogUtil::registerPermissionError();
    }

    $render = & pnRender::getInstance('Trivial', false);
    return $render->fetch('Trivial_admin_main.htm');
}

function Trivial_admin_viewCompeticiones()
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
  
  // Obtener todos los torneos
	$torneos = pnModAPIFunc('Trivial', 'user', 'getAllTorneos',
												array('combo' 	 => ''));
	
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');

	//Enviarlas a la plantilla
	$render->assign('torneos', $torneos);
	
	return $render->fetch('Trivial_admin_viewCompeticiones.htm');
  
}

function Trivial_admin_newCompeticion()
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	//Obtener los parametros
	//$ID  = (int)FormUtil::getPassedValue('ID', isset($args['ID']) , 'GET');
	
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');

	//Enviarlas a la plantilla
	//$pnRender->assign('torneos', $torneos);
	
	return $render->fetch('Trivial_admin_newCompeticion.htm');
  
}

function Trivial_admin_createCompeticion($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	extract($args);
	unset($args);
	
	list($txtNombre,
			 $Fecha_Ini,
			 $Fecha_Fin,
			 $txtNoQ,
			 $chkUnica,
			 $chkActiva) = pnVarCleanFromInput(	'txtNombre', 'Fecha_Ini', 'Fecha_Fin', 'txtNoQ',
												'chkUnica', 'chkActiva');
	
	// No html permitido para las casillas de texto
	list($txtNombre, $txtNoQ)	= pnVarPrepForDisplay($txtNombre, $txtNoQ);

	//Check for input parameters (mandatory!!)
	if ($txtNombre == '' ){
	
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 1;  //Error por parametros de entrada vacios
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
		
	}
	//Comprobar que la fecha inicial es menor a la final
	$exito = pnModAPIFunc('Trivial', 'admin', 'chkFechas', 
												array(	'fechIni'    => $Fecha_Ini,
														'fechFin'    => $Fecha_Fin));
	if ($exito == false){
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 2;  //Error por fecha superior a otra
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
	}
	//Comprobar que el nº preguntas es un numero
	if (!is_numeric($txtNoQ)){
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 3;  //Error por no ser numerico
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
	}
	//Preparar los datos para añadir el registro
	$record['Nombre'] = $txtNombre;
	$record['F_Inicio'] = $Fecha_Ini;
	$record['F_Fin'] = $Fecha_Fin;
	$record['Activa'] = $chkActiva;
	$record['N_Preguntas'] = $txtNoQ;
	$record['Unica'] = $chkUnica;
	//Añadir el registro a la base de datos
	$result = pnModAPIFunc('Trivial', 'admin', 'insertComp', $record);
	if ($result === false){
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Error trying insert in the DB!', $dom));
	}
			
	$url = "index.php?module=Trivial&type=admin&func=viewCompeticiones";
	// Construimos y devolvemos la Vista
	$render = pnRender::getInstance('Trivial');
	
	$render->assign('url', $url);
	return $render->fetch('Trivial_admin_createdComp.htm');
  
}

function Trivial_admin_editCompeticion($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	//Obtener los parametros
	$ID  = (int)FormUtil::getPassedValue('ID', isset($args['ID']) , 'GET');
	
	//Obtener los datos de la competicion
	$comp = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', array('idTorneo' => $ID));
	
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');
	
	$render->assign('comp', $comp);

	return $render->fetch('Trivial_admin_editCompeticion.htm');
	
}

function Trivial_admin_updateCompeticion($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	//Obtener los parametros
	$ID  = (int)FormUtil::getPassedValue('ID', isset($args['ID']) , 'GET');
	
	extract($args);
	unset($args);
	
	list(	$txtNombre,
			$Fecha_Ini,
			$Fecha_Fin,
			$txtNoQ,
			$chkUnica,
			$chkActiva) = pnVarCleanFromInput('txtNombre', 'Fecha_Ini', 'Fecha_Fin', 'txtNoQ', 'chkUnica', 'chkActiva');
	
	// No html permitido para las casillas de texto
	list($txtNombre, $txtNoQ) = pnVarPrepForDisplay($txtNombre, $txtNoQ);

	//Check for input parameters (mandatory!!)
	if ($txtNombre 	== '' ){
	
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 1;  //Error por parametros de entrada vacios
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
		
	}
	//Comprobar que la fecha inicial es menor a la final
	$exito = pnModAPIFunc('Trivial', 'admin', 'chkFechas', 
												array('fechIni'    => $Fecha_Ini,
												      'fechFin'    => $Fecha_Fin));
	if ($exito == false){
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 2;  //Error por fecha superior a otra
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
	}
	//Comprobar que el nº preguntas es un numero
	if (!is_numeric($txtNoQ)){
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 3;  //Error por no ser numerico
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
	}
	
	//Preparar los datos para modificar el registro
	/*$record['ID']			  = $ID;
	$record['Pregunta'] = $txtPregunta;
	$record['Resp1'] 		= $txtResp1;
	$record['Resp2'] 		= $txtResp2;
	$record['Resp3'] 		= $txtResp3;
	$record['Resp4'] 		= $txtResp4;
	$record['RespCor']  = $txtRespCor;
	$record['Heroes'] 	= $cmbHeroe;*/
	
	//Actualizar la pregunta
	$result = pnModAPIFunc('Trivial', 'admin', 'updateCompeticion',	
							array(	'ID' 	 	  => $ID,
									'Nombre' 	  => $txtNombre,
									'F_Inicio'	  => $Fecha_Ini,
									'F_Fin'		  => $Fecha_Fin,
									'Activa'	  => $chkActiva,
									'N_Preguntas' => $txtNoQ,
									'Unica'	 	  => $chkUnica
							));
	
	if ($result === false)
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Error trying modify in the DB!', $dom));
			
	$url = "index.php?module=Trivial&type=admin&func=viewCompeticiones";
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');
	
	$render->assign('url', $url);
	
	return $render->fetch('Trivial_admin_UpdateCompeticion.htm');
  
}

function Trivial_admin_deleteCompeticion($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	$confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
	
	//Recuperar los parametros
	$ID = isset($args['ID']) ? $args['ID'] : FormUtil::getPassedValue('ID', null, 'REQUEST');

	// Check for confirmation.
	if (empty($confirmation)) {
		// No confirmation yet
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$render->assign('id', $ID);

		// Return the output that has been generated by this function
		return $render->fetch('Trivial_admin_deleteCompeticion.htm');
	}
	// Confirm authorisation code
	if (!SecurityUtil::confirmAuthKey()) {
		return LogUtil::registerAuthidError (pnModURL('Trivial', 'admin', 'viewCompeticion'));
	}
	
	//Lenguaje
	$dom = ZLanguage::getModuleDomain('Trivial');
	
	//Confirmamos que el registro que queremos borrar, existe.
	$lista = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', array('idTorneo' => $ID));
	
	if ($lista == false) {
		return LogUtil::registerError(__('Error! Competition not found.', $dom));
	}
	
	if (pnModAPIFunc('Trivial', 'admin', 'deleteCompeticion', array('id' =>$ID))) {
		// Success
		LogUtil::registerStatus (__('Competition delete sucessfully.', $dom));
	}
	return pnRedirect(pnModURL('Trivial', 'admin', 'viewCompeticiones'));
	
}

function Trivial_admin_viewPreguntas()
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
  
	//Obtener los parametros
	$page  = (int)FormUtil::getPassedValue('page', isset($args['page']) ? $args['page'] : 1, 'GET');
	$idTorneo = FormUtil::getPassedValue('comboTorneo', isset($args['comboTorneo']) , 'POST');
	$cmbHeroe = FormUtil::getPassedValue('cmbHeroe', isset($args['cmbHeroe']), 'POST');
	if ($idTorneo == '' && $cmbHeroe == ''){
		$idTorneo = FormUtil::getPassedValue('comboTorneo', isset($args['comboTorneo']) , 'GET');
		$cmbHeroe = FormUtil::getPassedValue('cmbHeroe', isset($args['cmbHeroe']), 'GET');
	}

	// Obtener todas las variables del modulo
	$modvars = pnModGetVar('Trivial');
	
	$itemsperpage = $modvars['itemsperpage'];
	
	// Primer elemento a obtener de la paginacion
	$startnum = (($page - 1) * $itemsperpage) + 1;
	
	//Obtenemos todos los torneos
	$torneos = pnModAPIFunc('Trivial', 'user', 'getAllTorneos',
							array('combo' 	 => 'X'));	
													
	//Obtener las preguntas sobre el torneo elegido
	if ($idTorneo != ''){
		$question = pnModAPIFunc('Trivial', 'user', 'getQuestions', 
								  array('startnum' => $startnum,
										'numitems' => $itemsperpage,
										'torneo'   => $idTorneo,
										'Heroe'    => $cmbHeroe));
		
		if ($idTorneo == 1){
			$numPreg = pnModAPIFunc('Trivial', 'user', 'countitemsPreg', 
									array(	'Def' => 'X',
											'Gen' => $cmbHeroe));
		}else{
			$comp = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', array('idTorneo' => $idTorneo));
			$numPreg = pnModAPIFunc('Trivial', 'user', 'countitemsPreg', array(	'Heroes' => $idTorneo));
		}
	}
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');

	//Enviarlas a la plantilla
	$render->assign('torneo', $torneos);
	$render->assign('questions', $question);
	$render->assign('idTorneo', $idTorneo);
	$render->assign('numPreg', $numPreg);
	$render->assign('comp', $comp);
	$render->assign($modvars);
	$render->assign('startnum', $startnum);
	
	// Asignar los valores al sistema de paginación
	$render->assign('pager', array(	'numitems' => $numPreg,
									'itemsperpage' => $itemsperpage));
	
	return $render->fetch('Trivial_admin_viewPreguntas.htm');
  
}

function Trivial_admin_newPregunta()
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	//Obtener los parametros
	//$ID  = (int)FormUtil::getPassedValue('ID', isset($args['ID']) , 'GET');
	//Obtenemos todos los torneos
	$torneos = pnModAPIFunc('Trivial', 'user', 'getAllTorneos',	
													array('combo' 	 => ''));

	$cont = 0;
	foreach($torneos as $line){
		if ($line['Nombre'] == 'General'){
			$combo[$cont] = 'Heroes I';
			$cont++;
			$combo[$cont] = 'Heroes II';
			$cont++;
			$combo[$cont] = 'Heroes III';
			$cont++;
			$combo[$cont] = 'Heroes IV';
			$cont++;
			$combo[$cont] = 'Heroes V';
			$cont++;
			$combo[$cont] = 'Heroes VI';
			$cont++;
		}
	}
													
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');

	//Enviarlas a la plantilla
	$render->assign('combo', $combo);
	$render->assign('torneos', $torneos);
	
	return $render->fetch('Trivial_admin_newPregunta.htm');
  
}

function Trivial_admin_createPregunta($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	extract($args);
	unset($args);
	
	list($txtPregunta,
			 $txtResp1,
			 $txtResp2,
			 $txtResp3,
			 $txtResp4,
			 $txtRespCor,
			 $cmbHeroe) = pnVarCleanFromInput(	'txtPregunta', 'txtResp1', 'txtResp2', 'txtResp3', 'txtResp4',
												'txtRespCor', 'cmbHeroe');
	
	// No html permitido para las casillas de texto
	list($txtPregunta, $txtResp1, $txtResp2, $txtResp3, $txtResp4, $txtRespCor)	
		= pnVarPrepForDisplay($txtPregunta, $txtResp1, $txtResp2, $txtResp3, $txtResp4, $txtRespCor);

	//Check for input parameters (mandatory!!)
	if ($txtPregunta 	== '' ||
		$txtResp1 		== '' ||
		$txtResp2 		== '' ||
		$txtRespCor 	== '' ||
		$cmbHeroe 		== '' ){
	
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 1;  //Error por parametros de entrada vacios
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
		
	}
	
	//Comprobar que el nº preguntas es un numero
	if (!is_numeric($txtRespCor)){
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 3;  //Error por no ser numerico
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
	}else{
		if ($txtRespCor < 1 || $txtRespCor > 4){
			// Construimos y devolvemos la Vista
			$render = & pnRender::getInstance('Trivial');
			$tipo_error = 4;  //Error por no ser numerico
			$render->assign('tipo_error', $tipo_error);
			return $render->fetch('Trivial_admin_error.htm');
		}
	}
	//Preparar los datos para añadir el registro
	$record['Pregunta'] = $txtPregunta;
	$record['Resp1'] 		= $txtResp1;
	$record['Resp2'] 		= $txtResp2;
	$record['Resp3'] 		= $txtResp3;
	$record['Resp4'] 		= $txtResp4;
	$record['RespCor']  = $txtRespCor;
	$record['Heroes'] 	= $cmbHeroe;
	//Añadir el registro a la base de datos
	$result = pnModAPIFunc('Trivial', 'admin', 'insertPreg', $record);
	if ($result === false){
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Error trying insert in the DB!', $dom));
	}

	$url = "index.php?module=Trivial&type=admin&func=viewPreguntas";
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');
	
	$render->assign('url', $url);
	return $render->fetch('Trivial_admin_createdPreg.htm');
  
}

function Trivial_admin_editPregunta($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	//Obtener los parametros
	$ID  = (int)FormUtil::getPassedValue('ID', isset($args['ID']) , 'GET');
	
	//Obtener los datos de la pregunta
	$preg = pnModAPIFunc('Trivial', 'user', 'getQuestion',	
												array('idPreg' 	 => $ID));
	
	//Obtenemos todos los torneos
	$torneos = pnModAPIFunc('Trivial', 'user', 'getAllTorneos',	
												array('combo' 	 => ''));

	$cont = 0;
	foreach($torneos as $line){
		if ($line['Nombre'] == 'General'){
			$combo[$cont] = 'Heroes I';
			$cont++;
			$combo[$cont] = 'Heroes II';
			$cont++;
			$combo[$cont] = 'Heroes III';
			$cont++;
			$combo[$cont] = 'Heroes IV';
			$cont++;
			$combo[$cont] = 'Heroes V';
			$cont++;
			$combo[$cont] = 'Heroes VI';
			$cont++;
		}
	}
	
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');
	
	$render->assign('preg', $preg);
	$render->assign('combo', $combo);
	$render->assign('torneos', $torneos);
	return $render->fetch('Trivial_admin_editPregunta.htm');
	
}

function Trivial_admin_updatePregunta($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	//Obtener los parametros
	$ID  = (int)FormUtil::getPassedValue('ID', isset($args['ID']) , 'GET');
	
	extract($args);
	unset($args);
	
	list($txtPregunta,
			 $txtResp1,
			 $txtResp2,
			 $txtResp3,
			 $txtResp4,
			 $txtRespCor,
			 $cmbHeroe) = pnVarCleanFromInput(	'txtPregunta', 'txtResp1', 'txtResp2', 'txtResp3', 'txtResp4',
												'txtRespCor', 'cmbHeroe');
	
	// No html permitido para las casillas de texto
	list($txtPregunta, $txtResp1, $txtResp2, $txtResp3, $txtResp4, $txtRespCor)	
		= pnVarPrepForDisplay($txtPregunta, $txtResp1, $txtResp2, $txtResp3, $txtResp4, $txtRespCor);

	//Check for input parameters (mandatory!!)
	if ($txtPregunta 	== '' ||
		$txtResp1 		== '' ||
		$txtResp2 		== '' ||
		$txtRespCor 	== '' ||
		$cmbHeroe 		== '' ){
	
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 1;  //Error por parametros de entrada vacios
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
		
	}
	
	//Comprobar que el nº preguntas es un numero
	if (!is_numeric($txtRespCor)){
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$tipo_error = 3;  //Error por no ser numerico
		$render->assign('tipo_error', $tipo_error);
		return $render->fetch('Trivial_admin_error.htm');
	}else{
		if ($txtRespCor < 1 || $txtRespCor > 4){
			// Construimos y devolvemos la Vista
			$render = & pnRender::getInstance('Trivial');
			$tipo_error = 4;  //Error por no ser numerico
			$render->assign('tipo_error', $tipo_error);
			return $render->fetch('Trivial_admin_error.htm');
		}
	}
	//Preparar los datos para modificar el registro
	/*$record['ID']			  = $ID;
	$record['Pregunta'] = $txtPregunta;
	$record['Resp1'] 		= $txtResp1;
	$record['Resp2'] 		= $txtResp2;
	$record['Resp3'] 		= $txtResp3;
	$record['Resp4'] 		= $txtResp4;
	$record['RespCor']  = $txtRespCor;
	$record['Heroes'] 	= $cmbHeroe;*/
	
	//Actualizar la pregunta
	$result = pnModAPIFunc('Trivial', 'admin', 'updateQuestion',	
														array('ID' 	 => $ID,
															'Pregunta' => $txtPregunta,
															'Resp1'		 => $txtResp1,
															'Resp2'		 => $txtResp2,
															'Resp3'		 => $txtResp3,
															'Resp4'		 => $txtResp4,
															'RespCor'  => $txtRespCor,
															'Heroes'	 => $cmbHeroe
														 ));
	
	if ($result === false){
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Error trying modify in the DB!', $dom));
	}
			
	$url = "index.php?module=Trivial&type=admin&func=viewPreguntas";
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');
	
	$render->assign('url', $url);
	
	return $render->fetch('Trivial_admin_UpdatePregunta.htm');
  
}

function Trivial_admin_deletePregunta($args)
{
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	$confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');
	
	//Recuperar los parametros
	$ID = isset($args['ID']) ? $args['ID'] : FormUtil::getPassedValue('ID', null, 'REQUEST');

	// Check for confirmation.
	if (empty($confirmation)) {
		// No confirmation yet
		// Construimos y devolvemos la Vista
		$render = & pnRender::getInstance('Trivial');
		$render->assign('id', $ID);

		// Return the output that has been generated by this function
		return $render->fetch('Trivial_admin_deletePregunta.htm');
	}
	// Confirm authorisation code
	if (!SecurityUtil::confirmAuthKey()) {
		return LogUtil::registerAuthidError (pnModURL('Trivial', 'admin', 'viewPreguntas'));
	}
	
	//Lenguaje
	$dom = ZLanguage::getModuleDomain('Trivial');
	
	//Confirmamos que el registro que queremos borrar, existe.
	$preg = pnModAPIFunc('Trivial', 'user', 'getQuestion', array('idPreg' 	 => $ID));
	
	if ($preg == false) {
		return LogUtil::registerError(__('Error! Question not found.', $dom));
	}
	
	if (pnModAPIFunc('Trivial', 'admin', 'deletePregunta', array('id' =>$ID))) {
		// Success
		LogUtil::registerStatus (__('Question delete sucessfully.', $dom));
	}
	return pnRedirect(pnModURL('Trivial', 'admin', 'viewPreguntas'));
	
}

function Trivial_admin_viewResults($args)
{

	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_ADMIN)) {
		return LogUtil::registerPermissionError();
	}
	$filtro = FormUtil::getPassedValue('cmbFiltro', null, 'POST');
	
	// Obtener todos los torneos
	$torneos = pnModAPIFunc('Trivial', 'user', 'getAllTorneos',
							array('combo' 	 => ''));
	
	if ($filtro > 1){
		$comp = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', array('idTorneo' => $filtro));
	}
	
	// Obtener los resultados
	if (!empty($filtro)){
		$resultados = pnModAPIFunc('Trivial', 'user', 'getAllResul',
									array(	'Torneo' 	 => $filtro));
	}

	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');

	//Enviarlas a la plantilla
	$render->assign('torneos', $torneos);
	$render->assign('resultados', $resultados);
	$render->assign('filtro', $filtro);
	$render->assign('comp', $comp);
	
	return $render->fetch('Trivial_admin_viewResults.htm');
}