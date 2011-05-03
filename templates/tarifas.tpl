{*
 *  This file is part of SNEP.
 *  Para território Brasileiro leia LICENCA_BR.txt
 *  All other countries read the following disclaimer
 *
 *  SNEP is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  SNEP is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with SNEP.  If not, see <http://www.gnu.org/licenses/>.
 *}

{include file="cabecalho.tpl"}

<table cellspacing="0" align="center" class="contorno">

   <form name="formulario"  method="POST" enctype="multipart/form-data" action="{$smarty.server.SCRIPT_NAME}?acao={$ACAO}" onSubmit="return check_form('{$ACAO}');">

   {if $ACAO == "grava_alterar"}
      {assign var="view" value="readonly='true' disabled='true' class='campos_disable'"}

   {else}
      {assign var="view" value="class='campos'"}

   {/if}

   <tr>
      <td valign="top" {if $ACAO == "grava_alterar"} width="30%" {else} width="40%" {/if} >
         <table class="subtable">

            {if $ACAO == "grava_alterar"}
               <tr>
                  <td class="subtable" colspan="2" style="text-align:center; font-size:" height="30">
                    <strong> {$LANG.datatarifa} </strong>
                  </td>
               </tr>
            {/if}

            <tr>
               <td class="formlabel" style="width:40%;" > {$LANG.operadora}: </td>
               <td class="subtable" >
                  <select name="operadora" {$view} >
                     {html_options options=$OPERADORAS selected=$dt_tarifas.operadora}
                  </select>&nbsp;*
               </td>
            </tr>

            <tr>
               <td class="formlabel">{$LANG.country}:</td>
               <td class="subtable" >
                  <input name="pais" type="text" size="20" maxlength="30" {$view} value="{$dt_tarifas.pais|default:BRASIL}" >
               </td>
            </tr>

            <tr>
               <td class="formlabel">{$LANG.state}:</td>
               <td class="subtable" >
                 <div class="waiting" id="wait_estado" style="display:none;">

                  </div>

                  {if $ACAO != "grava_alterar"}
                      <select onchange="cidades(this.value)" name="estado" {$view}>
                         {html_options options=$ESTADOS selected=--}
                      </select>

               
                  {/if}
                  {if $ACAO == "grava_alterar"}
                      <select onchange="prefixo(this.value)" name="estado" {$view}>
                          <option>{$ESTADO}</option>
                      </select>
                  {/if}
               </td>
            </tr>

            <tr>
               <td class="formlabel">{$LANG.city}:</td>
               <td class="subtable" >
               
                  <select name="cidade" id="cidade" {$view} >
                       <option>{$CITY}</option>
                       {if $ACAO == "grava_alterar"}
                            <option>{$CIDADE}</option>
                       {/if}

                       {if $ACAO != "grava_alterar"}
                            {html_options options=$CIDADES selected=$dt_tarifas.cidade}
                       {/if}
                  </select>&nbsp;*
               </td>
            </tr>
         
            <tr>
               <td class="formlabel">{$LANG.cod_ddi}:</td>
               <td class="subtable" >
                  <input name="ddi" type="text" size="3" maxlength="4" {$view} value="{$dt_tarifas.ddi|default:55}" >&nbsp;*
               </td>
            </tr>

            <tr>
               <td class="formlabel">{$LANG.cod_ddd}:</td>
               <td class="subtable" >
                  <input name="ddd" id="ddd" type="text" size="2" maxlength="3" {$view} value="{$dt_tarifas.ddd|default:0}" >&nbsp;*
               </td>
            </tr>

            <tr>
               <td class="formlabel">{$LANG.prefix}:</td>
               <td class="subtable" >
                  <input name="prefixo" id="prefixo" type="text" size="3" maxlength="4" {$view} value="{$dt_tarifas.prefixo|default:'0000'}" >&nbsp;*
               </td>
            </tr>

            {if $ACAO != "grava_alterar"}
                <tr>
                   <td class="formlabel">{$LANG.vlrbase_fix}:</td>
                   <td class="subtable" >
                      <input name="vfix" id="vfix" class="campos" type="text" size="5"  value="0"  style="text-align: right;" /> {$LANG.dottodec}
                   </td>
                </tr>
                <!--
                <tr>
                   <td class="formlabel">{$LANG.vlrpartida_fix}:</td>
                   <td class="subtable" >
                      <input name="vpf" id="vpf" class="campos" type="text" size="5"  value="0"  style="text-align: right;" /> {$LANG.dottodec}
                   </td>
                </tr>
                -->
                <tr>
                   <td class="formlabel">{$LANG.vlrbase_cel}:</td>
                   <td class="subtable" >
                      <input name="vcel" id="vcel" class="campos" type="text" size="5"  value="0" style="text-align: right;" /> {$LANG.dottodec}
                   </td>
                </tr>
                <!-- 
                <tr>
                   <td class="formlabel">{$LANG.vlrpartida_cel}:</td>
                   <td class="subtable" >
                      <input name="vpc" id="vpc" class="campos" type="text" size="5"  value="0"  style="text-align: right;" /> {$LANG.dottodec}
                   </td>
                </tr>
                -->
            {/if}
            <tr>
               <td colspan="2" class="subtable">
                 <br />
                 * {$LANG.requerid}
               </td>
            </tr>    
         </table>
      </td>
      <td valign="top">
         {if $ACAO == "grava_alterar"}
            {include file="tarifas_alterar.tpl"}
         {else}
            {$LANG.txt_inctarifas}
         {/if}
      </td>
   </tr>
   <tr>
      <td colspan="2" class="subtable" valign="center" align="center" height="40px" valign="top">

         <input type="hidden" name="codigo" value="{$id_tarifa}" />

         <input class="button" type="submit" id="gravar" value="{$LANG.save}">

         <div class="buttonEnding"></div> &nbsp;&nbsp;&nbsp;
         <input class="button" type="button" id="voltar" value="{$LANG.back}" onClick="location.href='../tarifas/rel_tarifas.php'" />
         <div class="buttonEnding"></div>         
         
      </td>
   </tr>
   </form>

