<?php
/**
 * Registro de Partidas Module for Zikula
 *
 * @copyright (c) 2008, Mark West
 * @link http://www.heroesmightmagic.es
 * @version $Id: pntables.php 19262 2006-06-12 14:45:18Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
*/

/**
 * Populate pntables array
 *
 * @author Krator
 * @return array pntables array
 */
function Trivial_pntables()
{
    $pntable = array();

    // Tabla que contiene todas las preguntas del Trivial
    $pntable['trivial_preg'] = DBUtil::getLimitedTablename('trivial_preg');
    $pntable['trivial_preg_column'] = array('ID'   			=> 'ID',
											'Pregunta'		=> 'Pregunta',
											'Resp1' 		=> 'Resp1',
											'Resp2'			=> 'Resp2',
											'Resp3' 		=> 'Resp3',
											'Resp4' 		=> 'Resp4',
											'RespCor'		=> 'RespCor',
											'Heroes'	   	=> 'Heroes'
										   );
    $pntable['trivial_preg_column_def'] = array('ID'   		=> "I NOTNULL AUTO PRIMARY",
												'Pregunta'  => "C(255) NOTNULL",
												'Resp1'		=> "C(255) NOTNULL",
												'Resp2' 	=> "C(255) NOTNULL",
												'Resp3' 	=> "C(255) NOTNULL",
												'Resp4' 	=> "C(255) NOTNULL",
												'RespCor' 	=> "I NOTNULL",
												'Heroes' 	=> "C(15) NULL"
											   );
    $pntable['trivial_preg_column_idx'] = array('ID' => 'ID');
 
	// Tabla que contiene los resultados generales para "no competiciones"
	$pntable['trivial_clas'] = DBUtil::getLimitedTablename('trivial_clas');
    $pntable['trivial_clas_column'] = array('id_user'   => 'id_user',
											'Usuario'	=> 'Usuario',
											'h1' 		=> 'h1',
											'h2'		=> 'h2',
											'h3' 		=> 'h3',
											'h4' 		=> 'h4',
											'h5'		=> 'h5',
											'h6'	   	=> 'h6',
											'DM'	   	=> 'DM',
											'Todos'	   	=> 'Todos',
											'Total'	   	=> 'Total',
											'NTest'	   	=> 'NTest',
											'Media'	   	=> 'Media'
										   );
    $pntable['trivial_clas_column_def'] = array('id_user' 	=> "I NOTNULL PRIMARY",
												'Usuario'  	=> "C(25) NOTNULL",
												'h1'		=> "I NOTNULL",
												'h2' 		=> "I NOTNULL",
												'h3' 		=> "I NOTNULL",
												'h4' 		=> "I NOTNULL",
												'h5' 		=> "I NOTNULL",
												'h6' 		=> "I NULL",
												'DM' 		=> "I NULL",
												'Todos' 	=> "I NULL",
												'Total' 	=> "I8 NULL",
												'NTest' 	=> "I NULL",
												'Media' 	=> "F NULL"
											   );
    $pntable['trivial_clas_column_idx'] = array('id_user' => 'id_user');
	
    // Tabla que contiene las diferentes competiciones
	$pntable['trivial_competiciones'] = DBUtil::getLimitedTablename('trivial_competiciones');
    $pntable['trivial_competiciones_column'] = array('ID'   			=> 'ID',
														'Nombre'		=> 'Nombre',
														'F_Inicio' 		=> 'F_Inicio',
														'F_Fin'			=> 'F_Fin',
														'Activa' 		=> 'Activa',
														'N_Preguntas' 	=> 'N_Preguntas',
														'Unica'	   		=> 'Unica'
													);
    $pntable['trivial_competiciones_column_def'] = array(	'ID'   			=> "I NOTNULL AUTO PRIMARY",
															'Nombre'  		=> "C(50) NOTNULL",
															'F_Inicio'		=> "D NULL",
															'F_Fin' 		=> "D NULL",
															'Activa' 		=> "L NOTNULL",
															'N_Preguntas' 	=> "I NULL",
															'Unica' 		=> "L NULL"
											   );
    $pntable['trivial_competiciones_column_idx'] = array('ID' => 'ID');
	
	// Tabla que contiene los resultados de las "competiciones"
	$pntable['trivial_resultados'] = DBUtil::getLimitedTablename('trivial_resultados');
    $pntable['trivial_resultados_column'] = array(	'ID'   			=> 'ID',
													'Jugador'		=> 'Jugador',
													'Torneo' 		=> 'Torneo',
													'Resultado'		=> 'Resultado',
													'Tiempo'		=> 'Tiempo',
													'Fecha'			=> 'Fecha'
												 );
    $pntable['trivial_resultados_column_def'] = array(	'ID'   			=> "I NOTNULL AUTO PRIMARY",
														'Jugador'  		=> "I NOTNULL",
														'Torneo'		=> "I NOTNULL",
														'Resultado' 	=> "I NOTNULL",
														'Tiempo'		=> "T NULL",
														'Fecha'			=> "D NULL"
											   );
    $pntable['trivial_resultados_column_idx'] = array('ID' => 'ID');
		
	return $pntable;
}
