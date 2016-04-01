<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnuserapi.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */


/**
 * getCompeticiones
 * Function to return the information about a Competition
 *
 *@param idTorneo int with ID of the championship
 *@returns *array* record with the information of the competition
 */
function Trivial_userapi_getCompeticiones($args)
{
	
	//Check optional parameters
	if (!is_numeric($args['idTorneo'])) {
		return LogUtil::registerArgsError();
	}
	
	if (isset($args['idTorneo'])){
		//Get the record of the competition
		$record = DBUtil::selectObjectByID('trivial_competiciones', $args['idTorneo'], 'ID');
	}else{
		//Clause where *here*
	}
	
	return $record;
	
}

/**
 * getClasification
 * Function to return the information of users in "General" competition
 *
 *@param idUser int uid
 *@returns record with the information of the user in "General"
 */
function Trivial_userapi_getClasification($args)
{

	//Check optional parameters
	if (!is_numeric($args['idUser'])) {
		return LogUtil::registerArgsError();
	}
	
	if (isset($args['idUser'])){
		//Get the record of the competition
		$record = DBUtil::selectObjectByID('trivial_clas', $args['idUser'], 'id_user');
	}else{
		//Clause where *here*
	}
	
	return $record;
	
}

function Trivial_userapi_getAll($args)
{

	// Optional arguments.
  if (!isset($args['startnum']) || empty($args['startnum'])) {
  	$args['startnum'] = 1;
  }
	if (!isset($args['numitems']) || empty($args['numitems'])) {
  	$args['numitems'] = -1;
  }
	if (!isset($args['order']) || empty($args['order'])) {
  	$args['order'] = 'Media DESC'; 
  }
	/*if (!isset($args['tipo']) || empty($args['tipo'])) {
  	$args['order'].= ' desc';
  } else {
		$args['order'].= ' '.$args['tipo'];
	}*/
	if (!is_numeric($args['startnum']) ||
		!is_numeric($args['numitems'])) {
		return LogUtil::registerArgsError();
	}
		
	// Extrae el array de elementos de la BD (paginado)
	//$where = "`Estado` LIKE 'Aceptado'";
	$objArray = DBUtil::selectObjectArray ('trivial_clas', $where, $args['order'], $args['startnum']-1, $args['numitems']);

	// Validamos que el elemento existe en la BD
	if ($objArray === false) {
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Error! Classification not found.', $dom));
	}

	// Retorna el objeto
	return $objArray;
}

function Trivial_userapi_getAllTorneos($args)
{
	//Mandatory fields
	if (!isset($args['combo']) ){ //|| empty($args['combo'])){
		return LogUtil::registerArgsError();
	}
	//Construir la clausula WHERE
	if ($args['Activa'] == 'X'){
		// Obtiene todos los Torneos activos
		$queryargs[] = "`Activa` = 1";
	}
	if ($args['porFecha'] == 'X'){
		// Obtiene todos los Torneos activos
		$queryargs[] = "`F_Inicio` <= '".date('Y-m-d')."' AND `F_Fin` >= '".date('Y-m-d')."'";
	}
		
	if (count($queryargs) > 0) {
		$where = ' WHERE ' . implode(' AND ', $queryargs);
	}

  if ($args['combo'] != 'X'){

		$objArray = DBUtil::selectObjectArray ('trivial_competiciones', $where);
		
		// Validamos que el elemento existe en la BD
		if ($objArray == false) {
			$dom = ZLanguage::getModuleDomain('Trivial');
			return LogUtil::registerError(__('Error! Competition not found.', $dom));
		}
	
		//Return objects retrieved
		return $objArray;
		
	}else{	 //Return values in a combo box
		
		$pntable = pnDBGetTables();
		$table = $pntable['trivial_competiciones'];
	
		$sql = "SELECT `Nombre`, `ID` FROM $table".$where;
		//$sql = "SELECT `Nombre`, `ID` FROM `z_trivial_competiciones`".$where;
		$handler = DBUtil::executeSQL($sql);
		
		//return $handler->GetMenu('comboTorneo','',false, false, 0, 'onchange="mostrarDiv(\'heroe\')"');
	}
	
}

