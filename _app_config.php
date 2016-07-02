<?php
/**
 * @package BEATRIZ
 *
 * APPLICATION-WIDE CONFIGURATION SETTINGS
 *
 * This file contains application-wide configuration settings.  The settings
 * here will be the same regardless of the machine on which the app is running.
 *
 * This configuration should be added to version control.
 *
 * No settings should be added to this file that would need to be changed
 * on a per-machine basic (ie local, staging or production).  Any
 * machine-specific settings should be added to _machine_config.php
 */

/**
 * APPLICATION ROOT DIRECTORY
 * If the application doesn't detect this correctly then it can be set explicitly
 */
if (!GlobalConfig::$APP_ROOT) GlobalConfig::$APP_ROOT = realpath("./");

/**
 * check is needed to ensure asp_tags is not enabled
 */
if (ini_get('asp_tags')) 
	die('<h3>Server Configuration Problem: asp_tags is enabled, but is not compatible with Savant.</h3>'
	. '<p>You can disable asp_tags in .htaccess, php.ini or generate your app with another template engine such as Smarty.</p>');

/**
 * INCLUDE PATH
 * Adjust the include path as necessary so PHP can locate required libraries
 */
set_include_path(
		GlobalConfig::$APP_ROOT . '/libs/' . PATH_SEPARATOR .
		GlobalConfig::$APP_ROOT . '/../phreeze/libs' . PATH_SEPARATOR .
		GlobalConfig::$APP_ROOT . '/vendor/phreeze/phreeze/libs/' . PATH_SEPARATOR .
		get_include_path()
);

/**
 * COMPOSER AUTOLOADER
 * Uncomment if Composer is being used to manage dependencies
 */
// $loader = require 'vendor/autoload.php';
// $loader->setUseIncludePath(true);

/**
 * SESSION CLASSES
 * Any classes that will be stored in the session can be added here
 * and will be pre-loaded on every page
 */
require_once "App/ExampleUser.php";

/**
 * RENDER ENGINE
 * You can use any template system that implements
 * IRenderEngine for the view layer.  Phreeze provides pre-built
 * implementations for Smarty, Savant and plain PHP.
 */
require_once 'verysimple/Phreeze/SavantRenderEngine.php';
GlobalConfig::$TEMPLATE_ENGINE = 'SavantRenderEngine';
GlobalConfig::$TEMPLATE_PATH = GlobalConfig::$APP_ROOT . '/templates/';

/**
 * ROUTE MAP
 * The route map connects URLs to Controller+Method and additionally maps the
 * wildcards to a named parameter so that they are accessible inside the
 * Controller without having to parse the URL for parameters such as IDs
 */
