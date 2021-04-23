<?php
// AUTO LOAD DE CLASSES
function __autoload($Class) {
    $cDir = array('../class', '../../class', 'class'); //cDir(configuração de diretório)
    $iDir = null; //iDir(include diretório) para vefiricar se inclusão ocorreu        
    
    foreach($cDir as $dirName){
        //verifica se arquivo existe e se não é um diretório
        if(!$iDir && file_exists("{$dirName}/{$Class}Class.php") && !is_dir("{$dirName}/{$Class}Class.php")){
            include_once("{$dirName}/{$Class}Class.php");
            $iDir = true;
        }
    }
    
    if(!$iDir){
        die("Não foi possível incluir {$Class}Class.php");
    }
}

/*
 * LINKS EM DIRETORIOS DIFERENTES
 * EX: sistema/form_usuario.php
 */
$dirDefaul = explode("/", $_SERVER['REQUEST_URI']);
$totCaminho = count($dirDefaul);

if($totCaminho == 4){
    $caminho = "../";
    $caminho_src = "../";
}else{
    $caminho = "";
    $caminho_src = "";
}

if($_SERVER['SERVER_NAME'] == "localhost"){    
    if($totCaminho == 5){
        $caminho = "";
        $caminho_src = "";
    }else{
        $caminho = "../";
        $caminho_src = "../";
    }
}

// FUNÇÃO PHP GLOBAL
include($caminho.'wfunction.php');
include($caminho.'class/GlobalsClass.php');
$global = new Globals();

// BUSCANDO INFO DE CLIENTES PARA CONEXÃO
$cliente = new Cliente();
$cliente->setInfoCliente();

// CONFIGURAÇÕES GERAIS
header('Content-type: text/html; charset=UTF-8');

date_default_timezone_set('America/Sao_Paulo');
session_start();

// CONEXÃO AO BANCO DE DADOS
Conn(HOST, USER, PASS, DBSA);
mysql_query("SET NAMES utf8");

//VERIFICA SE DISPOSITIVO É MOBILE OU PC
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");

$mobile = false;
$pc = false;

if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true){
    $mobile = true;
}else{
    $pc = true;
}

//CONFIGURAÇÕES PARA DATATABLE
if($mobile){
    $pagingType = 'full';    
    $previous = '<i class="fa fa-angle-left"></i>';				
    $next = '<i class="fa fa-angle-right"></i>';
    $first = '<i class="fa fa-angle-double-left"></i>';
    $last = '<i class="fa fa-angle-double-right"></i>';
    $lengthMenu = '_MENU_';
    $search = '';
    $searchPlaceholder = 'Buscar';
    $buttonTxtPrint = '<i class="fa fa-print"></i>';
    $buttonTxtPdf = '<i class="fa fa-file-pdf-o"></i>';
    $buttonTxtExcel = '<i class="fa fa-file-excel-o"></i>';
}else{
    $pagingType = 'full_numbers';
    $previous = 'Anterior';
    $next = 'Próximo';
    $first = 'Primeiro';
    $last = 'Último';
    $lengthMenu = 'Mostrar _MENU_ entradas';
    $search = 'Buscar';
    $searchPlaceholder = '';
    $buttonTxtPrint = '<i class="fa fa-print"></i>&nbsp; Imprimir';
    $buttonTxtPdf = '<i class="fa fa-file-pdf-o"></i>&nbsp; Salvar em PDF';
    $buttonTxtExcel = '<i class="fa fa-file-excel-o"></i>&nbsp; Salvar em Excel';
}

/*
 * CRIANDO UM ARRAY PARA ENVIAR A CONFIGURAÇÃO
 * REFERENTE A DATATABLE, PARA O GLOBAS.JS
 * PARA FICAR UMA ESTRUTURA REUTILIZÁVEL
 */
$config_dataTable = array(
    "pagingType" => $pagingType,
    "previous" => $previous,
    "next" => $next,
    "first" => $first,
    "last" => $last,
    "lengthMenu" => $lengthMenu,
    "search" => $search,
    "searchPlaceholder" => $searchPlaceholder,
    "buttonTxtPrint" => $buttonTxtPrint,
    "buttonTxtPdf" => $buttonTxtPdf,
    "buttonTxtExcel" => $buttonTxtExcel
);

/*
 * BUSCANDO SE USUARIO LOGADO
 * ESTA COM O SIDEBAR ATIVO OU NÃO
 */
$db_usu = carregaUsuario();

$sidebar_fixed = "";

if($db_usu['sidebar'] == 1){
    $sidebar_fixed = "page-sidebar-minified";
}
?>