function Trivial_userapi_getAllResul($args)
{

	//Construir la clausula WHERE
	if ($args['Jugador'] != ''){
		// Obtiene los resultados por un jugador determinado
		$queryargs[] = "`Jugador` = ".$args['Jugador'];
	}
	if ($args['Torneo'] != ''){
		// Obtiene los resultados por un torneo determinado
		$queryargs[] = "`Torneo` = ".$args['Torneo'];
	}
		
	if (count($queryargs) > 0) {
		$where = ' WHERE ' . implode(' AND ', $queryargs);
	}
	
	if (!isset($args['order']) || empty($args['order'])) {
		$args['order'] = 'Resultado DESC, Tiempo ASC'; 
	}

	$objArray = DBUtil::selectObjectArray ('trivial_resultados', $where, $args['order']);
		
	// Validamos que el elemento existe en la BD
	if ($objArray === false) {
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Do not exist results.', $dom));
	}
	
	//Return objects retrieved
	return $objArray;
	
}

function Trivial_userapi_getResul($args)
{
	$dom = ZLanguage::getModuleDomain('Trivial');
	//Check parameter
	if (!is_numeric($args['idResul'])) {
		return LogUtil::registerArgsError();
	}
	
	//Get the record
	$record = DBUtil::selectObjectByID('trivial_resultados', $args['idResul'], 'ID');
		
	if ($record === false) {
		return LogUtil::registerError(__('Do not exist results.', $dom));
	}else{
		return $record;
	}
	
}

/**
 * countQuestionByComp
 * Return the number total of questions by Competition
 *
 *@param idTorneo int with ID of the championship 
 *@returns int number total of question by the competition selected
 */
function Trivial_userapi_countQuestionByComp($args)
{
	$pntable = pnDBGetTables();
	$Trivialcolumn = $pntable['trivial_preg'];
	$queryargs = array();

	$where = '';
	if (isset($args['idTorneo'])) {
		$queryargs[] = "Heroes = ".$args['idTorneo'];
  }	
	if (count($queryargs) > 0) {
		$where = ' WHERE ' . implode(' AND ', $queryargs);
	}
	
	return DBUtil::selectObjectCount ('trivial_preg', $where, 'ID', false, '');
}


function Trivial_userapi_countitems($args)
{

	$pntable = pnDBGetTables();
	$Trivialcolumn = $pntable['trivial_clas'];
	$queryargs = array();

	$where = '';
	if (isset($args['torneo'])) {
		$queryargs[] = "idTorneo = ".$args['torneo'];
  }	
	if (count($queryargs) > 0) {
		$where = ' WHERE ' . implode(' AND ', $queryargs);
	}
	
	return DBUtil::selectObjectCount ('trivial_clas', $where, 'id_user', false, '');
		
}

// Funcion que devuelve el número de registros de una consulta a Trivial Preguntas
function Trivial_userapi_countitemsPreg($args)
{

	$pntable = pnDBGetTables();
	$Trivialcolumn = $pntable['trivial_preg'];
	$queryargs = array();

	$where = '';
	if (isset($args['Heroes'])) {
		$queryargs[] = "Heroes = ".$args['Heroes'];
	}
	if (isset($args['Def'])) {
		if ($args['Gen'] == 'Todos') {
			$queryargs[] = "Heroes LIKE 'H%'";
		}else{
			$queryargs[] = "Heroes LIKE '".$args['Gen']."'";
		}
	}

	if (count($queryargs) > 0) {
		$where = ' WHERE ' . implode(' AND ', $queryargs);
	}

	return DBUtil::selectObjectCount ('trivial_preg', $where, 'ID', false, '');
		
}

// Funcion que devuelve el número de registros de una consulta a Trivial Resultados
function Trivial_userapi_countitemsResul($args)
{

	$pntable = pnDBGetTables();
	$Trivialcolumn = $pntable['trivial_clas'];
	$queryargs = array();

	$where = '';
	if (isset($args['torneo'])) {
		$queryargs[] = "Torneo = ".$args['torneo'];
	}
	if (isset($args['Jugador'])) {
		$queryargs[] = "Jugador = ".$args['Jugador'];
	}
	if (count($queryargs) > 0) {
		$where = ' WHERE ' . implode(' AND ', $queryargs);
	}
	
	return DBUtil::selectObjectCount ('trivial_resultados', $where, 'ID', false, '');
		
}

