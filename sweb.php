<?php
  include('db.php');
    BaseDatos('localhost','root','','onpe');

  if ( isset( $_GET["id"] ) )  $id  = $_GET["id"];
  $parametros = explode("/",$id);
  $longitud = count( $parametros);

  if ( $parametros[0]  == "participacion" && $longitud > 1 ) getParticipacion();
  if ( $parametros[0]  == "actas" && $longitud > 1 ) getActas();

  function getParticipacion () {
    global $_SQL;
    global $parametros;
    global $longitud;

    $bDPD = $parametros[1] == "Nacional" || $parametros[1] == "Extranjero";
    
    if ( $longitud == 2 ) 
      $_SQL = $parametros[1] == "Nacional" ? "call sp_getVotos(1,25)" : ( $parametros[1] == "Extranjero" ? "call sp_getVotos(26,30)" : "");
    elseif ( $longitud == 3 ) $_SQL = $bDPD  ? "call sp_getVotosDepartamento('$parametros[2]')" : "";
    elseif ( $longitud == 4 ) $_SQL = $bDPD  && isDPD( $parametros[2], "Departamento" ) ? "call sp_getVotosProvincia('$parametros[3]')" : "";

    if ( $_SQL != "" ) getRegistros();
  }

  function getActas(){
    global $_SQL;
    global $parametros;
    global $longitud;

    if ( $longitud == 2 && $parametros[1] == "ubigeo" ) $_SQL = "call sp_getDepartamentos(1,25)";
    if ( $longitud == 3 && $parametros[1] == "numero" ) $_SQL = "call sp_getGruposVotacion('$parametros[2]')";
    if ( $longitud == 3 && $parametros[1] == "ubigeo" ) $_SQL = "call sp_getProvinciasbyDepartamento('$parametros[2]')";
    if ( $longitud == 4 && $parametros[1] == "ubigeo" ) $_SQL = "call sp_getDistritosbyProvincia('$parametros[3]')";

    if ( $_SQL != "" ) getRegistros();
  }

  function isDPD( $detalle, $DPD ) {
    global $_SQL;

    if ( $DPD == "Departamento" ) $_SQL = "call sp_isDepartamento('$detalle')";
    else if ( $DPD == "Provincia" ) $_SQL = "call sp_isProvincia('$detalle')";
    return getCampo();
  }
  
?>