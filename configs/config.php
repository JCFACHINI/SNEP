<?php
/**
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
 */

// Adicionando caminho de libs ao include path para autoloader trabalhar:
set_include_path("../lib" . PATH_SEPARATOR  . get_include_path());

$config_file = "../includes/setup.conf";

require_once "Snep/Bootstrap/Web.php";
$bootstrap = new Snep_Bootstrap_Web($config_file);
$bootstrap->boot();

// Atualizando config
$config = Zend_Registry::get('config');

if($config->system->debug) {
    error_reporting(E_ALL);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
}
else {
    ini_set('display_startup_errors', 0);
    ini_set('display_errors', 0);
}

// ==================================================
//  FIM da Inicialização do ambiente Snep
// ==================================================

/*  Inicia Smarty */
define('PATH_SMARTY', '../includes/smarty');
require PATH_SMARTY.'/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c/';
$smarty->config_dir = '../configs/';
$smarty->cache_dir = '../cache/';
$smarty->plugin_dir = '../includes/smarty/plugins';
$smarty->agi_log = $config->system->path->log;

// Versao do Sistema
define('VERSAO',Zend_Registry::get('snep_version'));

// Ambiente de Desenvolvimento = True, senao, usar False
$smarty->compile_check = True;
$smarty->debugging = False;
$sis_nome = "SNEP" ;
$smarty->assign("i18n",Zend_Registry::get("i18n"));

Zend_Registry::set('smarty',$smarty);

// Caminhos dos Arquivos de Som - Link para /var/lib/asterisk/sounds/br
if (!defined ('SNEP_PATH_SOUNDS'))
    define('SNEP_PATH_SOUNDS','../sounds/pt_BR/');
// Caminhos dos Arquivos de Musicas em Espera - Link para /var/lib/asterisk/moh
if (!defined ('SNEP_PATH_MOH'))
    define('SNEP_PATH_MOH','../sounds/moh/');


// Carrega arquivo de variaveis de uso do sistema
$SETUP = parse_ini_file($config_file, true);
// Carrega Arquivo de Funcoes do Sistema
require_once "../includes/functions.php" ;

// Carrega arquivo de definicao de Locales
$LANG = Zend_Registry::get('lang');

$logo_cliente['name']   = "../imagens/logo_cliente.png";
$logo_cliente['width']  = "369" ;
$logo_cliente['height'] = "82" ;
$logo_snep['name']   = "../imagens/topbar.png";
$logo_snep['width']  = "610" ;
$logo_snep['height'] = "82" ;

// Arrrays de Uso Geral

// Estados do Brasil //
$uf_brasil = array("--"=>"--","AC"=>"AC","AL"=>"AL","AM"=>"AM","AP"=>"AP",
        "BA"=>"BA","CE"=>"CE","DF"=>"DF","ES"=>"ES","GO"=>"GO",
        "MA"=>"MA","MG"=>"MG","MS"=>"MS","MT"=>"MT","PA"=>"PA",
        "PE"=>"PE", "PI"=>"PI","PB"=>"PB","PR"=>"PR","RJ"=>"RJ",
        "RN"=>"RN", "RR"=>"RR","RO"=>"RO","RS"=>"RS","SC"=>"SC",
        "SE"=>"SE","SP"=>"SP","TO"=>"TO");

// Modos validos para Musica em espera
$musiconhold_modes = array( "files"      => $LANG['moh_files'] ,
        "quietmp3"   => $LANG['moh_quietmp3'],
        "mp3"        => $LANG['moh_mp3'],
        "mp3nb"      => $LANG['moh_mp3nb'],
        "quietmp3nb" => $LANG['moh_quietmp3nb'],
        "custom"     => $LANG['moh_custom']);