//Funcion que devuelve si un jugador puede jugar a un determinado "Torneo"
function Trivial_userapi_checkPlay($args)
{
	
	//Mandatory fields
	if (!isset($args['uid']) || empty($args['uid']) ||
  		!isset($args['idTorneo']) || empty($args['idTorneo'])){
		return LogUtil::registerArgsError();
	}
  
	$torneo = DBUtil::selectObjectByID('trivial_competiciones', $args['idTorneo'], 'ID');
	// Validamos que el elemento existe en la BD
	if ($torneo === false) {
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Competition not found.', $dom));
	}
	
	// Chequeo extra (por si es mu listo y cambia la URL)
	//Si la competición no está activa, no dejar jugar
	if ($torneo['Activa'] == '' || empty($torneo['Activa'])){
		//return LogUtil::registerError(__('Error! Could not load any articles.', $dom));
		return false;
	}
	
	// Chequeo extra (por si no se ha desactivado el torneo)
	//Si no estamos entre las fechas del torneo, no se puede jugar
	$hoy = date('Y-m-d');
	if ($hoy > $torneo['F_Fin'] || $hoy < $torneo['F_Inicio']){
		return false;
	}
	
	//Si tiene marcado el campo "Unica", comprobamos que el usuario no ha jugado ya
	$numPart = 0;
	if ($torneo['Unica'] == 1){
		$numPart = pnModAPIFunc('Trivial', 'user', 'countitemsResul', 
								array(	'torneo'   => $torneo['ID'],
										'Jugador'  => $args['uid'],
										'numitems' => $itemsperpage));
		//$numPart = DBUtil::selectObjectCount ('trivial_resultados', $where, 'ID', false, '');
	}
	
	if ($numPart == 0){
		return true;
	}else{
		return false;
	}
  
}

/**
 * validate
 * Function to retrieve the number of answer corrects
 *
 *@param idTorneo int ID of competition
 *@param idPreg array Id's of questions
 *@param respUser array Answer(from user) to the questions
 *@returns int answer corrects
 */
function Trivial_userapi_validate($args)
{
	//Mandatory fields
	if (!isset($args['idTorneo']) || empty($args['idTorneo']) ||
  		!isset($args['idPreg']) || empty($args['idPreg']) ||
  		!isset($args['respUser']) || empty($args['respUser'])){
		return LogUtil::registerArgsError();
  }
  
	$idTorneo = $args['idTorneo'];
	$idPreg   = $args['idPreg'];
	$respUser = $args['respUser'];
	$Heroe    = $args['Heroe'];

	//Obtener las preguntas que se le han mostrado en el formulario
	$question = pnModAPIFunc('Trivial', 'user', 'getQuestions', 
							array(	'torneo' => $idTorneo,
									'Heroe'  => $Heroe,
									'idPreg' => $idPreg
							));
	//El sistema ordena las preguntas por ID, así que hay que reordenarlas
	for ($i=1;$i <= count($idPreg);$i++){
		$bq = $idPreg[$i];
		for ($j=0;$j < count($question) ;$j++){
			if ($bq == $question[$j]['ID']){
				$preguntas[] = $question[$j];
			}
		}
	}

	$respOK = pnModAPIFunc('Trivial', 'user', 'passTest', 
							array(	'question' => $preguntas,
									'respUser' => $respUser
							));

	return $respOK;
	
}

