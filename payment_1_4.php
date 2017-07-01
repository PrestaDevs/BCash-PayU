<?php
/*
*  @author Buscapé Company <integracao@bcash.com.br>
*  @version  Release: 1.0
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

/**
 * @deprecated 1.5.0 This file is deprecated, use moduleFrontController instead
 */

/* SSL Management */
$useSSL = true;

ini_set('display_errors', 1);
//ini_set('log_errors', 1);
//ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);
                
include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/bcash.php');

if (!$cookie->isLogged())
    Tools::redirect('authentication.php?back=order.php');
    $bcash = new Bcash();
echo $bcash->execPayment($cart);

include_once(dirname(__FILE__).'/../../footer.php');