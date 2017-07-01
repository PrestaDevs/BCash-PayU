{*
*  @author Buscapé Company <integracao@bcash.com.br>
*  @version  Release: 1.0
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{capture name=path}{l s='Bcash' mod='bcash'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Resumo do Pedido' mod='bcash'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='bcash'}</p>
{else}

<form action="{$this_path_ssl}validation_1_4.php" method="post">
<p>
	<img src="{$this_path}bcash.jpg" alt="{l s='Bcash' mod='bcash'}" style="float:left; margin: 0px 10px 5px 0px;" />
	{l s='Você escolheu pagar pelo Bcash.' mod='bcash'}
	<br/><br />
	{l s='Aqui está um breve resumo de seu pedido:' mod='bcash'}
</p>
<br/><br /><br/><br />
<p style="margin-top:20px;">
	- {l s='O valor total de seu pedido é' mod='bcash'}
	<span id="amount" class="price">{displayPrice price=$total}</span><br/>
	<iframe width="450px" height="350px" frameborder="0" src="https://www.bcash.com.br/site/calcula_parcelamento_cliente.php?valor={$total}&key={$cod_loja}&nmp=24" ></iframe>
</p>

<p>
	{l s='Informações sobre o pagamento serão exibidas na página seguinte.' mod='bcash'}
	<br /><br />
	<b>{l s='Por favor, confirme seu pedido clicando em \'Confirmo meu pedido\'.' mod='bcash'}</b>
</p>
<p class="cart_navigation">
	<input type="submit" name="submit" value="{l s='Confirmo meu pedido' mod='bcash'}" class="exclusive_large" />
	<a href="{$link->getPageLink('order', true, NULL, "step=3")}" class="button_large">{l s='Outro meio de pagamento' mod='bcash'}</a>
</p>
</form>
{/if}
