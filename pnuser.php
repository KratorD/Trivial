<?php
/**
 * Zikula Application Framework
 *
 * @copyright (c) 2001, Zikula Development Team
 * @link http://www.zikula.org
 * @version $Id: pnuser.php 24342 2008-06-06 12:03:14Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function Trivial_user_main()
{
	return Trivial_user_show();
}

function Trivial_user_show($args)
{
	//Permisos generales
	if (!SecurityUtil::checkPermission('Trivial::', '::', ACCESS_OVERVIEW)) {
		return LogUtil::registerPermissionError();
	}
  
	//Obtener los parametros
	$page  = (int)FormUtil::getPassedValue('page', isset($args['page']) ? $args['page'] : 1, 'GET');
	$order = FormUtil::getPassedValue('order', isset($args['order']) ? $args['order'] : '', 'GET');
	
	// Obtener todas las variables del modulo
	$modvars = pnModGetVar('Trivial');
	
	$itemsperpage = $modvars['itemsperpage'];
	
	// Primer elemento a obtener de la paginacion
	$startnum = (($page - 1) * $itemsperpage) + 1;

	// Obtenemos la clasificación
	$lista = pnModAPIFunc('Trivial', 'user', 'getAll', 
						array(	'startnum' => $startnum,
                                'numitems' => $itemsperpage,
								'order'    => $order
							 ));
															 
	// Obtenemos los torneos a los que se pueden jugar (activos y en fecha)
	$torneos = pnModAPIFunc('Trivial', 'user', 'getAllTorneos',
							array(	'combo' 	 => 'X',
									'Activa' 	 => 'X',
									'porFecha' => 'X'));

	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');

	//Enviarlas a la plantilla
	$render->assign('listado', $lista);
	$render->assign($modvars);
	$render->assign('startnum', $startnum);
	$render->assign('torneo', $torneos);

	// Asignar los valores al sistema de paginación
	$render->assign('pager', array(	'numitems' => pnModAPIFunc('Trivial', 'user', 'countitems'),
                                    'itemsperpage' => $itemsperpage,
									'torneo' => '1'));

	return $render->fetch('Trivial_user_show.htm');
  
}

/**
 * Play
 * Formulario que muestra las preguntas del juego seleccionado
 *
 *@param userdata array with user submitted data
 *@param contact array of contact information (single contact)
 *@param custom array of custom fields information
 *@param form int form id
 *@param format   string email format, either 'plain' or 'html'
 *@returns boolean
 */