$khomp_signal = array(  "kesOk (sync)" => $LANG['ok'],
        "kesOk" => $LANG['ok'],
        "kes{SignalLost} (sync)" => $LANG['signallost'],
        "kes{SignalLost},sync" => $LANG['signallost'],
        "kes{SignalLost}" => $LANG['signallost'],
        "[ksigInactive]" => $LANG['inactive'],
        "NoLinksAvailable" => $LANG['msg_nolinksavail'],
        "ringing" => $LANG['ringing'],
        "ongoing"=> $LANG['ongoing'],
        "unused"=> $LANG['unused'],
        "dialing"=> $LANG['dialing'],
        "kcsFree"=> $LANG['kcsFree'],
        "kcsFail"=> $LANG['kcsFail'],
        "kcsIncoming"=> $LANG['kcsIncoming'],
        "kcsOutgoing"=> $LANG['kcsOutgoing'],
        "kecsFree"=> $LANG['kecsFree'],
        "kecsBusy"=> $LANG['kecsBusy'],
        "kecsOutgoing"=> $LANG['kecsOutgoing'],
        "kecsIncoming"=> $LANG['kecsIncoming'],
        "kecsLocked"=> $LANG['kecsLocked'],
        "kecs{SignalLost}" => $LANG['signallost'],
        "kecs{Busy}"=> $LANG['kecs{Busy}'],
        "kecs{Busy,Locked,RemoteLock}"=> $LANG['kecs{Busy,Locked,RemoteLock}'],
        "kecs{Busy,Outgoing}"=> $LANG['kecs{Busy,Outgoing}'],
        "kecs{Busy,Incoming}"=> $LANG['kecs{Busy,Incoming}'],
        "kgsmIdle"=> $LANG['kgsmIdle'],
        "kgsmCallInProgress"=> $LANG['kgsmCallInProgress'],
        "kgsmSMSInProgress"=> $LANG['kgsmSMSInProgress'],
        "kgsmNetworkError"=> $LANG['kgsmNetworkError'],
        "kfxsOnHook"=> $LANG['onhook'],
        "kfxsOffHook"=> $LANG['offhook'],
        "offhook" => $LANG['offhook'],
        "kfxsRinging"=> $LANG['kfxsRinging'],
        "kfxsFail"=> $LANG['kfxsFail'],
        "kfxsDisabled"=> $LANG['kfxsDisabled'],
        "kfxsEnable"=> $LANG['kfxsEnable'],
        "reserved"=> $LANG['reserved'],
        "ring"=> $LANG['ringing']
);


$khomp_signal_colors = array("kesOk" => "#18BF18",
        "kes{SignalLost}" => "#FF0000",
        "[ksigInactive]" => "#FFD202");

// Dias da Semana
$dias_semana = array("0"=>$LANG['sunday'],"1"=>$LANG['monday'],
        "2"=>$LANG['tuesday'],"3"=>$LANG['wednesday'],
        "4"=>$LANG['thursday'],"5"=>$LANG['friday'],
        "6"=>$LANG['saturday']) ;
// Links Khomp
$links_khomp_lista = array("0" => "B00", "1" => "B01", "3" => "B02", "4" => "B03",
        "5" => "B04", "6" => "B05", "7" => "B06", "8" => "B07");

// Tipos de Sons
$tipos_sons = array("AST" => $LANG['defaultast'],
        "URA" => $LANG['ura'],
        "MOH" => $LANG['menu_musiconhold']) ;

// Tipos de Chamadas
$tipos_chamadas = array("T" => $LANG['all'],
        "O" => $LANG['started'],
        "R" => $LANG['received']) ;

// Tipos de Chamadas para Relatorio
$tipos_chamadas_rel = array("T" => $LANG['all'],
        "S" => $LANG['started'],
        "E" => $LANG['received'],
        "O" => $LANG['others']) ;

// Tipos de Centros de Custos
$tipos_ccustos = array("E"=>$LANG['entrance'],
        "S"=>$LANG['exit'],
        "O"=>$LANG['others']) ;

// Tipos de Graficos
$tipos_graficos = array("B" => $LANG['bars'], 
        "L" => $LANG['lines']) ;