/**
 * getQuestions
 * Funcion que devuelve preguntas sobre el torneo seleccionado
 *
 *@param userdata array with user submitted data
 *@param contact array of contact information (single contact)
 *@param custom array of custom fields information
 *@param form int form id
 *@param format   string email format, either 'plain' or 'html'
 *@returns array with questions selected
 */
 function Trivial_userapi_getQuestions($args)
{
	
	//Mandatory fields
	/*if (!isset($args['uid']) || empty($args['uid']) ||
  		!isset($args['idTorneo'] || empty($args['idTorneo'])){
		return LogUtil::registerArgsError();
  }*/
  
	// Optional arguments.
	if (!isset($args['torneo']) || empty($args['torneo'])) {
		$args['torneo'] = 1;
	}
	if (!isset($args['startnum']) || empty($args['startnum'])) {
		$args['startnum'] = 1;
	}
	if (!isset($args['numitems']) || empty($args['numitems'])) {
		$args['numitems'] = -1;
	}
	if (!is_numeric($args['startnum']) ||
		!is_numeric($args['numitems'])) {
		return LogUtil::registerArgsError();
	}

	//Generar la clausula WHERE para obtener los datos solicitados
	$where = '';
	if ($args['torneo'] != 1){
		if (isset($args['idPreg'])) {
			$queryargs[] = ' `ID` IN (' . implode(' , ', $args['idPreg']) . ')';
		}
		if (isset($args['torneo'])) {
			$queryargs[] = "`Heroes` = '".$args['torneo']."'";
		}	
		/*if (count($queryargs) > 0) {
			$where = ' WHERE ' . implode(' AND ', $queryargs);
		}*/
	}else{//Torneo general
		if (!isset($args['Heroe']) || empty($args['Heroe'])) {
			return LogUtil::registerArgsError();
		}
		if ($args['Heroe'] == 'Todos'){
			$queryargs[] = "`Heroes` LIKE 'Heroes%'";
			if (isset($args['idPreg'])) {
				$queryargs[] = ' `ID` IN (' . implode(' , ', $args['idPreg']) . ')';
			}
		}else{
			$queryargs[] = "`Heroes` = '".$args['Heroe']."'";
			if (isset($args['idPreg'])) {
				$queryargs[] = ' `ID` IN (' . implode(' , ', $args['idPreg']) . ')';
			}
		}
		if ($args['rand'] == 'X'){
			$order = "RAND()";
		}
	}
	if (count($queryargs) > 0) {
		$where = ' WHERE ' . implode(' AND ', $queryargs);
	}

	// Extrae el array de elementos de la BD (paginado)
	$objArray = DBUtil::selectObjectArray ('trivial_preg', $where, $order, $args['startnum']-1, $args['numitems']);

	// Validamos que el elemento existe en la BD
	if ($objArray === false) {
		$dom = ZLanguage::getModuleDomain('Trivial');
		return LogUtil::registerError(__('Error! Do not exist questions.', $dom));
	}

	// Retorna el objeto
	return $objArray;
	
}

/**
 * passTest
 * Check the form with the responses of the user
 *
 *@param question array with the questions of the BD
 *@param respUser array with the answer of the user
 *@returns int with number of correct answer
 */
function Trivial_userapi_passTest($args)
{
	//Mandatory fields
	if (!isset($args['question']) || empty($args['question']) ||
  		!isset($args['respUser']) || empty($args['respUser'])){
		return LogUtil::registerArgsError();
	}
  
	//Extract from Array
	$respUser = $args['respUser'];
	$question = $args['question'];

	//Initialite
	$resOK = 0;
  
	//Verify answers
	for ($i = 0; $i<count($respUser);$i++){
		if ($respUser[$i+1] == $question[$i][RespCor]){
			$resOK++;
		}
	}
  
	return $resOK;
  
}

/**
 * insertResults
 * Insert a record in "resultados" table (This is only for competitions)
 *
 *@param Torneo int ID of the competition
 *@param Resultado int Number of correct answers
 *@returns int 
 */
function Trivial_userapi_insertResults($args)
{

	// Argument check
	if (!isset($args['Torneo']) || !isset($args['Resultado'])) {
		return LogUtil::registerArgsError();
	}
  
	$args['Jugador'] = pnUserGetVar('uid');
    
	return DBUtil::insertObject($args, 'trivial_resultados', 'ID');
    
}

function Trivial_userapi_modifyResults($args)
{

	// Argument check
	if (!isset($args['id']) ) {
		return LogUtil::registerArgsError();
	}
	$ID = $args['id'];
	extract ($args);
	if ($Resultado != ''){
		$cadena.= "`Resultado` = '".$Resultado."'";
		$mas = ",";
	}else{
		$cadena.= $mas."`Resultado` = NULL";
		$mas = ",";
	}
	if ($Tiempo != ''){
		$cadena.= $mas."`Tiempo` = '".$Tiempo."'";
		$mas = ",";
	}else{
		$cadena.= $mas."`Tiempo` = NULL";
		$mas = ",";
	}
	
	$pntable = pnDBGetTables();
	$table = $pntable['trivial_resultados'];
	$sql = "UPDATE $table SET ".$cadena." WHERE `ID` = ".$ID;
	//$sql = "UPDATE `z_trivial_resultados` SET ".$cadena." WHERE `ID` = ".$ID;

	return DBUtil::executeSQL($sql);
    
}