GlobalConfig::$ROUTE_MAP = array(

	// default controller when no route specified
	'GET:' => array('route' => 'Default.Home'),
        
    
	'GET:check' => array('route' => 'Check.Vistoria'),
        'POST:generate' => array('route' => 'CheckResponder.Responder'),
	'POST:analyze' => array('route' => 'Check.Responder'),
		
	// example authentication routes
	'GET:loginform' => array('route' => 'SecureExample.LoginForm'),
	'POST:login' => array('route' => 'SecureExample.Login'),
	'GET:secureuser' => array('route' => 'SecureExample.UserPage'),
	'GET:secureadmin' => array('route' => 'SecureExample.AdminPage'),
	'GET:logout' => array('route' => 'SecureExample.Logout'),
		
	// Checklistvistoria
	'GET:checklistvistorias' => array('route' => 'Checklistvistoria.ListView'),
	'GET:checklistvistoria/(:num)' => array('route' => 'Checklistvistoria.SingleView', 'params' => array('id' => 1)),
	'GET:api/checklistvistorias' => array('route' => 'Checklistvistoria.Query'),
	'POST:api/checklistvistoria' => array('route' => 'Checklistvistoria.Create'),
	'GET:api/checklistvistoria/(:num)' => array('route' => 'Checklistvistoria.Read', 'params' => array('id' => 2)),
	'PUT:api/checklistvistoria/(:num)' => array('route' => 'Checklistvistoria.Update', 'params' => array('id' => 2)),
	'DELETE:api/checklistvistoria/(:num)' => array('route' => 'Checklistvistoria.Delete', 'params' => array('id' => 2)),
		
	// Engenheiro
	'GET:engenheiros' => array('route' => 'Engenheiro.ListView'),
	'GET:engenheiro/(:num)' => array('route' => 'Engenheiro.SingleView', 'params' => array('id' => 1)),
	'GET:api/engenheiros' => array('route' => 'Engenheiro.Query'),
	'POST:api/engenheiro' => array('route' => 'Engenheiro.Create'),
	'GET:api/engenheiro/(:num)' => array('route' => 'Engenheiro.Read', 'params' => array('id' => 2)),
	'PUT:api/engenheiro/(:num)' => array('route' => 'Engenheiro.Update', 'params' => array('id' => 2)),
	'DELETE:api/engenheiro/(:num)' => array('route' => 'Engenheiro.Delete', 'params' => array('id' => 2)),
		
	// Grupoquestao
	'GET:grupoquestaos' => array('route' => 'Grupoquestao.ListView'),
	'GET:grupoquestao/(:num)' => array('route' => 'Grupoquestao.SingleView', 'params' => array('id' => 1)),
	'GET:api/grupoquestaos' => array('route' => 'Grupoquestao.Query'),
	'POST:api/grupoquestao' => array('route' => 'Grupoquestao.Create'),
	'GET:api/grupoquestao/(:num)' => array('route' => 'Grupoquestao.Read', 'params' => array('id' => 2)),
	'PUT:api/grupoquestao/(:num)' => array('route' => 'Grupoquestao.Update', 'params' => array('id' => 2)),
	'DELETE:api/grupoquestao/(:num)' => array('route' => 'Grupoquestao.Delete', 'params' => array('id' => 2)),
		
	// Obra
	'GET:obras' => array('route' => 'Obra.ListView'),
	'GET:obra/(:num)' => array('route' => 'Obra.SingleView', 'params' => array('id' => 1)),
	'GET:api/obras' => array('route' => 'Obra.Query'),
	'POST:api/obra' => array('route' => 'Obra.Create'),
	'GET:api/obra/(:num)' => array('route' => 'Obra.Read', 'params' => array('id' => 2)),
	'PUT:api/obra/(:num)' => array('route' => 'Obra.Update', 'params' => array('id' => 2)),
	'DELETE:api/obra/(:num)' => array('route' => 'Obra.Delete', 'params' => array('id' => 2)),
		
	// Questao
	'GET:questaos' => array('route' => 'Questao.ListView'),
	'GET:questao/(:num)' => array('route' => 'Questao.SingleView', 'params' => array('id' => 1)),
	'GET:api/questaos' => array('route' => 'Questao.Query'),
	'POST:api/questao' => array('route' => 'Questao.Create'),
	'GET:api/questao/(:num)' => array('route' => 'Questao.Read', 'params' => array('id' => 2)),
	'PUT:api/questao/(:num)' => array('route' => 'Questao.Update', 'params' => array('id' => 2)),
	'DELETE:api/questao/(:num)' => array('route' => 'Questao.Delete', 'params' => array('id' => 2)),
		
	// Vistoria
	'GET:vistorias' => array('route' => 'Vistoria.ListView'),
	'GET:vistoria/(:num)' => array('route' => 'Vistoria.SingleView', 'params' => array('id' => 1)),
	'GET:api/vistorias' => array('route' => 'Vistoria.Query'),
	'POST:api/vistoria' => array('route' => 'Vistoria.Create'),
	'GET:api/vistoria/(:num)' => array('route' => 'Vistoria.Read', 'params' => array('id' => 2)),
	'PUT:api/vistoria/(:num)' => array('route' => 'Vistoria.Update', 'params' => array('id' => 2)),
	'DELETE:api/vistoria/(:num)' => array('route' => 'Vistoria.Delete', 'params' => array('id' => 2)),
	// CheckList
	'GET:checklists' => array('route' => 'CheckList.ListView'),
	'GET:checklist/(:num)' => array('route' => 'CheckList.SingleView', 'params' => array('id' => 1)),
	'GET:api/checklists' => array('route' => 'CheckList.Query'),
	'POST:api/checklist' => array('route' => 'CheckList.Create'),
	'GET:api/checklist/(:num)' => array('route' => 'CheckList.Read', 'params' => array('id' => 2)),
	'PUT:api/checklist/(:num)' => array('route' => 'CheckList.Update', 'params' => array('id' => 2)),
	'DELETE:api/checklist/(:num)' => array('route' => 'CheckList.Delete', 'params' => array('id' => 2)),

	// catch any broken API urls
	'GET:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'PUT:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'POST:api/(:any)' => array('route' => 'Default.ErrorApi404'),
	'DELETE:api/(:any)' => array('route' => 'Default.ErrorApi404')
);

/**
 * FETCHING STRATEGY
 * You may uncomment any of the lines below to specify always eager fetching.
 * Alternatively, you can copy/paste to a specific page for one-time eager fetching
 * If you paste into a controller method, replace $G_PHREEZER with $this->Phreezer
 */
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Checklistvistoria","fk_checklistvistoria_questao",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Checklistvistoria","fk_checklistvistoria_vistoria",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Questao","fk_questao_grupo",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Vistoria","fk_vistoria_engenheiro_idx",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
// $GlobalConfig->GetInstance()->GetPhreezer()->SetLoadType("Vistoria","fk_vistoria_obra_idx",KM_LOAD_EAGER); // KM_LOAD_INNER | KM_LOAD_EAGER | KM_LOAD_LAZY
?>