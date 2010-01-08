{*
 *  This file is part of SNEP.
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

 <table>
    <form method="post"  name="relatorio">
        
    <input type="hidden" name="vinculos" id="vinculos" value="{$VINCULOS}"/>
    <tr style="background-color: #f1f1f1;">
       <td class="esq" width="30%">
           {if $NIVEL == 1}
               {$LANG.access_level} :
           {elseif $NIVEL == 2}
               {$LANG.access_level} :
           {elseif $NIVEL == 3}
               {$LANG.access_level} :
           {/if}
       </td>
       <td class="esq">
           {if $NIVEL == 1}
               {$LANG.vinculos_todos}
           {elseif $NIVEL == 2}
               {$LANG.vinculos_self}
           {elseif $NIVEL == 3}
               {$LANG.vinculos_ramais} : {$VINCULOS}
           {/if}
       </td>
    </tr>

    <tr>
       <td class="esq" width="30%">
          {$LANG.periodo}
       </td>
       <td class="esq">
          <table class="subtable">
             <tr>
                <td class="subtable" width="15%">
                   {$LANG.apartir} :
                </td>
                <td class="subtable">
                   <input type="text" size="9" maxlength="10" class="campos" name="dia_ini" value="{$dt_relchamadas.dia_ini}" onKeyUp="mascara_data(this,this.value)"/>&nbsp;dd/mm/aaaa
                   &nbsp;&nbsp;&nbsp;
                   <input type="text" size="4" maxlength="5" class="campos" name="hora_ini" value="{$dt_relchamadas.hora_ini}" onKeyUp="mascara_hora(this,this.value)"/>&nbsp;hh:mm
                </td>
             </tr>
             <tr>
                <td class="subtable">
                  {$LANG.ate} :
                </td>
                <td class="subtable">
                   <input type="text" size="9" maxlength="10" class="campos" name="dia_fim" value="{$dt_relchamadas.dia_fim}" onKeyUp="mascara_data(this,this.value)"/>&nbsp;dd/mm/aaaa
                   &nbsp;&nbsp;&nbsp;
                   <input type="text" size="4" maxlength="5" class="campos" name="hora_fim" value="{$dt_relchamadas.hora_fim}" onKeyUp="mascara_hora(this,this.value)"/>&nbsp;hh:mm
                </td>
             </tr>
          </table>
       </td>
    </tr>
    <tr>
       <td class="esq">
           <!--
              {if $VINCULOS}
                <input type="radio" name="orides" value="origem" onClick="javascript:permitedados('dst','{$VINCULOS}')" checked="true">
              {/if}
           -->
              {$LANG.origin}(s)&nbsp;{$LANG.morethatone}
           
       </td>
       <td class="esq">
          
          {$LANG.group} :
            <select name="groupsrc" class="campos" onChange="javascript:grupos('src', this.value)">
                 {html_options options=$OPCOES_USERGROUPS selected=$groupsrc}
            </select>
          &nbsp;&nbsp;
          
          <input type="text" name="src" id="src" class="campos" />
          &nbsp;&nbsp;          
          {html_radios name="srctype" options=$OPCOES_PROCURA selected=1 }
       </td>
    </tr>
    <tr>
       <td class="esq">
           <!--
          {if $VINCULOS}
            <input type="radio" name="orides" value="destino"  onClick="javascript:permitedados('src','{$VINCULOS}')">
          {/if}
           -->
          {$LANG.destination}(s)&nbsp;{$LANG.morethatone}
           
       </td>
       <td class="esq">
           
           {$LANG.group} :
            <select name="groupdst" class="campos" onClick="javascript:grupos('dst', this.value)">
                 {html_options options=$OPCOES_USERGROUPS selected=$groupdst} 
            </select>
          &nbsp;&nbsp;
           
          <input type="text" name="dst"  id="dst"  class="campos"  >
          &nbsp;&nbsp;
          {html_radios name="dsttype" options=$OPCOES_PROCURA selected=1}

       </td>
    </tr>
    <tr>
       <td class="esq">
          {$LANG.menu_ccustos}
       </td>
       <td class="esq">
          <SELECT name="contas[]" multiple=true size="10" class="campos">
             {html_options options=$CCUSTOS selected=$ccusto}
          </SELECT>
       </td>
    </tr>
    <tr>
       <td class="esq">
          {$LANG.callstatus}
       </td>
       <td class="esq">
          <input type="checkbox" name="status_all" {$status_all} value="ALL" />&nbsp;{$LANG.all}
          <input type="checkbox" name="status_ans" {$status_ans} value="ANSWERED" />&nbsp;{$LANG.answered}
          <input type="checkbox" name="status_noa" {$status_noa} value="NO ANSWER" />&nbsp;{$LANG.notanswered}
          <input type="checkbox" name="status_bus" {$status_bus} value="BUSY" />&nbsp;{$LANG.busys}
          <input type="checkbox" name="status_fai" {$status_fai} value="FAILED" />&nbsp;{$LANG.fail}
      </td>
   </tr>
   <tr>
       <td class="esq">
         {$LANG.duration} - {$LANG.seconds}
       </td>
       <td class="esq">
         {$LANG.from}: &nbsp;
         <input type="text" class="campos" name="duration1" id="duration1" size="4" value="{$duration1}">
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         {$LANG.ate}: &nbsp;
         <input type="text"  class="campos" NAME="duration2" id="duration2" size="4" value="{$duration2}">
       </td>
    </tr>
    <tr>
       <td class="esq">
          {$LANG.calltype}
       </td>
       <td class="esq">
          {html_radios name="call_type" checked=$call_type options=$OPCOES_CHAMADAS}
       </td>
    </tr>
    <tr>
       <td class="esq">
          {$LANG.viewtariff}
       </td>
       <td class="esq">
          {html_radios name="view_tarif" options=$OPCOES_YN selected=$view_tarif}
       </td>
    </tr>

    <tr class="esq">
       <td>
          {$LANG.selectandcompact}
       </td>
       <td class="esq">
         {html_radios name="view_compact" options=$OPCOES_YN selected=$view_compact }
       </td>
    </tr>

    <tr class="esq">
       <td>
          {$LANG.graphtype}
       </td>
       <td class="esq">
          {html_radios name="graph_type" checked=$graph_type options=$OPCOES_GRAFICOS}
       </td>
    </tr>

    <tr class="esq">
       <td>
          {$LANG.select_tipo_rel}
       </td>
       <td class="esq">
           <label>
             <input type="radio" value="analitico" checked name="rel_type" id="rel_type_ana" onclick="$('bt_grafico').show();" {if $rel_type == "analitico"} checked=checked {/if}>
                 {$LANG.analitico}
           </label>
           <label>
             <input type="radio"  value="sintetico" name="rel_type" id="rel_type_sin" onclick="$('bt_grafico').hide();" {if $rel_type == "sintetico"} checked=checked {/if}>
                 {$LANG.sintetico}
           </label>
       </td>
    </tr>

    <tr class="cen">
       <td colspan="3" height="40">
          <input type="hidden" id="acao" name="acao" value="">
          <input class="button" type="submit" name="submit" id="submit" value="{$LANG.viewreport}" OnClick="document.relatorio.acao.value='relatorio';document.getElementById('frescura').style.display='block'">
          <div class="buttonEnding"></div>
          &nbsp;&nbsp;&nbsp;

          <div id="bt_grafico" >
              <input class="button" type="submit" name="grafico" id="grafico" value="{$LANG.viewgraphic}"  OnClick="document.relatorio.acao.value='grafico';document.getElementById('frescura').style.display='block'">
              <div class="buttonEnding"></div>
              &nbsp;&nbsp;&nbsp;
              <input class="button" type="submit" name="csv" id="csv" value="{$LANG.viewcsv}"  OnClick="document.relatorio.acao.value='csv';document.getElementById('frescura').style.display='block'">
              <div class="buttonEnding"></div>
          </div>

          <div align="center" id="frescura" style="display : none;">
              <img src="../imagens/ajax-loader2.gif" width="256" height="24" /><br />
            {$LANG.processing}
          </div>
       </td>
    </tr>
 </form>
 </table>
 { include file="rodape.tpl }
 <script type="text/javascript">
   document.forms[0].elements[0].focus() ;

   //permitedados('dst','{$VINCULOS}')
   
 </script>
 <script  language="JavaScript" type="text/javascript" scr="../includes/javascript/functions.js"></script>    