function Trivial_user_play($args)
{
	//Obtener los parametros
	$page  = (int)FormUtil::getPassedValue('page', isset($args['page']) ? $args['page'] : 1, 'GET');
	$idTorneo = (int)FormUtil::getPassedValue('idTorneo', isset($args['idTorneo']) , 'POST');
	
	$respOK = (int)FormUtil::getPassedValue('respOK', isset($args['respOK']) , 'POST');
	
	//Lenguaje
	$dom = ZLanguage::getModuleDomain('Trivial');
	
	//Hora de comienzo para torneos
	if ($page == 1){
		//Obtener los datos de la competición
		$comp = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', 
							array('idTorneo' => $idTorneo));
		if ($comp['Unica'] == '1'){
			$usuario = pnUserGetVar('uid');
			//Comprobar si ya ha tiene un resultado
			$lresult = pnModAPIFunc('Trivial', 'user', 'getAllResul', 
										array(	'Torneo'   => $idTorneo,
												'Jugador'  => $usuario));
			if ($lresult[0]['ID'] != ''){
				//Haciendo trampas
				// Construimos y devolvemos la Vista
				$render = & pnRender::getInstance('Trivial');
				$tipo_error = 1;  //Volviendo atras en el torneo
				$render->assign('tipo_error', $tipo_error);
				return $render->fetch('Trivial_user_error.htm');
			}else{
				$hora_inicio = time();
			}
		}else{
			$hora_inicio = time();
		}
	}else{
		$hora_inicio = $_POST['hora_inicio'];
	}
	
	// Si proviene de la pantalla inicial de selección de juego
	if ($idTorneo == ''){
		$idTorneo = FormUtil::getPassedValue('comboTorneo', isset($args['comboTorneo']) ? $args['comboTorneo'] : '1', 'POST');
		$cmbHeroe = FormUtil::getPassedValue('cmbHeroe', isset($args['cmbHeroe']), 'POST');
		
		// Chekeo. ¿Puede el usuario jugar a este torneo?
		$uid = pnUserGetVar('uid');
	  
		$canplay = pnModAPIFunc('Trivial', 'user', 'checkPlay', 
								array('uid' => $uid,
									  'idTorneo' => $idTorneo
								));
  
		if ($canplay != true){
			// Construimos y devolvemos la Vista
			$render = & pnRender::getInstance('Trivial');
			$tipo_error = 2;  //No cumple requisitos para jugar
			$render->assign('tipo_error', $tipo_error);
			return $render->fetch('Trivial_user_error.htm');
		}
		
		//Si es un torneo, avisos
		if ($idTorneo > 1){
			//Obtener sus datos
			$comp = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', 
							array('idTorneo' => $idTorneo));
			if ($comp['Unica'] != ''){
				//Iniciamos la instancia
				$render = & pnRender::getInstance('Trivial');
				//Enviar datos a plantillas
				$render->assign('idTorneo', $idTorneo);
				
				return $render->fetch('Trivial_user_avisos.htm');
			}
		}
	
	}else{
		if ($_POST['txtAviso'] == "X"){
			//Registrar que a va a jugar
			//Fecha para el registro
			$fecha = date('Y-m-d');
			//Almacenar el resultado
			$rcd = pnModAPIFunc('Trivial', 'user', 'insertResults', 
								array(	'Torneo' 	=> $idTorneo,
										'Resultado' => '0',
										'Fecha'		=> $fecha));
			
		}else{
			//Ya ha realizado al menos la primera página de preguntas
			$idPreg 	= $_POST['idPreg'];
			$respUser = $_POST['group'];
			$Heroe    = $_POST['Heroe'];

			$numrespOK = pnModAPIFunc('Trivial', 'user', 'validate', 
										array(	'idTorneo' => $idTorneo,
												'idPreg'	 	 => $idPreg,
												'respUser'   => $respUser,
												'Heroe'      => $Heroe));
			
			$respOK = $respOK + $numrespOK;
			$numMaxPag = $_POST['numMaxPag'];
			$numitems = $_POST['numitems'];
			
			// Ha finalizado las preguntas		
			if ($page > $numMaxPag){
				if ($idTorneo == 1){
					//Patch Torneo General - Nº de preguntas
					$numitems = count($idPreg);
					//Almacenar el resultado
					$rcd = pnModAPIFunc('Trivial', 'user', 'insertClas', 
										array(	'Heroe' 	=> $Heroe,
												'Resultado' => $respOK));
					
					if (!$rcd){
						return LogUtil::registerError(__('Error trying insert in the DB!', $dom));
					}
					
				}else{
					//Calcular el tiempo que ha tardado en acabar de responder
					$hora_final = time();
					$segundos = $hora_final - $_POST['hora_inicio'];
					$tiempo = pnModAPIFunc('Trivial', 'user', 'getTime', 
								array('Segundos' => $segundos));
					//Fecha para el registro
					$fecha = date('Y-m-d');
					//Almacenar el resultado
					$usuario = pnUserGetVar('uid');
					//Get information about the competition
					$comp = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', 
									array('idTorneo' => $idTorneo));

					if ($comp['Unica'] == '1'){
						//Es una competicion marcada como Unica. Estas se insertan al aceptar las condiciones,
						//por lo que debe de existir ya un registro en la BD. Actualizar!
						$lresult = pnModAPIFunc('Trivial', 'user', 'getAllResul', 
										array(	'Torneo'   => $idTorneo,
												'Jugador'  => $usuario));

						if ($lresult != ''){
							$resID = $lresult[0]['ID'];
							$rcd = pnModAPIFunc('Trivial', 'user', 'modifyResults', 
													array(	'id' 		=> $resID,
															'Resultado' => $respOK,
															'Tiempo'	=> $tiempo,
															'Fecha'		=> $fecha));

							if (!$rcd){
								return LogUtil::registerError(__('Error trying modify in the DB!', $dom));
							}
						}
					}else{
						//Esta no es una competicion marcada como única, así que puede insertar tantos registros
						//como se quiera
						$rcd = pnModAPIFunc('Trivial', 'user', 'insertResults', 
												array(	'Torneo' 	=> $idTorneo,
														'Resultado' => $respOK,
														'Tiempo'	=> $tiempo,
														'Fecha'		=> $fecha));

						if (!$rcd){
							return LogUtil::registerError(__('Error trying insert in the DB!', $dom));
						}
					}
				}
				
				//Presentar el resultado por pantalla
				// Construimos y devolvemos la Vista
				$render = & pnRender::getInstance('Trivial');
			
				//Enviarlas a la plantilla
				$render->assign('respOK', $respOK);
				$render->assign('numitems', $numitems);
				
				//$pnRender->assign('idTorneo', $idTorneo);
				$render->assign($modvars);
							
				return $render->fetch('Trivial_user_results.htm');
			}
		}
	}

	// Obtener todas las variables del modulo
	$modvars = pnModGetVar('Trivial');
	
	//Get information about the competition
	$comp = pnModAPIFunc('Trivial', 'user', 'getCompeticiones', 
							array('idTorneo' => $idTorneo));
														
	$itemsperpage = $comp['N_Preguntas'];
	
	// Primer elemento a obtener de la paginacion
	$startnum = (($page - 1) * $itemsperpage) + 1;
	
	//Obtener las preguntas (aleatorias) sobre el torneo elegido
	$question = pnModAPIFunc('Trivial', 'user', 'getQuestions', 
							array(	'startnum' => $startnum,
                                	'numitems' => $itemsperpage,
                                	'torneo'   => $idTorneo,
                                	'Heroe'    => $cmbHeroe,
									'rand'	   => 'X'
							));
	
	//Obtener el número de preguntas
	$numitems = pnModAPIFunc('Trivial', 'user', 'countQuestionByComp', 
  								array('idTorneo' => $idTorneo));
	
	//Numero de paginas máximas
	$numMaxPag = ceil($numitems / $itemsperpage);
	
	// Construimos y devolvemos la Vista
	$render = & pnRender::getInstance('Trivial');

	//Enviarlas a la plantilla
	$render->assign('comp', $comp);
	$render->assign('questions', $question);
	$render->assign('idTorneo', $idTorneo);
	$render->assign('Heroe', $cmbHeroe);
	$render->assign($modvars);
	$render->assign('startnum', $startnum);
	$render->assign('itemsperpage', $itemsperpage);
	$render->assign('numMaxPag', $numMaxPag);
	$render->assign('respOK', $respOK);
	$render->assign('hora_inicio', $hora_inicio);
	$render->assign('numitems', $numitems);

	//Si la competición es la general, no queremos paginación alguna.
	//if ($idTorneo > 1){
		// Asignar los valores al sistema de paginación
  	$render->assign('pager', array('numitems' 		=> $numitems,
      	                             'itemsperpage' => $itemsperpage,
      	                             'page' 		=> $page + 1));
  //}

	return $render->fetch('Trivial_user_play.htm');
  
}