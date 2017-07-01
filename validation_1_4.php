<?php
/*
*  @author Buscapé Company <integracao@bcash.com.br>
*  @version  Release: 1.0
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

/**
 * @deprecated 1.5.0 This file is deprecated, use moduleFrontController instead
 */


         

include(dirname(__FILE__).'/../../config/config.inc.php');
include(dirname(__FILE__).'/../../header.php');
include(dirname(__FILE__).'/bcash.php');

$bcash = new Bcash();

if ($cart->id_customer == 0 OR $cart->id_address_delivery == 0 OR $cart->id_address_invoice == 0 OR !$bcash->active)
	Tools::redirect('index.php?controller=order&step=1');

// Check that this payment option is still available in case the customer changed his address just before the end of the checkout process
$authorized = false;
//var_dump($authorized);
//var_dump(Module::getModulesInstalled());
foreach (Module::getModulesInstalled() as $module){
	if ($module['name'] == 'bcash')
	{
		$authorized = $module['active'];
	}
}

        
        
if (!$authorized)
	die($bcash->l('This payment method is not available.', 'validation'));

$customer = new Customer((int)$cart->id_customer);

if (!Validate::isLoadedObject($customer))
	Tools::redirect('index.php?controller=order&step=1');

$currency = new Currency(intval(isset($_POST['currency_payement']) ? $_POST['currency_payement'] : $cookie->id_currency));

$total = (float)($cart->getOrderTotal());

$bcash->validateOrder($cart->id, Configuration::get('BCASH_STATUS_1'), $total, $bcash->displayName, NULL, array(), (int)$currency->id, false, $customer->secure_key);

$order = new Order($bcash->currentOrder);

Tools::redirect('order-confirmation.php?&id_cart='.$cart->id.'&id_module='.$bcash->id.'&id_order='.$bcash->currentOrder.'&key='.$customer->secure_key);
