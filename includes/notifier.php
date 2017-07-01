<?php
/*
*  @author Buscapé Company <integracao@bcash.com.br>
*  @version  Release: 1.0
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*/

include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../../init.php');

include_once(_PS_MODULE_DIR_.'bcash/bcash.php');

define('TIMEOUT', 30);

class BcashNotifier extends Bcash
{

        protected $httpCode = false;

	public function __construct()
	{
		parent::__construct();
	}

	public function confirmOrder($transactionResponse)
	{
            //var_dump($transactionResponse);
                $id_pedido = str_replace(Configuration::get('BCASH_PREFIX'),"",$transactionResponse->transacao->id_pedido);
                $order = new Order((int)$id_pedido);
                
		$total_price = Tools::ps_round($order->total_paid, 2);
		$total_bcash = Tools::ps_round($transactionResponse->transacao->valor_original, 2);

		$message = null;
		
                if ($this->httpCode != 200){
                    $message = 'Erro Status: '.$this->httpCode.'<br>Código: '.$transactionResponse->erro->codigo.'<br>Descrição: '.$transactionResponse->erro->descricao.'<br />';
                    echo $message;
                }else{
                    if ($total_bcash != $total_price){
                        $payment = (int)Configuration::get('BCASH_STATUS_1');
                        $message = 'Total pago ao Bcash é diferente do valor original.<br />';
                        echo $message."<br>Total Bcash: ".$total_bcash."<br>Total Loja: ".$total_price;
                    }else{
                        switch ((int)$transactionResponse->transacao->cod_status){
                            case 1:
                                    $payment = Configuration::get('BCASH_STATUS_1');
                                    $message = 'Transação Em Andamento no Bcash<br />';
                                break;
                            case 2:
                                    $payment = Configuration::get('BCASH_STATUS_1');
                                    $message = 'Transação Em Andamento no Bcash<br />';
                                break;
                            case 3:
                                    $payment = Configuration::get('BCASH_STATUS_3');
                                    $message = 'Transação Aprovada no Bcash<br />';
                                break;
                            case 4:
                                    $payment = Configuration::get('BCASH_STATUS_4');
                                    $message = 'Transação Concluída no Bcash<br />';
                                break;
                            case 5:
                                    $payment = Configuration::get('BCASH_STATUS_5');
                                    $message = 'Transação Em Disputa no Bcash<br />';
                                break;
                            case 6:
                                    $payment = Configuration::get('BCASH_STATUS_6');
                                    $message = 'Transação Devolvida no Bcash<br />';
                                break;
                            case 7:
                                    $payment = Configuration::get('BCASH_STATUS_7');
                                    $message = 'Transação Cancelada no Bcash<br />';
                                break;
                            case 8:
                                    $payment = Configuration::get('BCASH_STATUS_8');
                                    $message = 'Transação Cancelada (Chargeback) no Bcash<br />';
                                break;
                                
                        }
                        
                        $history = new OrderHistory();
                        $history->id_order = (int)$id_pedido;
                                                
                        if (_PS_VERSION_ < '1.5'){
                            $history->changeIdOrderState((int)$payment, (int)$id_pedido);
                        }else{
                            $history->changeIdOrderState((int)$payment, $order);
                        }
                        
                        $history->add();
                        
                        $message_history = new Message();
                        $message_history->id_order = (int)$id_pedido;
                        $message_history->message = $message;
                        $message_history->private = TRUE;
                        
                        $message_history->add();
                        
                        echo $message;
                    }
                }
	}

	public function fetchResponseTransaction($data)
	{
            //17921520
		$ch = curl_init();

                $dataRequest = array("id_transacao"=>$data["transacao_id"],"id_pedido"=>$data["pedido"],"tipo_retorno"=>"2","codificacao"=>"1");
                
		curl_setopt($ch, CURLOPT_URL, "https://www.bcash.com.br/transacao/consulta/");
                curl_setopt($ch, CURLOPT_POST, 1); 
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataRequest); 
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic ".base64_encode(Configuration::get('BCASH_EMAIL'). ":".Configuration::get('BCASH_TOKEN')))); 
                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);                
                curl_exec($ch);
                
                $result = ob_get_contents(); 
                
                ob_end_clean(); 
                
                $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
                
		curl_close($ch);

		return $result;
	}

	
}

//if ($custom = Tools::getValue('custom'))
//{
$notifier = new BcashNotifier();
if (_PS_VERSION_ >= '1.5'){
    $result = Tools::jsonDecode($notifier->fetchResponseTransaction($_POST), false);
}else{
    $result = json_decode($notifier->fetchResponseTransaction($_POST), false);
}

$notifier->confirmOrder($result);
//}
