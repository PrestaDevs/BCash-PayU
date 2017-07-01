{*
*  @author Buscapé Company <integracao@bcash.com.br>
*  @version  Release: 1.0
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

{if $status == 'ok'}
    <form name="bcash_form" id="bcash_form" action="https://www.bcash.com.br/checkout/pay/" method="post">
        <input name="email_loja" type="hidden" value="{$email_loja}"> 
        <input name="id_pedido" type="hidden" value="{$id_pedido}"> 
        <input name="email" type="hidden" value="{$email}"> 
        <input name="nome" type="hidden" value="{$nome}"> 
        <input name="cpf" type="hidden" value="{$cpf}"> 
        <input name="telefone" type="hidden" value="{$telefone}"> 
        <input name="cep" type="hidden" value="{$cep}"> 
        <input name="endereco" type="hidden" value="{$endereco}"> 
        <input name="bairro" type="hidden" value="{$bairro}"> 
        <input name="cidade" type="hidden" value="{$cidade}"> 
        <input name="estado" type="hidden" value="{$estado}"> 
        <input name="frete" type="hidden" value="{$frete}"> 
        <input name="desconto" type="hidden" value="{$desconto}"> 
        <input name="acrescimo" type="hidden" value="{$acrescimo}"> 
        
        {section name='produto_codigo' loop=$produto_codigo} 
                <input name="produto_codigo_{$smarty.section.produto_codigo.index+1}" type="hidden" value="{$produto_codigo[$smarty.section.produto_codigo.index]}"> 
        {/section}
        {section name='produto_descricao' loop=$produto_descricao} 
                <input name="produto_descricao_{$smarty.section.produto_descricao.index+1}" type="hidden" value="{$produto_descricao[$smarty.section.produto_descricao.index]}"> 
        {/section}
        {section name='produto_qtde' loop=$produto_qtde}
                <input name="produto_qtde_{$smarty.section.produto_qtde.index+1}" type="hidden" value="{$produto_qtde[$smarty.section.produto_qtde.index]}"> 
        {/section}
        {section name='produto_valor' loop=$produto_valor}
                <input name="produto_valor_{$smarty.section.produto_valor.index+1}" type="hidden" value="{$produto_valor[$smarty.section.produto_valor.index]}"> 
        {/section}
        
        <input name="url_retorno" type="hidden" value="{$url_retorno}"> 
        <input name="url_aviso" type="hidden" value="{$url_aviso}"> 
        <input name="redirect" type="hidden" value="{$redirect}"> 
        <input name="redirect_time" type="hidden" value="{$redirect_time}"> 
    </form>
    <p>
        {l s='Sua compra est&aacute; em processo de finaliza&ccedil;&atilde;o.' mod='bcash'}
        {if $type_checkout == 'REDIRECT'}
            <br /><br />
            {l s='Você será redirecionado para o Bcash. Caso a p&aacute;gina de finaliza&ccedil;&atilde;o de pagamento n&atilde;o se inicie automaticamente, ' mod='bcash'}<strong><a href="#" id="bcash_lightbox">Clique Aqui</a></strong>
            <br /><br />
            <object type="application/x-shockwave-flash" data="https://a248.e.akamai.net/f/248/96284/12h/www.bcash.com.br/webroot/banners/site/meios/meios_468x60.swf" width="468" height="60"><param name="movie" value="https://a248.e.akamai.net/f/248/96284/12h/www.bcash.com.br/webroot/banners/site/meios/meios_468x60.swf" /><param name="wmode" value="transparent" /></object>
            <br /><br /> <strong>{l s='Seu pedido será enviado logo que recebermos a confirmação de pagamento.' mod='bcash'}</strong>.
            <script type="text/javascript">
                {literal}
                document.getElementById('bcash_form').submit();
                document.getElementById('bcash_lightbox').onclick = function (){
                    document.getElementById('bcash_form').submit();
                };
                {/literal}
            </script>  
        {elseif $type_checkout == 'NOTIFICATION'}
            <script type="text/javascript">
                parent.document.getElementById('overlay-bcash').style.display = "none";
            </script>
        {else}
            <style>
                #overlay-bcash {literal}{ {/literal}
                    background-image: url('{$this_path}overlay.png');
                    display:block;
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    width: 100%;
                    height: 100%;
                    z-index: 2147483647;
                {literal}}{/literal}
            </style>
            <script type="text/javascript">
                var div_bcash = document.createElement("div");
                var center_frame = document.createElement("center");
                var div_frame = document.createElement("div");
                var img_close = document.createElement("img");
                var frame_bcash = document.createElement("iframe");

                frame_bcash.setAttribute("style","border:0px");
                frame_bcash.setAttribute("width","970");
                frame_bcash.setAttribute("height","700");
                frame_bcash.setAttribute("name","bcash_frame");

                img_close.setAttribute("style","position: relative;margin-left: 970px;margin-top: -20px;");
                img_close.setAttribute("src","{$this_path}close.png");

                div_frame.setAttribute("style","padding: 10px;background-color: #fff;width: 980px;margin-top: 50px");
                div_frame.setAttribute("id","lightbox");

                div_bcash.setAttribute("id","overlay-bcash");

                div_frame.appendChild(img_close);
                div_frame.appendChild(frame_bcash);

                center_frame.appendChild(div_frame);

                div_bcash.appendChild(center_frame);

                document.body.appendChild(div_bcash);
            </script>
            <br /><br />
            {l s='Caso a p&aacute;gina de finaliza&ccedil;&atilde;o de pagamento n&atilde;o se inicie automaticamente, ' mod='bcash'}<strong><a href="#" id="bcash_lightbox">Clique Aqui</a></strong>
            <br /><br />
            <object type="application/x-shockwave-flash" data="https://a248.e.akamai.net/f/248/96284/12h/www.bcash.com.br/webroot/banners/site/meios/meios_468x60.swf" width="468" height="60"><param name="movie" value="https://a248.e.akamai.net/f/248/96284/12h/www.bcash.com.br/webroot/banners/site/meios/meios_468x60.swf" /><param name="wmode" value="transparent" /></object>
            <br /><br /> <strong>{l s='Seu pedido será enviado logo que recebermos a confirmação de pagamento.' mod='bcash'}</strong>.
            
            <script type="text/javascript">
                {literal}
                document.getElementById('bcash_form').setAttribute("target", "bcash_frame");
                document.getElementById('bcash_form').submit();
                document.getElementById('overlay-bcash').onclick = function (){
                    this.style.display = "none";
                };
                document.getElementById('bcash_lightbox').onclick = function (){
                    document.getElementById('overlay-bcash').style.display = "block";
                };
                {/literal}
            </script>
        {/if}        
    </p>
{else}
	<p class="warning">
		{l s='Percebemos um problema com seu pedido. Se você acha que isso é um erro, você pode contatar nosso.' mod='bcash'} 
		<a href="{$link->getPageLink('contact', true)}">{l s='Suporte ao cliente' mod='bcash'}</a>.
	</p>
{/if}