// Status de Filas
$status_filas = array("COMPLETECALLER" => $LANG['completecaller'],
        "COMPLETEAGENT" => $LANG['completeagent'],
        "RINGNOANSWER" => $LANG['exitwithtimeout'],
        "ABANDON" => $LANG['abandon'],
        "EXITWITHTIMEOUT" => $LANG['exitwithtimeout'],
        "CONNECT" => $LANG['connect'],
        "ENTERQUEUE" => $LANG['enterqueue'],
        "TRANSFER" => $LANG['transfer'],
        "AGENTCALLBACKLOGIN" => $LANG['agent_callback_loguin'],
        "AGENTCALLBACKLOGOFF" => $LANG['agent_callback_logoff'],
        "PAUSE" => $LANG['agent_pause'],
        "UNPAUSE" => $LANG['agent_unpause']);

// Status do Agente na Fila
$queue_status = array("paused"     => $LANG['inpause'],
        "Not in use" => $LANG['available'],
        "Busy"       => $LANG['busy'],
        "In use"     => $LANG['inuse'],
        "Unknown"    => $LANG['available'],
        "Ringing"    => $LANG['ringing'],
        "Unavailable"=> $LANG['unavailable']);

// Status do Ramal
$peer_status = array( "INVITE"    => $LANG['invite'],
        "Rx:INVITE" => $LANG['dialing'],
        "Init:INVITE"   => $LANG['calling'],
        "ACK"       => $LANG['busy'],
        "ONCALL"    => $LANG['busy'],
        "IDLE"       => $LANG['busy'],
        "BYE"       => $LANG['bye'],
        "REGISTER"  => $LANG['bye'],
        "OPTIONS"   => $LANG['unavailable'],
        "NOTIFY"    => $LANG['unavailable'],
        "CANCEL"    => $LANG['unavailable'],
        "UNKNOWN"   => $LANG['available'],
        "Follows Privilege" => $LANG['unknown']);

// Cor para agentes cfe Status                        
$status_color_bg = array("Em Pausa"   => "#FFF200",
        "Disponivel" => "#00A651",
        "Ocupado"    => "#ED1C24",
        "Em uso"     => "#ED1C24",
        "Chamando"   => "#F7931E",
        "Indisponivel"=> "#D1D3D4",
        "Discando"    => "#F7931E",
        "Desligando"  => "#FFF200",
        "Desconhecido"=> "#FFFFFF" );

$status_color_fonte = array("Em Pausa"   => "#231F20",
        "Disponivel" => "#FFFFFF",
        "Ocupado"    => "#FFFFFF",
        "Em uso"    => "#fff",
        "Chamando"   => "#231F20",
        "Indisponivel"=> "#58595B",
        "Discando"    => "#231F20",
        "Desligando"  => "#231F20",
        "Desconhecido"=> "#000000" );

// Cor para monitoramento dos links
$status_canais_khomp = array("Sem Uso"  => "#00A651",
        "Em Curso" => "#ED1C24",
        "Chamando" => "#ff9c00",
        "Discando" => "#ff9c00",
        "Reservado" => "#ff9c00",
        "Ocupado" => "#ED1C24" );
// Status sintetico    
$status_sintetico_khomp = array("unused"   => $LANG['unused'],
        "ongoing"  => $LANG['ongoing'],
        "ringing"  => $LANG['ringing'],
        "dialing"  => $LANG['dialing'],
        "reserved" => $LANG['reserved'],
        "offhook"  => $LANG['offhook'],
        "ring"     => $LANG['ringing'],
        "prering"  => $LANG['prering'],
        "none"     => $LANG['stnone'],
        "down"     => $LANG['stdown']);
// Tipos de insecure para troncos
$tipos_insecure = array(""       => $LANG['default'],
        "norm" => $LANG['normal'],
        "very"   => $LANG['very'] );

// Status das Ligacoes (campo disposition da tabela CDR)  echo "<meta http-equiv='refresh' cont
$tipos_disp = array("ANSWERED"=>$LANG['answer'],
        "FAILED"=>$LANG['failed'],
        "BUSY"=>$LANG['busy'],
        "NO ANSWER"=>$LANG['notanswered']) ;


