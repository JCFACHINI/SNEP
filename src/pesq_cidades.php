<?php
/**
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
 */

require_once("../includes/verifica.php");
require_once("../configs/config.php");
$uf = $_GET['uf'] ;
try {
    $sql_cid = "SELECT ars_cidade.name as mucicipio FROM ars_ddd INNER JOIN ars_cidade ON ars_ddd.cidade=ars_cidade.id where ars_ddd.estado = '$uf'";
    $row_cid = $db->query($sql_cid)->fetchAll();
} catch (Exception $e) {
    display_error($LANG['error'].$e->getMessage(),true) ;
}
unset($val);
$cidades = array(""=>$LANG['undef']);
foreach ($row_cid as $val)
    $cidades[$val['municipio']] = $val['municipio'] ;
asort($cidades) ;
$smarty->assign('CIDADES',$cidades);