</table>

{ include file="rodape.tpl }

<script language="javascript" type="text/javascript">
    
 document.forms[0].elements[0].focus() ;
 
 function check_form(acao) {ldelim}
    campos = new Array() ;
    campos[0] = "{$LANG.city};"+document.formulario.cidade.value+";NOT_NULL;";
    campos[1] = "{$LANG.ddi};"+document.formulario.ddi.value+";NUM;";
    campos[2] = "{$LANG.ddd};"+document.formulario.ddd.value+";NUM;";
    campos[3] = "{$LANG.prefix};"+document.formulario.prefixo.value+";NOT_NULL;";
    campos[4] = "{$LANG.operadora};"+document.formulario.operadora.value+";NOT_NULL;";
    if (acao != "grava_alterar") {ldelim}
       campos[5]="{$LANG.vlrbase_fix};"+document.formulario.vfix.value+";FLOAT;";
       campos[6]="{$LANG.vlrbase_cel};"+document.formulario.vcel.value+";FLOAT;";
    {rdelim} else {ldelim}
       var ctd = 5 ;
       var lst = document.formulario.action ;
       for(var x=0 ; x<lst.length ; x++){ldelim}
          var ind = lst[x].value ;
          var avfix = document.formulario.elements['vfix['+ind+']'] ;
          var avcel = document.formulario.elements['vcel['+ind+']'] ;
          if (lst.checked) {ldelim}
             // Verifica Nome nao esta em Branco
             dados[ctd] = "{$LANG.vlrbase_fix};"+avfix.value+";FLOAT;";
             ctd += 1;
             // Verifica senha numerica
             dados[ctd] = "{$LANG.vlrbase_cel};"+avcel.value+";FLOAT;";
             ctd += 1 ;
          {rdelim}
       {rdelim}
    {rdelim}
    
    return valida_formulario(campos) ;
    
 {rdelim}
 { include file="../includes/javascript/functions_smarty.js" }

</script>

<script language="javascript" type="text/javascript">
{literal}
   function cidades(uf) {

       $('wait_estado').show();

        var url = 'rel_cidades.php';
        var params = 'uf='+uf;
        var retorno = new Ajax.Request (
            url, {
                method: 'POST',
                parameters: params,
                onComplete: ret_cidade
            }
        );

   }

   function ret_cidade(ret) {
       $('cidade').innerHTML = "";
       $('cidade').insert(ret.responseText);       
       $('wait_estado').hide();
   }
   
   function prefixo(cidade) {

       var url = 'rel_prefixo.php';
       var params = 'cidade='+cidade;
       var retorno = new Ajax.Request (
           url, {
                method: 'POST',
                parameters: params,
                onComplete: ret_prefixo
           }
       );
   }

   function ret_prefixo(ret) {

       alert(ret.responseText);
       $('ddd').value = ret.responseText;
   }

{/literal}
</script>
