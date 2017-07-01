<?php
/*
*  @author Buscapé Company <integracao@bcash.com.br>
*  @version  Release: 1.0
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

if (!defined('_PS_VERSION_'))
	exit;

class Bcash extends PaymentModule
{
	private $_html = '';
	private $_postErrors = array();

	public $email_bcash;
	public $cod_loja_bcash;
	public $token_bcash;
	public $return_bcash;
	public $checkout_bcash;
	public $prefix_bcash;
	public $status_bcash_1;
	public $status_bcash_3;
	public $status_bcash_4;
	public $status_bcash_5;
	public $status_bcash_6;
	public $status_bcash_7;
	public $status_bcash_8;
	public $extra_mail_vars;
        
	public function __construct()
	{
		$this->name = 'bcash';
		$this->tab = 'payments_gateways';
		$this->version = '1.0';
		$this->author = 'Buscapé Company';
		
		$this->currencies = true;
		$this->currencies_mode = 'checkbox';

		$config = Configuration::getMultiple(array('BCASH_EMAIL', 'BCASH_COD_LOJA', 'BCASH_TOKEN','BCASH_RETURN','BCASH_CHECKOUT','BCASH_PREFIX','BCASH_STATUS_1','BCASH_STATUS_3','BCASH_STATUS_4','BCASH_STATUS_5','BCASH_STATUS_6','BCASH_STATUS_7','BCASH_STATUS_8'));
                
		if (isset($config['BCASH_EMAIL']))
			$this->email_bcash = $config['BCASH_EMAIL'];
		if (isset($config['BCASH_COD_LOJA']))
			$this->cod_loja_bcash = $config['BCASH_COD_LOJA'];
		if (isset($config['BCASH_TOKEN']))
			$this->token_bcash = $config['BCASH_TOKEN'];
		if (isset($config['BCASH_RETURN']))
			$this->return_bcash = $config['BCASH_RETURN'];
		if (isset($config['BCASH_CHECKOUT']))
			$this->checkout_bcash = $config['BCASH_CHECKOUT'];
		if (isset($config['BCASH_PREFIX']))
			$this->prefix_bcash = $config['BCASH_PREFIX'];
		if (isset($config['BCASH_STATUS_1']))
			$this->status_bcash_1 = $config['BCASH_STATUS_1'];
		if (isset($config['BCASH_STATUS_3']))
			$this->status_bcash_3 = $config['BCASH_STATUS_3'];
		if (isset($config['BCASH_STATUS_4']))
			$this->status_bcash_4 = $config['BCASH_STATUS_4'];
		if (isset($config['BCASH_STATUS_5']))
			$this->status_bcash_5 = $config['BCASH_STATUS_5'];
		if (isset($config['BCASH_STATUS_6']))
			$this->status_bcash_6 = $config['BCASH_STATUS_6'];
		if (isset($config['BCASH_STATUS_7']))
			$this->status_bcash_7 = $config['BCASH_STATUS_7'];
		if (isset($config['BCASH_STATUS_8']))
			$this->status_bcash_8 = $config['BCASH_STATUS_8'];
                
		parent::__construct();

		$this->displayName = $this->l('Bcash');
		$this->description = $this->l('Aceitar Pagamentos através do Bcash.');
                
		$this->confirmUninstall = $this->l('Are you sure you want to delete your details?');
                
		/*if (!isset($this->owner) || !isset($this->details) || !isset($this->address))
			$this->warning = $this->l('Account owner and details must be configured in order to use this module correctly.');
		if (!count(Currency::checkPaymentCurrencies($this->id)))
			$this->warning = $this->l('No currency set for this module');*/
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('payment') || !$this->registerHook('paymentReturn'))
			return false;

		return true;
	}

	public function uninstall()
	{
		if (!Configuration::deleteByName('BCASH_EMAIL')
                    || !Configuration::deleteByName('BCASH_COD_LOJA')
                    || !Configuration::deleteByName('BCASH_TOKEN')
                    || !Configuration::deleteByName('BCASH_RETURN')
                    || !Configuration::deleteByName('BCASH_CHECKOUT')
                    || !Configuration::deleteByName('BCASH_PREFIX')
                    || !Configuration::deleteByName('BCASH_STATUS_1')
                    || !Configuration::deleteByName('BCASH_STATUS_3')
                    || !Configuration::deleteByName('BCASH_STATUS_4')
                    || !Configuration::deleteByName('BCASH_STATUS_5')
                    || !Configuration::deleteByName('BCASH_STATUS_6')
                    || !Configuration::deleteByName('BCASH_STATUS_7')
                    || !Configuration::deleteByName('BCASH_STATUS_8')
                    || !parent::uninstall())
                    return false;
                
		return true;
	}

	private function _postValidation()
	{
		if (Tools::isSubmit('btnSubmit'))
		{
			if (!Tools::getValue('email_bcash'))
				$this->_postErrors[] = $this->l('Informe o email cadastrado na Bcash.');
		}
	}

	private function _postProcess()
	{
		if (Tools::isSubmit('btnSubmit'))
		{
                    Configuration::updateValue('BCASH_EMAIL', Tools::getValue('email_bcash'));
                    Configuration::updateValue('BCASH_COD_LOJA', Tools::getValue('cod_loja_bcash'));
                    Configuration::updateValue('BCASH_TOKEN', Tools::getValue('token_bcash'));
                    Configuration::updateValue('BCASH_RETURN', Tools::getValue('return_bcash'));
                    Configuration::updateValue('BCASH_CHECKOUT', Tools::getValue('checkout_bcash'));
                    Configuration::updateValue('BCASH_PREFIX', Tools::getValue('prefix_bcash'));
                    Configuration::updateValue('BCASH_STATUS_1', Tools::getValue('status_bcash_1'));
                    Configuration::updateValue('BCASH_STATUS_3', Tools::getValue('status_bcash_3'));
                    Configuration::updateValue('BCASH_STATUS_4', Tools::getValue('status_bcash_4'));
                    Configuration::updateValue('BCASH_STATUS_5', Tools::getValue('status_bcash_5'));
                    Configuration::updateValue('BCASH_STATUS_6', Tools::getValue('status_bcash_6'));
                    Configuration::updateValue('BCASH_STATUS_7', Tools::getValue('status_bcash_7'));
                    Configuration::updateValue('BCASH_STATUS_8', Tools::getValue('status_bcash_8'));
		}
		$this->_html .= '<div class="conf confirm"> '.$this->l('Settings updated').'</div>';
	}

	private function _displayBcash()
	{
		$this->_html .= '<img src="../modules/bcash/bcash.jpg" style="float:left; margin-right:15px;"><b>'.$this->l('Este módulo permite você aceitar pagamentos pelo Bcash.').'</b><br /><br />';                
	}

        private function _yesno_select($padrao = 'N'){
            $retorno = '';
            $retorno .= '<select name="return_bcash" style="width: 300px;">';
            if($padrao == 'N'){
                $retorno .= '<option value="N" selected>Não</option>';
                $retorno .= '<option value="Y">Sim</option>';
            }else{
                $retorno .= '<option value="N">Não</option>';
                $retorno .= '<option value="Y" selected>Sim</option>';
            }
            $retorno .= '</select>';
            
            return $retorno;
        }
        
        private function _returnstatus_select($padrao = '',$type_state = ''){
            global $cookie;
            $retorno = '';
            
            if (_PS_VERSION_ < '1.5'){
                $id_lang = $cookie->id_lang;
            }else{
                $id_lang = Context::getContext()->language->id;
            }
            
            $rq = Db::getInstance()->ExecuteS('SELECT `id_order_state`, `name` FROM `'._DB_PREFIX_.'order_state_lang`
                                             WHERE id_lang = \''.$id_lang.'\'');

            $retorno .= '<select name="'.$type_state.'" style="width: 300px;">';
            
            foreach ($rq as $status_bcash){
                if($status_bcash["id_order_state"] == $padrao){
                    $retorno .= '<option value="'.$status_bcash["id_order_state"].'" selected>'.$status_bcash["name"].'</option>';
                }else{
                    $retorno .= '<option value="'.$status_bcash["id_order_state"].'" >'.$status_bcash["name"].'</option>';
                }
            }
            
            $retorno .= '</select>';
            
            return $retorno;
        }
        
        private function _type_checkout_select($padrao = 'REDIRECT'){
            $retorno = '';
            $retorno .= '<select name="checkout_bcash" style="width: 300px;">';
            if($padrao == 'REDIRECT'){
                $retorno .= '<option value="REDIRECT" selected>Redirecionar</option>';
                $retorno .= '<option value="LIGHTBOX">Modal (LightBox)</option>';
            }else{
                $retorno .= '<option value="REDIRECT">Redirecionar</option>';
                $retorno .= '<option value="LIGHTBOX" selected>Modal (LightBox)</option>';
            }
            $retorno .= '</select>';
            
            return $retorno;
        }
        
        private function _displayForm()
	{
		$this->_html .=
		'<form action="'.Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset>
			<legend><img src="../img/admin/cog.gif" />'.$this->l('Configurações').'</legend>
				<table border="0" width="550" cellpadding="0" cellspacing="0" id="form">
					<tr><td colspan="2"><h4>'.$this->l('Configurações do módulo Bcash').'.</h4></td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Email').'</td><td><input type="text" name="email_bcash" value="'.htmlentities(Tools::getValue('email_bcash', $this->email_bcash), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Código da Loja').'</td><td><input type="text" name="cod_loja_bcash" value="'.htmlentities(Tools::getValue('cod_loja_bcash', $this->cod_loja_bcash), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Chave de Acesso').'</td><td><input type="text" name="token_bcash" value="'.htmlentities(Tools::getValue('token_bcash', $this->token_bcash), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
                                        <tr><td width="180" style="height: 35px;">'.$this->l('Tipo de Checkout').'</td><td>
                                                '.$this->_type_checkout_select(htmlentities(Tools::getValue('checkout_bcash', $this->checkout_bcash), ENT_COMPAT, 'UTF-8')).'
                                        </td><tr>
                                        <tr><td width="180" style="height: 35px;">'.$this->l('Prefixo do Pedido').'</td><td><input type="text" name="prefix_bcash" value="'.htmlentities(Tools::getValue('prefix_bcash', $this->prefix_bcash), ENT_COMPAT, 'UTF-8').'" style="width: 300px;" /></td></tr>
                                        <tr><td colspan="2"><h4>'.$this->l('Configurações do Retorno Automático Bcash').'.</h4></td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Ativar Retorno Automático').'</td><td>
                                                '.$this->_yesno_select(htmlentities(Tools::getValue('return_bcash', $this->return_bcash), ENT_COMPAT, 'UTF-8')).'
                                        </td><tr><td colspan="2"><br/><strong>'.$this->l('Status de Retorno').'</strong></td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Em Andamento').'</td><td>'.
                                            $this->_returnstatus_select(htmlentities(Tools::getValue('status_bcash_1', $this->status_bcash_1), ENT_COMPAT, 'UTF-8'),'status_bcash_1').
                                        '</td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Aprovado').'</td><td>'.
                                            $this->_returnstatus_select(htmlentities(Tools::getValue('status_bcash_3', $this->status_bcash_3), ENT_COMPAT, 'UTF-8'),'status_bcash_3').
                                        '</td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Concluído').'</td><td>'.
                                            $this->_returnstatus_select(htmlentities(Tools::getValue('status_bcash_4', $this->status_bcash_4), ENT_COMPAT, 'UTF-8'),'status_bcash_4').
                                        '</td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Em Disputa').'</td><td>'.
                                            $this->_returnstatus_select(htmlentities(Tools::getValue('status_bcash_5', $this->status_bcash_5), ENT_COMPAT, 'UTF-8'),'status_bcash_5').
                                        '</td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Devolvido').'</td><td>'.
                                            $this->_returnstatus_select(htmlentities(Tools::getValue('status_bcash_6', $this->status_bcash_6), ENT_COMPAT, 'UTF-8'),'status_bcash_6').
                                        '</td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Cancelado').'</td><td>'.
                                            $this->_returnstatus_select(htmlentities(Tools::getValue('status_bcash_7', $this->status_bcash_7), ENT_COMPAT, 'UTF-8'),'status_bcash_7').
                                        '</td></tr>
					<tr><td width="180" style="height: 35px;">'.$this->l('Chargeback').'</td><td>'.
                                            $this->_returnstatus_select(htmlentities(Tools::getValue('status_bcash_8', $this->status_bcash_8), ENT_COMPAT, 'UTF-8'),'status_bcash_8').
                                        '</td></tr>
					<tr><td colspan="2" align="center"><input class="button" name="btnSubmit" value="'.$this->l('Atualizar configurações').'" type="submit" /></td></tr>
				</table>
			</fieldset>
		</form>';
	}

	public function getContent()
	{
		$this->_html = '<h2>'.$this->displayName.'</h2>';

		if (Tools::isSubmit('btnSubmit'))
		{
			$this->_postValidation();
			if (!count($this->_postErrors))
				$this->_postProcess();
			else
				foreach ($this->_postErrors as $err)
					$this->_html .= '<div class="alert error">'.$err.'</div>';
		}
		else
			$this->_html .= '<br />';

                
		$this->_displayBcash();
		$this->_displayForm();

		return $this->_html;
	}
        
        public function execPayment($cart)
        {
            ini_set('display_errors', 1);
            //ini_set('log_errors', 1);
            //ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
            error_reporting(E_ALL);
            global $cookie, $smarty;
            
            $invoiceAddress 	= new Address(intval($cart->id_address_invoice));
            $customerPag 	= new Customer(intval($cart->id_customer));
            $currencies 	= Currency::getCurrencies();
            $currencies_used 	= array();
            $currency           = $this->getCurrency();

            if (!$this->checkCurrency($cart))
			Tools::redirect('index.php?controller=order');

            $smarty->assign(array(
                    'nbProducts' => $cart->nbProducts(),
                    'cust_currency' => $cart->id_currency,
                    'currencies' => $this->getCurrency((int)$cart->id_currency),
                    'total' => $cart->getOrderTotal(),
                    'cod_loja' => Configuration::get('BCASH_COD_LOJA'),
                    'this_path' => $this->_path,
                    'this_path_ssl' => Tools::getHttpHost(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
            ));
            
            /*$currencies = Currency::getCurrencies();
            foreach ($currencies as $key => $currency)
                $smarty->assign(array(
                    'currency_default' => new Currency(Configuration::get('PS_CURRENCY_DEFAULT')),
                    'currencies' => $currencies_used, 
                    'imgBtn' => "modules/pagamentodigital/imagens/pagamentodigital.jpg",
                    'imgBnr' => "http://www.pagamentodigital.com.br/img/".$this->_banners[Configuration::get('pagamentodigital_BANNER')],
                    'currency_default' => new Currency(Configuration::get('PS_CURRENCY_DEFAULT')),
                    'currencies' => $currencies_used, 
                    'total' => number_format(Tools::convertPrice($cart->getOrderTotal(true, 3), $currency), 2, '.', ''), 
                    'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ?
                    'https://' : 'http://') . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT,'UTF-8') . __PS_BASE_URI__ . 'modules/' . $this->name . '/'));
            */
            return $this->display(__file__, '/views/templates/front/payment_execution_1_4.tpl');
        }

               
	public function hookPayment($params)
	{
                
		if (!$this->active)
			return;
		if (!$this->checkCurrency($params['cart']))
			return;

                if (_PS_VERSION_ >= '1.5'){
                    $this->smarty->assign(array(
                    	'this_path' => $this->_path,
			'this_path_ssl' => Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/'.$this->name.'/'
                    ));
                    return $this->display(__FILE__, 'payment.tpl');
                }else{
                    
                    global $smarty;
                    
                    $smarty->assign(array(
			'this_path' => $this->_path,
                        'this_path_ssl' => (Configuration::get('PS_SSL_ENABLED') ?
			'https://' : 'http://') . htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT,
			'UTF-8') . __PS_BASE_URI__ . 'modules/' . $this->name . '/'));
                    
                    
                    return $this->display(__FILE__, '/views/templates/hook/payment_1_4.tpl');
                }
                
		
	}

	public function hookPaymentReturn($params)
	{
            
            if (_PS_VERSION_ >= '1.5'){
                $smarty = $this->context->smarty;
                $customer = $this->context->customer;
            }else{
                global $cookie, $smarty;
                $customer = new Customer(intval($params['objOrder']->id_customer));
            }
            
            if((isset($_GET["return"])) && $_GET["return"] == "true"){
                if (_PS_VERSION_ >= '1.5'){
                    return $this->display(__FILE__, 'payment_ok.tpl');
                }else{
                    return $this->display(__FILE__, '/views/templates/hook/payment_ok.tpl');
                }
            }
            
            //var_dump(Configuration::get('BCASH_CHECKOUT'));
            if(isset($_POST["id_transacao"])){
                
                if(Configuration::get('BCASH_CHECKOUT') == 'REDIRECT'){
                    
                    if (_PS_VERSION_ >= '1.5'){
                        Tools::redirect(Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'index.php?controller=order-confirmation&id_cart='.$_GET["id_cart"].'&id_module='.$_GET["id_module"].'&id_order='.$_GET["id_order"].'&key='.$_GET["key"].'&return=true');
                    }else{
                        Tools::redirect('index.php?controller=order-confirmation&id_cart='.$_GET["id_cart"].'&id_module='.$_GET["id_module"].'&id_order='.$_GET["id_order"].'&key='.$_GET["key"].'&return=true');
                    }
                }else{
                    $dados["type_checkout"] = "NOTIFICATION";
                    $dados["status"] = "ok";
                    if (_PS_VERSION_ >= '1.5'){
                        $dados["url_refresh"] = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'index.php?controller=order-confirmation&id_cart='.$_GET["id_cart"].'&id_module='.$_GET["id_module"].'&id_order='.$_GET["id_order"].'&key='.$_GET["key"].'&return=true';
                    }else{
                        $dados["url_refresh"] = Tools::getHttpHost(true, true).__PS_BASE_URI__.'order-confirmation.php?id_cart='.$_GET["id_cart"].'&id_module='.$_GET["id_module"].'&id_order='.$_GET["id_order"].'&key='.$_GET["key"].'&return=true';
                    }
                        
                    if (_PS_VERSION_ >= '1.5'){
                        $this->context->smarty->assign($dados);
                        return $this->display(__FILE__, 'payment_return_lightbox.tpl');
                    }else{
                        $smarty->assign($dados);
                        return $this->display(__FILE__, '/views/templates/hook/payment_return_lightbox.tpl');
                    }
                    
                    
                }
            }  else {
                
		if (!$this->active)
			return;

                $order = $params['objOrder'];
                
		$state = $params['objOrder']->getCurrentState();
                
                $cart = $params['cart'];
                
                $detalhes_pedido = $cart->getSummaryDetails();
                $delivery = $detalhes_pedido["delivery"];
                
                $estado = $this->getStateBcash(State::getNameById($delivery->id_state));
                
                $dados = array();
                $dados["email_loja"] = Configuration::get('BCASH_EMAIL');
                $dados["id_pedido"] = Configuration::get('BCASH_PREFIX').$order->id;
                $dados["email"] = $customer->email;
                $dados["nome"] = $customer->firstname . " " . $customer->lastname;
                $dados["cpf"] = '';
                $dados["telefone"] = $delivery->phone;
                $dados["cep"] = $delivery->postcode;
                $dados["endereco"] = $delivery->address1;
                $dados["bairro"] = $delivery->address2;
                $dados["cidade"] = $delivery->city;
                $dados["estado"] = $estado;
                
                $i = 0;
                
                foreach($order->getProducts() as $produto){
                    $i += 1;
                    
                    if (_PS_VERSION_ >= '1.5'){
                        $pvalor = Tools::ps_round($produto["unit_price_tax_incl"],2);
                    }else{
                        $pvalor = Tools::ps_round($produto["product_price_wt"],2);
                    }
                    $produto_codigo[] = $produto["product_id"];
                    $produto_descricao[] = $produto["product_name"];
                    $produto_qtde[] = $produto["product_quantity"];
                    $produto_valor[] = $pvalor;
                }
                
                $dados["produto_codigo"] = $produto_codigo;
                $dados["produto_descricao"] = $produto_descricao;
                $dados["produto_qtde"] = $produto_qtde;
                $dados["produto_valor"] = $produto_valor;
                
                $dados["qtd_produtos"] = $i;
                
                $dados["frete"] = $order->total_shipping;
                $dados["desconto"] = $order->total_discounts;
                $dados["acrescimo"] = $order->total_discounts;
                
                $dados["status"] = "ok";
                $dados["type_checkout"] = Configuration::get('BCASH_CHECKOUT');
                
                
                if(Configuration::get('BCASH_RETURN') == "Y"){
                    
                    if (_PS_VERSION_ >= '1.5'){
                        $dados["url_retorno"] = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'index.php?controller=order-confirmation&id_cart='.$_GET["id_cart"].'&id_module='.$_GET["id_module"].'&id_order='.$_GET["id_order"].'&key='.$_GET["key"];
                        $dados["url_aviso"] = Tools::getShopDomainSsl(true, true).__PS_BASE_URI__.'modules/bcash/includes/notifier.php';
                    }else{
                        $dados["url_retorno"] = Tools::getHttpHost(true, true).__PS_BASE_URI__.'order-confirmation.php?controller=order-confirmation&id_cart='.$_GET["id_cart"].'&id_module='.$_GET["id_module"].'&id_order='.$_GET["id_order"].'&key='.$_GET["key"];
                        $dados["url_aviso"] = Tools::getHttpHost(true, true).__PS_BASE_URI__.'modules/bcash/includes/notifier.php';
                    }
                    
                    $dados["redirect"] = "true";
                    $dados["redirect_time"] = "30";
                    
                }
                
                $dados["this_path"] = $this->_path;
                
		if ($state == Configuration::get('BCASH_STATUS_1') || $state == Configuration::get('PS_OS_OUTOFSTOCK'))
		{
                    $smarty->assign($dados);
                    if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
			$this->smarty->assign('reference', $params['objOrder']->reference);
		}
		else
			$this->smarty->assign('status', 'failed');
                
                if (_PS_VERSION_ >= '1.5'){
                    return $this->display(__FILE__, 'payment_return.tpl');
                }else{
                    return $this->display(__FILE__, '/views/templates/hook/payment_return.tpl');
                }
                
            }
	}
	
	public function checkCurrency($cart)
	{
		$currency_order = new Currency($cart->id_currency);
		$currencies_module = $this->getCurrency($cart->id_currency);

		if (is_array($currencies_module))
			foreach ($currencies_module as $currency_module)
				if ($currency_order->id == $currency_module['id_currency'])
					return true;
		return false;
	}
        
        public function getStateBcash($state){
            switch ($state){
                case "Acre":
                        $state = "AC";
                    break;
                case "Alagoas":
                        $state = "AL";
                    break;
                case "Amapá":
                        $state = "AP";
                    break;
                case "Amazonas":
                        $state = "AM";
                    break;
                case "Bahia":
                        $state = "BA";
                    break;
                case "Ceará":
                        $state = "CE";
                    break;
                case "Distrito Federal":
                        $state = "DF";
                    break;
                case "Brasília":
                        $state = "DF";
                    break;
                case "Espírito Santo":
                        $state = "ES";
                    break;
                case "Goiás":
                        $state = "GO";
                    break;
                case "Maranhão":
                        $state = "MA";
                    break;
                case "Mato Grosso":
                        $state = "MT";
                    break;
                case "Mato Grosso do Sul":
                        $state = "MS";
                    break;
                case "Minas Gerais":
                        $state = "MG";
                    break;
                case "Pará":
                        $state = "PA";
                    break;
                case "Paraíba":
                        $state = "PB";
                    break;
                case "Paraná":
                        $state = "PR";
                    break;
                case "Pernambuco":
                        $state = "PE";
                    break;
                case "Piauí":
                        $state = "PI";
                    break;
                case "Rio de Janeiro":
                        $state = "RJ";
                    break;
                case "Rio Grande do Norte":
                        $state = "RN";
                    break;
                case "Rio Grande do Sul":
                        $state = "RS";
                    break;
                case "Rondônia":
                        $state = "RO";
                    break;
                case "Roraima":
                        $state = "RR";
                    break;
                case "Santa Catarina":
                        $state = "SC";
                    break;
                case "São Paulo":
                        $state = "SP";
                    break;
                case "Sergipe":
                        $state = "SE";
                    break;
                case "Tocantins":
                        $state = "TO";
                    break;
            }
            return $state;
        }
}