/**
 * insertClas
 * Insert a record in "clas" table (This is only for "General" competition)
 *
 *@param Heroe char name of the M&M game
 *@param Resultado int Number of correct answers
 *@returns int 
 */
function Trivial_userapi_insertClas($args)
{
	
	// Argument check
	if (!isset($args['Heroe']) || !isset($args['Resultado'])) {
		return LogUtil::registerArgsError();
	}
  
	$record['id_user'] = pnUserGetVar('uid');
	$record['Usuario'] = pnUserGetVar('uname');
  
	//Get the information of the clasification
	$rcd = pnModAPIFunc('Trivial', 'user', 'getClasification', array('idUser' => $record['id_user']));

	if ($rcd){
		$found = true;
	}else{
		$found = false;
	}
	//Se supone que si no encuentra, todos los valores serán 0 (para acumular)
  
	switch($args['Heroe']){
		case 'Heroes I':{
			$record['h1'] = $rcd['h1'] + $args['Resultado'];
			break;
		}
		case 'Heroes II':{
			$record['h2'] = $rcd['h2'] + $args['Resultado'];
			break;
		}
		case 'Heroes III':{
			$record['h3'] = $rcd['h3'] + $args['Resultado'];
			break;
		}
		case 'Heroes IV':{
			$record['h4'] = $rcd['h4'] + $args['Resultado'];
			break;
		}
		case 'Heroes V':{
			$record['h5'] = $rcd['h5'] + $args['Resultado'];
			break;
		}
		case 'Heroes VI':{
			$record['h6'] = $rcd['h6'] + $args['Resultado'];
			break;
		}
		case 'Dark Messiah':{
			$record['DM'] = $rcd['DM'] + $args['Resultado'];
			break;
		}
		case 'Todos':{
			$record['Todos'] = $rcd['Todos'] + $args['Resultado'];
			break;
		}
	}
	$record['Total'] = $rcd['Total'] + $args['Resultado'];
	$record['NTest'] = $rcd['NTest'] + 1;
	$record['Media'] = round($record['Total'] / $record['NTest'], 2);
	
	if ($found == true){
		return DBUtil::updateObject($record, 'trivial_clas', '', 'id_user');
		
	}else{
		return DBUtil::insertObject($record, 'trivial_clas');		
	} 
    
}

/**
 * getQuestion
 * Function to return the information of a question
 *
 *@param idPreg ID of the question int
 *@returns record with the information of the question
 */
function Trivial_userapi_getQuestion($args)
{
	
	//Check optional parameters
	if (!is_numeric($args['idPreg'])) {
		return LogUtil::registerArgsError();
	}
	
	if (isset($args['idPreg'])){
		//Get the record of the competition
		$record = DBUtil::selectObjectByID('trivial_preg', $args['idPreg'], 'ID');
	}else{
		//Clause where *here*
	}
	
	return $record;
	
}

function Trivial_userapi_getTime($args)
{
	// Argument check
	if (!isset($args['Segundos'])) {
		return LogUtil::registerArgsError();
	}
	//Check optional parameters
	if (!is_numeric($args['Segundos'])) {
		return LogUtil::registerArgsError();
	}
	$segundos = $args['Segundos'];
	$minutos=$segundos/60;
	$horas=floor($minutos/60);
	$minutos2=$minutos%60;
	$segundos_2=$segundos%60%60%60;
	if($minutos2<10)$minutos2='0'.$minutos2;
	if($segundos_2<10)$segundos_2='0'.$segundos_2;

	if($segundos<60){ /* segundos */
		$resultado= '00:00:'.$segundos_2;//.round($segundos);
	}elseif($segundos>60 && $segundos<3600){/* minutos */
		$resultado= '00:'.$minutos2.':'.$segundos_2;
	}else{/* horas */
		$resultado= $horas.':'.$minutos2.':'.$segundos_2;
	}
	return $resultado;

}