$tipos_procura = array("1"=>$LANG['exact'], "2"=>$LANG['start'], 
        "3"=>$LANG['end'],   "4"=>$LANG['contain']) ;

$tipos_contas = array("f"=>$LANG['row'], "o"=>$LANG['other'],""=>$LANG['undef']) ;

$tipos_tf = array("1"=>$LANG['yes'],"0"=>$LANG['no']) ;

$tipos_time = array("M"=>$LANG['minutes'],"S"=>$LANG['seconds']) ;

$tipos_conference = array("M"=>"Meetme","C"=>"Conference") ;

$tipos_yn = array("yes"=>$LANG['yes'],"no"=>$LANG['no']) ;
$tipos_sn = array("S"=>$LANG['yes'],"N"=>$LANG['no']) ;

$tipos_fp = array("friend"=>$LANG['friend'],
        "peer"=>$LANG['peer'],
        "user"=>$LANG['user']) ;

$tipos_dtmf = array("rfc2833"=>"rfc2833","inband"=>"inband","info"=>"info");

$tipos_codecs = array(" "=>" ", "all"=>"all", "g729"=>"g729", "ilbc"=>"ilbc", "gsm"=>"gsm", "ulaw"=>"ulaw", "alaw"=>"alaw", "h264"=>"h264", "h263"=>"h263", "h263p"=>"h263p") ;

$codecs_default = array("cod1"=>"alaw", "cod2"=>"ulaw", "cod3"=>"gsm", "cod4"=>"g729", "cod5"=>"");



// Campos DEFAULT (nao são tratados via software na tabela peers
//---------------------------------------------------------------------
$def_campos_ramais = array("accountcode" => "''", "amaflags" => "''", "defaultip" => "''", "host" => "'dynamic'", "insecure" => "''", "language" => "'pt_BR'", "deny" => "''", "permit" => "''", "mask" => "''", "port" => "''", "restrictcid" => "''","rtptimeout" => "''", "rtpholdtimeout" => "''", "musiconhold" => "'cliente'", "regseconds" => 0, "ipaddr" => "''", "regexten" => "''", "cancallforward" => "'yes'", "setvar" => "''", "disallow" => "'all'", "canreinvite" => "'no'")  ;

$def_campos_troncos = array("accountcode" => "''", "amaflags" => "''", "defaultip" => "''", "language" => "'pt_BR'", "deny" => "''", "permit" => "''", "mask" => "''", "restrictcid" => "''","rtptimeout" => "''", "rtpholdtimeout" => "''", "musiconhold" => "'cliente'", "regseconds" => 0, "ipaddr" => "''", "regexten" => "''", "cancallforward" => "'yes'", "setvar" => "''", "disallow" => "'all'", "mailbox" => "''", "email" => "''", "vinculo" => "''", "incominglimit" => 0, "outgoinglimit" => 0, "usa_vc"=>"'no'","canreinvite" => "'no'", "mailbox"=>"''","fullcontact"=>"''","authenticate"=>"''", "subscribecontext"=>"''","incominglimit"=>0,"outgoinglimit"=>0, "usa_vc"=>"'no'", "email"=>"''", "vinculo"=>"''");


// Define Template CSS a ser usado
if (!defined ('CSS_TEMPL'))
    define('CSS_TEMPL',$SETUP['ambiente']['css_tpl']) ;
// Define Constantes de Ambiente
if (!defined('EMP_NOME'))
    define('EMP_NOME',$SETUP['ambiente']['emp_nome']) ;
if (!defined('SIS_NOME'))
    define('SIS_NOME',"SNEP") ;
// Conexao do banco de Dados
$db = Zend_Registry::get('db');

// Menu
$menu = Zend_Registry::get('menu');
$menu->setId("navmenu");

// Verifica se variavel acao esta definida e/ou foi passada
if (!isset($acao)) {
    if (isset($_POST['acao'] ) && $_POST['acao'] != "")
        $acao = $_POST['acao'];
    elseif (isset($_GET['acao'] ) && $_GET['acao'] != "")
        $acao = $_GET['acao'];
    else
        $acao = "" ;
}
