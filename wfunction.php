<?php

/*
 * METODO PARA FAZER CONEXÃO MANUAL
 */
function Conn($server, $user, $pass, $bd) {
    mysql_connect($server, $user, $pass) or die ('ERRO ao conectar com o banco de dados');        
    mysql_select_db($bd) or die('ERRO na seleção do banco de dados');                
}

function retornaPalavras($string) {
    $palavra = explode(" ", $string);
    
    $palavras_not = array("de", "dos", "da", "e", "do", "DE", "DOS", "DA", "E", "DO");
    
    if(in_array($palavra[1], $palavras_not)){        
        $palavra_2 = $palavra[2];
    }else{        
        $palavra_2 = $palavra[1];
    }
    
    $palavra_f = "{$palavra[0]} {$palavra_2}";
    
    return $palavra_f;
}

function removeMascara($string, $tipo = 'int') {
    if($tipo == 'int'){
        $string = preg_replace("/\D+/", "", $string); // remove qualquer caracter não numérico
    }
    
    return $string;
}

//RETORNA EM TIMESTAMP ULTIMA ALTERACAO DO ARQUIVO
function AlteracaoFile($file, $dir = null) {
    if(!is_null($dir)){
        $diretorio = $dir;
    }else{
        $diretorio = "../resources/js/";
    }
    
    echo filemtime($diretorio.$file);
}

function getSexo($id){
    switch ($id) {
        case 1:
            $re = "Masculino";
            break;
        case 2:
            $re = "Solteiro";
            break;        
    }
    
    return $re;
}

function getTipoCasamento($id){
    switch ($id) {
        case 1:
            $re = "Civil";
            break;
        case 2:
            $re = "Religioso";
            break;        
        case 3:
            $re = "Civil e Religioso";
            break;        
    }
    
    return $re;
}

function getEstadoCivil($id){
    switch ($id) {
        case 1:
            $re = "Solteiro";
            break;
        case 2:
            $re = "Casado";
            break;
        case 3:
            $re = "Divorciado";
            break;
        case 4:
            $re = "Viúvo";
            break;
        case 5:
            $re = "Separado";
            break;
        case 6:
            $re = "Companheiro";
            break;
    }
    
    return $re;
}

function getEscolaridade($id){
    switch ($id) {
        case 1:
            $re = "Analfabeto";
            break;
        case 2:
            $re = "1º grau incompleto";
            break;
        case 3:
            $re = "1º grau completo";
            break;
        case 4:
            $re = "2º grau incompleto";
            break;
        case 5:
            $re = "2º grau completo";
            break;
        case 6:
            $re = "Superior incompleto";
            break;
        case 7:
            $re = "Superior completo";
            break;
        case 8:
            $re = "Mestre";
            break;
        case 9:
            $re = "Doutor";
            break;
    }
    
    return $re;
}

function getStatus($id, $tipo){
    
    //FINANCEIRO(ENTRADA)
    if($tipo == 1){
        switch ($id) {
            case 0:
                $re = "<span class='label label-danger'>EXCLUÍDA</span>";
                break;
            case 1:
                $re = "<span class='label label-success'>RECEBIDA</span>";
                break;
            case 2:
                $re = "<span class='label label-warning'>A RECEBER</span>";
                break;
        }
    }elseif($tipo == 2){
        switch ($id) {
            case 0:
                $re = "<span class='label label-danger'>EXCLUÍDA</span>";
                break;
            case 1:
                $re = "<span class='label label-success'>PAGA</span>";
                break;
            case 2:
                $re = "<span class='label label-warning'>A PAGAR</span>";
                break;
        }
    }
    
    return $re;
}

function getTipoPgt($id){
    switch ($id) {
        case 1:
            $re = "Dinheiro";
            break;
        case 2:
            $re = "Cheque";
            break;
        case 3:
            $re = "Transf. bancária";
            break;
        case 4:
            $re = "Cartão Crédito";
            break;
        case 5:
            $re = "Cartão Débito";
            break;
    }
    
    return $re;
}

/**
 * Função para retornar registros na tabela
 * @param string $tabela
 * @param array,string $campos
 * @param array,string $condicao
 * @param array,string $order
 * @param string $limit
 * @param string $return "array" para retornar um array, !="array" para retornar o result mysql
 * @param string $debug
 * @param array,string $groupBy array ou string com o(s) nome(s) do campo que será agrupado
 * @return array,resultMySql
 */
function montaQuery($tabela, $campos = "*", $condicao = null, $order = null, $limit = null, $return = "array", $debug = false, $groupBy = null) {
    $_where = "";
    $_order = "";
    $_limit = "";
    $_groupBy = "";
    if (is_array($campos)) {
        $_camp = implode(",", $campos);
    } else {
        $_camp = $campos;
    }

    if ($condicao !== null) {
        $_where = " WHERE ";
        if (is_array($condicao)) {
            foreach ($condicao as $k => $val) {
                $_where .= " " . $k . " = '" . $val . "' AND";
            }
            $_where = substr($_where, 0, -3);
        } else {
            $_where .= $condicao;
        }
    }

    if ($order !== null) {
        $_order = " ORDER BY ";
        if (is_array($order)) {
            foreach ($order as $k => $val) {
                $_order .= $k . " " . $val . ",";
            }
            $_order = substr($_order, 0, -1);
        } else {
            $_order .= $order;
        }
    }

    if ($limit !== null) {
        $_limit = " LIMIT {$limit}";
    }
    
    if($groupBy !== null) {
        if(is_array($groupBy)){
            $_groupBy = " GROUP BY ".implode(",",$groupBy)."";
        }else{
            $_groupBy = " GROUP BY {$groupBy}";
        }
    }
    
    $query = "SELECT {$_camp} FROM {$tabela}{$_where}{$_groupBy}{$_order}{$_limit}";
    
    $result = mysql_query($query);
    
    if ($debug) {
        echo $query;
    }
    
    if ($return == "array") {
        $return = array();
        $count = 1;
        
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                foreach ($row as $k => $val) {
                    $return[$count][$k] = $val;
                }
                $count++;
            }
        }
    } else {
        $return = $result;
    }

    return $return;
}

/**
 * Função para retornar apenas um registro na tabela, ela executa a funcao montaQuery e modifica o resultado
 * @param string $tabela
 * @param array,string $campos
 * @param array,string $condicao
 * @param array,string $order
 * @param string $limit
 * @param string $debug
 * @return array
 */
function montaQueryFirst($tabela, $campos = "*", $condicao = null, $order = null, $limit = null, $debug = false) {
    $rs = montaQuery($tabela, $campos, $condicao, $order, $limit, "array", $debug);
    return current($rs);
}

function execQuery($sql, $return = "array") {
    $result = mysql_query($sql);

    if ($return == "array") {
        $return = array();
        $count = 1;

        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                foreach ($row as $k => $val) {
                    $return[$count][$k] = $val;
                }
                $count++;
            }
        }
    } else {
        $return = $result;
    }

    return $return;
}

/**
 * 
 * @param type $tabela
 * @param type $campos
 * @param type $valores
 * @param type $debug
 * @return type
 */
function sqlInsert($tabela, $campos, $valores, $debug = false) {
    $palavrasReservadas = array("NOW()"); //depois montar uma forma de retirar as aspas simples das palavras reservados do mysql
    $_campos = "";
    $_valores = "";
    $qr = "INSERT INTO {$tabela} ";

    if (is_array($campos)) {
        $_campos = implode(",", $campos);
    } else {
        $_campos = $campos;
    }

    if (is_array($valores)) {
        //VERIFICANDO SE É UMA MATRIZ
        if (count($valores) == count($valores, COUNT_RECURSIVE)) {
            $_valores = "('" . implode("','", $valores) . "')";
        } else {
            foreach ($valores as $val) {
                $_valores .= "('" . implode("','", $val) . "'),";
            }
            $_valores = substr($_valores, 0, -1);
        }
    } else {
        $_valores = "(" . $valores . ")";
    }

    $qr .= "($_campos) VALUES $_valores";
    if ($debug) {
        echo $qr . "<br/>";
    } else {
        mysql_query($qr) or die("Erro na query:<br/>" . $qr . "<br/><br/>Descrição:<br/>" . mysql_error());
    }
    return mysql_insert_id();
}

/**
 * 
 * @param type $tabela
 * @param type $campos
 * @param type $condicao
 * @param type $debug
 * @return type
 */
function sqlUpdate($tabela, $campos, $condicao, $debug = false) {
    $_campos = "";
    $_condicao = "";
    $qr = "UPDATE {$tabela} SET ";

    if (is_array($campos)) {
        foreach ($campos as $k => $val) {
            $_campos .= " {$k}='{$val}',";
        }
        $_campos = substr($_campos, 0, -1);
    } else {
        $_campos = $campos;
    }

    if (is_array($condicao)) {
        foreach ($condicao as $k => $val) {
            $_condicao .= " {$k}={$val} AND ";
        }
        $_condicao = substr($_condicao, 0, -4);
    } else {
        $_condicao = $condicao;
    }

    $qr .= $_campos . " WHERE " . $_condicao;

    if ($debug) {
        echo $qr . "<br/>";
    } else {
        return mysql_query($qr) or die("Erro na query:<br/>" . $qr . "<br/><br/>Descrição:<br/>" . mysql_error());
    }
}

function sqlDelete($tabela, $condicao, $debug = false){
    $_condicao = "";
    $qr = "DELETE FROM {$tabela} ";
    
    if (is_array($condicao)) {
        foreach ($condicao as $k => $val) {
            $_condicao .= " {$k}={$val} AND ";
        }
        $_condicao = substr($_condicao, 0, -4);
    } else {
        $_condicao = $condicao;
    }

    $qr .= " WHERE " . $_condicao;

    if ($debug) {
        echo $qr . "<br/>";
    } else {
        return mysql_query($qr) or die("Erro na query:<br/>" . $qr . "<br/><br/>Descrição:<br/>" . mysql_error());
    }
}

function montaDataToSelect() {
    
}

function montaSelect($options, $value, $atributos) {
    $html = "<select ";

    if (is_array($atributos)) {
        foreach ($atributos as $key => $val) {
            $html .= $key . "=\"" . $val . "\" ";
        }
    } else {
        $html .= $atributos;
    }
    $html .= ">";


    if (is_array($options)) {
        foreach ($options as $key => $val) {
            if (!empty($value) && $value == $key) {
                $selected = " selected=\"selected\"";
            } else {
                $selected = "";
            }
            $html .= "<option value=\"" . $key . "\"$selected>" . $val . "</option>";
        }
    }
    $html .= "</select>";
    return $html;
}

/**
 * Retorna o dia da semana 1 (para Segunda) até 7 (para Domingo) date('N')
 * @param int $id (1-7)
 * @return string
 */
function diasSemanaArray($id) {
    //date(N) 1 (para Segunda) até 7 (para Domingo)
    $id = (int) $id;
    $diass = array("0" => "« Selecione »", "1" => "Segunda-feira", "2" => "Terça-feira", "3" => "Quarta-feira", "4" => "Quinta-feira", "5" => "Sexta-feira", "6" => "Sábado",
        "7" => "Domingo");
    if (!empty($id))
        return $diass[$id];
    else
        return $diass;
}

function mesesArray($id=null,$key='-1',$todos=null) {
    $id = (int) $id;
    
    if($todos != null){
        $opcao = $todos;
    }else{
        $opcao = "« Selecione o mês »";
    }
    
    $meses = array($key => $opcao, "1" => "Janeiro", "2" => "Fevereiro", "3" => "Março", "4" => "Abril", "5" => "Maio", "6" => "Junho",
        "7" => "Julho", "8" => "Agosto", "9" => "Setembro", "10" => "Outubro", "11" => "Novembro", "12" => "Dezembro");
    if (!empty($id))
        return $meses[$id];
    else
        return $meses;
}

function anosArray($inicio=null, $fim=null, $default = null) {
    if ($inicio == null)
        $inicio = date("Y") - 4;
    if ($fim == null)
        $fim = date("Y") + 1;
    $anos = array();

    if ($default !== null) {
        $anos = $default;
    }

    for ($i = $inicio; $i <= $fim; $i++) {
        $anos[$i] = $i;
    }
    return $anos;
}

function converteData($data, $formato = null) {
    if ($formato == null)
        $formato = "Y-m-d";
    return date($formato, strtotime(str_replace("/", "-", $data)));
}

function convertImage($imagem, $tipo) {
    if ($tipo == "gif") {
        $img = imagecreatefromgif($imagem . "." . $tipo);
    } elseif ($tipo == "png") {
        $img = imagecreatefrompng($imagem . "." . $tipo);
    }

    $w = imagesx($img);
    $h = imagesy($img);
    $trans = imagecolortransparent($img);
    if ($trans >= 0) {
        $rgb = imagecolorsforindex($img, $trans);
        $oldimg = $img;
        $img = imagecreatetruecolor($w, $h);
        $color = imagecolorallocate($img, $rgb['red'], $rgb['green'], $rgb['blue']);
        imagefilledrectangle($img, 0, 0, $w, $h, $color);
        imagecopy($img, $oldimg, 0, 0, 0, 0, $w, $h);
    }

    if (imagejpeg($img, $imagem . ".jpg")) {
        return $imagem . ".jpg";
    } else {
        return false;
    }
}

function validate($object) {
    if (!isset($object))
        return false;
    if (empty($object))
        return false;

    return true;
}

function carregaUsuario() {
    $result = montaQuery("usuario AS A
            LEFT JOIN file_extensao AS B ON(A.id_extensao = B.id_extensao)", 
            "A.*, B.nome AS nome_extensao", 
            "A.id_usuario = '{$_SESSION['id_usuario']}'", null, null, null, false);
    $row = mysql_fetch_assoc($result);
    return $row;
}

function acentoMaiusculo($texto) {
    $array1 = array('à','á','â','ã','é','è','ê','í','ì','î','ó','ò','ô','õ','ú','ù','û','ä','ë','ï','ö','ü','ç');
    $array2 = array('À','Á','Â','Ã','É','È','Ê','Í','Ì','Î','Ó','Ò','Ô','Õ','Ú','Ù','Û','Ä','Ë','Ï','Ö','Ü','Ç');
    for ($x = 0; $x < count($array2); $x++) {
        $texto = str_replace($array1[$x],$array2[$x],$texto);
    }
    return strtoupper($texto);
}

///USADOS NO SEFIP, CAGED, DIRF, RAIS
function RemoveCaracteres($variavel) {
    $variavel = str_replace('(', "", $variavel);
    $variavel = str_replace(')', "", $variavel);
    $variavel = str_replace('-', '', $variavel);
    $variavel = str_replace('/', '', $variavel);
    $variavel = str_replace(":", "", $variavel);
    $variavel = str_replace(",", " ", $variavel);
    $variavel = str_replace('.', '', $variavel);
    $variavel = str_replace(";", "", $variavel);
    $variavel = str_replace("\"", "", $variavel);
    $variavel = str_replace("\'", "", $variavel);   
    
    return $variavel;
}

//USADO PARA REMOVER QUALQUER TIPO DE CARACTER ESPECIAL
function RemoveCaracteresGeral($variavel) {
    $variavel = str_replace('(', "", $variavel);
    $variavel = str_replace(')', "", $variavel);
    $variavel = str_replace('-', '', $variavel);
    $variavel = str_replace('_', '', $variavel);
    $variavel = str_replace('/', '', $variavel);
    $variavel = str_replace(":", "", $variavel);
    $variavel = str_replace(",", " ", $variavel);
    $variavel = str_replace('.', '', $variavel);
    $variavel = str_replace(";", "", $variavel);
    $variavel = str_replace("\"", "", $variavel);
    $variavel = str_replace("\'", "", $variavel);
    $variavel = str_replace("!", " ", $variavel);
    $variavel = str_replace("@", " ", $variavel);
    $variavel = str_replace("#", " ", $variavel);
    $variavel = str_replace("$", " ", $variavel);
    $variavel = str_replace("%", " ", $variavel);
    $variavel = str_replace("*", " ", $variavel);
    $variavel = str_replace("+", " ", $variavel);
    $variavel = str_replace("?", " ", $variavel);
    $variavel = str_replace("=", " ", $variavel);
    return $variavel;
}

function normalizaNometoFile($variavel){
    $variavel = strtoupper($variavel);
    if(strlen($variavel) > 200){
        $variavel = substr($variavel, 0, 200);
        $variavel = $variavel[0];
    }
    $nomearquivo = preg_replace("/ /","_",$variavel);
    $nomearquivo = preg_replace("/[\/]/","",$nomearquivo);
    $nomearquivo = preg_replace("/[ÁÀÂÃ]/i","A",$nomearquivo);
    $nomearquivo = preg_replace("/[áàâãª]/i","a",$nomearquivo);
    $nomearquivo = preg_replace("/[ÉÈÊ]/i","E",$nomearquivo);
    $nomearquivo = preg_replace("/[éèê]/i","e",$nomearquivo);
    $nomearquivo = preg_replace("/[ÍÌÎ]/i","I",$nomearquivo);
    $nomearquivo = preg_replace("/[íìî]/i","i",$nomearquivo);
    $nomearquivo = preg_replace("/[ÓÒÔÕ]/i","O",$nomearquivo);
    $nomearquivo = preg_replace("/[óòôõº]/i","o",$nomearquivo);
    $nomearquivo = preg_replace("/[ÚÙÛ]/i","U",$nomearquivo);
    $nomearquivo = preg_replace("/[úùû]/i","u",$nomearquivo);
    $nomearquivo = str_replace("Ç","C",$nomearquivo);
    $nomearquivo = str_replace("ç","c",$nomearquivo); 
    
    return $nomearquivo;
}

function RemoveLetras($variavel) {
    $letras = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    foreach ($letras as $letra) {
        $variavel = str_replace($letra, '', $variavel);
    }
    return $variavel;
}

function RemoveEspacos($variavel) {
    $variavel = trim($variavel);
    return $variavel;
}

function removeTodosEspacos($variavel) {
    $result = str_replace(" ", "", $variavel);
    
    return $result;
}

function Valor($variavel) {
    $variavel = str_replace('.', '', $variavel);
    return $variavel;
}

function valorBrtoUs($variavel) {
    $variavel = str_replace('.', '', $variavel);
    $variavel = str_replace(',', '.', $variavel);
    return $variavel;
}

function RemoveAcentos($str, $enc = "iso-8859-1") {
    $acentos = array(
        'A' => '/&Agrave;|&Aacute;|&Acirc;|&Atilde;|&Auml;|&Aring;/',
        'a' => '/&agrave;|&aacute;|&acirc;|&atilde;|&auml;|&aring;/',
        'C' => '/&Ccedil;/',
        'c' => '/&ccedil;/',
        'E' => '/&Egrave;|&Eacute;|&Ecirc;|&Euml;/',
        'e' => '/&egrave;|&eacute;|&ecirc;|&euml;/',
        'I' => '/&Igrave;|&Iacute;|&Icirc;|&Iuml;/',
        'i' => '/&igrave;|&iacute;|&icirc;|&iuml;/',
        'N' => '/&Ntilde;/',
        'n' => '/&ntilde;/',
        'O' => '/&Ograve;|&Oacute;|&Ocirc;|&Otilde;|&Ouml;/',
        'o' => '/&ograve;|&oacute;|&ocirc;|&otilde;|&ouml;/',
        'U' => '/&Ugrave;|&Uacute;|&Ucirc;|&Uuml;/',
        'u' => '/&ugrave;|&uacute;|&ucirc;|&uuml;/',
        'Y' => '/&Yacute;/',
        'y' => '/&yacute;|&yuml;/',
        'a.' => '/&ordf;/',
        'o.' => '/&ordm;/'
    );
    return preg_replace($acentos, array_keys($acentos), htmlentities($str, ENT_NOQUOTES, $enc));
}

function validaData($data, $formato = "Y-m-d") {
    $ok = true;

    if (empty($data))
        $ok = false;

    if ($formato == "Y-m-d" && $data == "0000-00-00")
        $ok = false;

    if ($formato == "d-m-Y" && $data == "00-00-0000")
        $ok = false;

    if ($formato == "d/m/Y" && $data == "00/00/0000")
        $ok = false;

    return $ok;
}

function getUnidades($user = null) {
    
    if($user === null){
        $usuario = carregaUsuario();
    }else{
        $usuario['id_usuario'] = $user;
    }
    
    $sql = montaQuery("unidade_assoc AS A
        LEFT JOIN unidade AS B ON (A.id_unidade = B.id_unidade)", 
        "B.id_unidade, B.unidade", "id_usuario = {$usuario['id_usuario']}", null, null, null, false);                
    
    return $sql;
}

function getMasters($user=null){
    
    if($user===null){
        $usuario = carregaUsuario();
    }else{
        $usuario['id_funcionario'] = $user;
    }
    
    $qrMaster = "SELECT A.id_master,B.nome
                FROM funcionario_regiao_assoc AS A
                INNER JOIN master AS B ON (A.id_master = B.id_master)
                WHERE id_funcionario = {$usuario['id_funcionario']} AND B.status = 1 GROUP BY A.id_master";
    $rsMaster = mysql_query($qrMaster);
    
    $masters = array("-1" => "« Selecione »");
    while ($row = mysql_fetch_assoc($rsMaster)) {
        $masters[$row['id_master']] = $row['id_master']." - ".$row['nome'];
    }

    return $masters;
}

function getProjetos($regiao) {
    $sql = mysql_query("SELECT *
                    FROM projeto
                    WHERE id_regiao = {$regiao} AND status_reg = '1'");
    $projetos = array("-1" => "« Selecione »");
    while ($rst = mysql_fetch_assoc($sql)) {
        $projetos[$rst['id_projeto']] = $rst['id_projeto'] . " - " . $rst['nome'];
    }
    return $projetos;
}

function getFuncionarios() {
    $sql = mysql_query("SELECT *
                        FROM funcionario
                        WHERE status_reg = '1'
                        ORDER BY nome1");
    $func = array("-1" => "« Selecione »");
    while ($rst = mysql_fetch_assoc($sql)) {
        $func[$rst['id_funcionario']] = $rst['nome1'];
    }
    return $func;
}

function getProjetosRegiao($id_regiao) {
    $sql = "SELECT *
            FROM projeto            
            WHERE id_regiao = '{$id_regiao}' ORDER BY nome";
    $proj = mysql_query($sql) or die(mysql_error());
    return $proj;
}

function diferencaDias($data_inicial, $data_final, $delimiter="/"){
   $data_inicial = explode($delimiter, $data_inicial);
   $data_final = explode($delimiter, $data_final);
   $time_inicial = mktime(0, 0, 0, $data_inicial[1], $data_inicial[0], $data_inicial[2]);
   $time_final = mktime(0, 0, 0, $data_final[1], $data_final[0], $data_final[2]);
   $diferenca = ($time_final - $time_inicial);
   $diferenca_dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias  
   $diferenca_dias = $diferenca_dias + 1;
   return $diferenca_dias;
}
function somarDias($quantidade_dia = 0, $data =NULL , $delimiter="-",$formato_timestamp=TRUE){
    $data = ($data!=NULL) ? explode($delimiter, $data) : array(date('Y'), date('m'), date('d') ) ;
    if(!$formato_timestamp && !empty($data)){
        $data = array_reverse($data);
    } 
    return date('d/m/Y',mktime(0,0,0,$data[1],$data[2]+$quantidade_dia,$data[0]));
}

function validaCPF($cpf) {
    // Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

    // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
        return false;
    } else {   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
 
            $d = ((10 * $d) % 11) % 10;
 
            if ($cpf{$c} != $d) {
                return false;
            }
        }
 
        return true;
    }
}

// Funcao para validar PIS
function validaPIS($pis){
    
    //remove todos os caracteres deixando apenas valores numéricos
    $pis = preg_replace('/[^0-9]+/', '', $pis);

    //se a quantidade de caracteres numéricos  for diferente de 11 é inválido
    if (strlen($pis) <> 11)
        return false;

    //inicia uma variável que será responsável por armazenar o cálculo da somatória dos números individuais
    $digito = 0;
    
    for($i = 0, $x=3; $i<10; $i++, $x--){
        
    //Verifica se $x for menor que 2, seu valor será 9, senão será $x
    $x = ($x < 2) ? 9 : $x;
    
    //Realiza a soma dos números individuais vezes o fator multiplicador
    $digito += $pis[$i]*$x;
    }

    /**
    * Verificamos se o módulo do resultado por 11 é menor que 2, se for o valor será 0
    * Caso não for, pegar o valor 11 e diminuir com o módulo do resultado da somatória.
    */

    $calculo = (($digito%11) < 2) ? 0 : 11-($digito%11);
    //Se o valor da variavel cálculo for diferente do último digito, ele será inválido, senão verdadeiro
    return ($calculo <> $pis[10]) ? false :true;
}


function formataMoeda($moeda_bd,$cifrao=null) {
    $moeda = number_format($moeda_bd, 2, ',', '.');
    if($cifrao == 1){
        $rs = "";
    }else{
        $rs = "R$ ";
    }
    return $rs . $moeda;
}

function selected( $value, $selected ){
    return $value == $selected ? ' selected="selected"' : '';
}

function checked( $value, $checked ){
    return $value == $checked ? ' checked="checked"' : '';
}

/*MÉTODO PARA RETORNAR O PROJETO*/
function todosProjetos(){
    $sql = "SELECT * FROM projeto";
    $projetos  = mysql_query($sql);
    return $projetos;
}

function projetosId($id_projeto){
    $sql = "SELECT * FROM projeto WHERE id_projeto = '{$id_projeto}'";
    $projetos  = mysql_query($sql);
    $row = mysql_fetch_assoc($projetos);
    return $row;
}

function masterId($id_master){
    $sql = "SELECT * FROM master WHERE id_master = '{$id_master}'";
    $master  = mysql_query($sql);
    $row = mysql_fetch_assoc($master);
    return $row;
}

function selectUF($value, $atributos) {
    /*
     * função para criar select com estados brasileiros
     * Autor: Leonardo
     */
    $options = array(
        '-1' => '-- Selecione --',
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        "AP" => 'Amapá',
        "AM" => 'Amazonas',
        "BA" => 'Bahia',
        "CE" => 'Ceará',
        "DF" => 'Distrito Federal',
        "ES" => 'Espirito Santo',
        "GO" => 'Goiás',
        "MA" => 'Maranhão',
        "MT" => 'Mato Grosso',
        "MS" => 'Mato Grosso do Sul',
        "MG" => 'Minas Gerais',
        "PA" => 'Pará',
        "PB" => 'Paraíba',
        "PR" => 'Paraná',
        "PE" => 'Pernambuco',
        "PI" => 'Piauí',
        "RJ" => 'Rio de Janeiro',
        "RN" => 'Rio Grande do Norte',
        "RS" => 'Rio Grande do Sul',
        "RO" => 'Rondônia',
        "RR" => 'Roraima',
        "SC" => 'Santa Catarina',
        "SP" => 'São Paulo',
        "SE" => 'Sergipe',
        "TO" => 'Tocantins'
    );
    return montaSelect($options, $value, $atributos);
}

function valor_extenso($valor = 0, $maiusculas = false) {
    // verifica se tem virgula decimal
    if (strpos($valor, ",") > 0) {
        // retira o ponto de milhar, se tiver
        $valor = str_replace(".", "", $valor);

        // troca a virgula decimal por ponto decimal
        $valor = str_replace(",", ".", $valor);
    }
    $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões",
        "quatrilhões");

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos",
        "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",
        "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",
        "dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis",
        "sete", "oito", "nove");
    $z = 0;
    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);
    $cont = count($inteiro);
    for ($i = 0; $i < $cont; $i++)
        for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++)
            $inteiro[$i] = "0" . $inteiro[$i];

    $fim = $cont - ($inteiro[$cont - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < $cont; $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                $ru) ? " e " : "") . $ru;
        $t = $cont - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000")
            $z++; elseif ($z > 0)
            $z--;
        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
            $r .= (($z > 1) ? " de " : "") . $plural[$t];
        if ($r)
            $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                    ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    if (!$maiusculas) {
        return($rt ? $rt : "zero");
    } elseif ($maiusculas == "2") {
        return (strtoupper($rt) ? strtoupper($rt) : "Zero");
    } else {
        return (ucwords($rt) ? ucwords($rt) : "Zero");
    }
}

function montaCriteriaSimples($var, $campo){
    $ret = "";
    if(isset($var)){
        if(!is_array($var)){
            $ret = $campo." ='{$var}'";
        }else{
            $ret = $campo." IN ('".  implode("','", $var)."')";
        }
    }else{
        return false;
    }
    return $ret;
}

function validatePost($variavel,$tipo = null){
    $re = "";
    if ($tipo !== null) {
        switch ($tipo) {
            case "INT":
                $re = filter_input(INPUT_POST, $variavel, FILTER_SANITIZE_NUMBER_INT);
                break;
            case "EMAIL":
                $re = filter_input(INPUT_POST, $variavel, FILTER_SANITIZE_EMAIL);
                break;
            case "FLOAT":
                $re = filter_input(INPUT_POST, $variavel, FILTER_SANITIZE_NUMBER_FLOAT);
                break;
            case "URL":
                $re = filter_input(INPUT_POST, $variavel, FILTER_SANITIZE_URL);
                break;
        }
    } else {
        $re = filter_input(INPUT_POST, $variavel, FILTER_SANITIZE_STRING);
    }
    return $re;
}

function validarCNPJ($cnpj){
    $cnpj = str_pad(str_replace(array('.','-','/'),'',$cnpj),14,'0',STR_PAD_LEFT);
    if (strlen($cnpj) != 14){
        return false;
    }else{
        for($t = 12; $t < 14; $t++){
            for($d = 0, $p = $t - 7, $c = 0; $c < $t; $c++){
                $d += $cnpj{$c} * $p;
                $p  = ($p < 3) ? 9 : --$p;
            }
            $d = ((10 * $d) % 11) % 10;
            if($cnpj{$c} != $d){
                return false;
            }
        }
        return true;
    }
}

function soNumero($str) {
    return preg_replace("/[^0-9]/", "", $str);
}

function mascara_string($mascara,$string){
   $string = str_replace(" ","",$string);
   for($i=0;$i<strlen($string);$i++){
      $mascara[strpos($mascara,"#")] = $string[$i];
   }
   return $mascara;
}

//mascara para exibição, tel com 8/9 digitos
function mascara_stringTel($string){
    if(strlen($string) == 10){
        $string = mascara_string("(##) ####-####", $string);
    }elseif(($string == 0) || ($string == '')){
        $string = '';
    }else{
        $string = mascara_string("(##) #####-####", $string);
    }
    return $string;
}

/**
 * Expressão regular Personalizada, automaticamente ela remove os espaços multiplos
 * e você pode aproveitar para remover outros caracters especificos passando em array
 * @param type $str
 * @param array $caracters
 * @return string
 */
function expersonalizada($str, $caracters=null, $composto=null){
    $array = array();
    $re = $str;
    if($caracters !== null){
        if(is_array($caracters)){
            foreach($caracters as $val){
                array_push($array, $val);
            }
        }
    }
    
    if(count($array) > 0){
        $match = '/\\'  . implode("|\\",$array) . '/i';
        $re = preg_replace($match, "", $str);
    }
    
    if($composto!==null){
        $arrayC = array();
        if(is_array($composto)){
            foreach($composto as $val){
                array_push($arrayC, $val);
            }
            
            $match = '/'  . implode("|",$arrayC) . '/i';
            $re = preg_replace($match, "", $re);
        }
    }
    
    $re = preg_replace("/[[:blank:]]+/", " ", $re);
    $re = trim($re);
    return $re;
}

/**
 * Substitui a sequencias de caracteres identicos pelo caracter que está sendo repetido
 * ex: vascooooo  = vasco
 * @param type $str
 * @param int $nrRep
 * @return string
 */
function regexCaracterIgualConsecutivo ($str,$nrRep){
    $letras = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $re = $str;
    foreach ($letras as $letra) {
          $re =  preg_replace("/($letra{{$nrRep},})/i", "$letra", $re);
    }
    return $re;
}

// pra imprimir arrays com tag pre
function print_array($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
    return TRUE;
}

/** 
 * TRANSFORMA DATA POR EXTENSO
 * @param DATETIME $data
 * @param int $tipo
 * @param DATETIME $data_fim
 * @return string
 */
function dataExtenso($data, $tipo = null, $data_fim = null) {
    $dia = date("d", strtotime($data));
    $mes = date("m", strtotime($data));
    $ano = date("Y", strtotime($data));
    $ano_simp = date("y", strtotime($data));
    $hora = date("H", strtotime($data));
    $minuto = date("i", strtotime($data));    
    $mes_extenso = mesesArray($mes);
    $mes_inicias = substr(mesesArray($mes), 0, 3);
    $hora_dataFim = date("H", strtotime($data_fim));
    $minuto_dataFim = date("i", strtotime($data_fim));
    
    // TIPO1 (13 de Julho de 2015, 19:00 às 21:00)
    if($tipo == 1){
        $data_formatada = "{$dia} de {$mes_extenso} de {$ano}, {$hora}:{$minuto} às {$hora_dataFim}:{$minuto_dataFim}";
    
    // TIPO2 (Jun, 15)
    }elseif($tipo == 2){
        $data_formatada = "{$mes_inicias}, {$ano_simp}";    
    
    // TIPO3 (19:00 às 21:00)
    }elseif($tipo == 3){
        $data_formatada = "{$hora}:{$minuto} às {$hora_dataFim}:{$minuto_dataFim}";
    
    // TIPO4 (25 de Agosto, 2014)
    }elseif($tipo == 4){
        $data_formatada = "{$dia} de {$mes_extenso}, {$ano}";
    
    // TIPO5 (25 Ago, 2015)
    }elseif($tipo == 5){
        $data_formatada = "{$dia} {$mes_inicias}, {$ano_simp}"; 
        
    }else{
        $data_formatada = date("d/m/Y", strtotime($data));
    }
    
    return $data_formatada;    
}

function selected_menu( $value, $selected, $nome_ativo = null){
    if($nome_ativo){
        $ativo = $nome_ativo;
    }else{
        $ativo = 'ativo';
    }
    
    return $value == $selected ? $ativo : '';
}

function limitarTexto($texto, $limite){
    $contador = strlen($texto);
  
    if ( $contador >= $limite ) {      
        $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
        return $texto;
    }
  
    else{
        return $texto;
    }
}

function quebraTexto($texto, $limite) {
    $novo_texto = wordwrap($texto, $limite, "<br />\n");
    
    return $novo_texto;
}

/**
 * FUNÇÃO PARA RETORNAR MÁSCARA
 * @param $val
 * @param $mask
 */
function mask($val, $mask){
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++){
        if($mask[$i] == '#'){
            if(isset($val[$k]))
            $maskared .= $val[$k++];
        }else{
            if(isset($mask[$i]))
            $maskared .= $mask[$i];
        }
    }
    
    return $maskared;
}

/**
 * CRIADA PARA VERIFICAR A PAGINA ATIVA
 * PARA APLICAR A CLASS ativo NO MENU 
 * @param type $position (1 nome do arquivo, 2 modulo(pasta))
 */
function page_name($position) {
    $page_exp = $_SERVER['REQUEST_URI'];
    $page = explode('/', $page_exp);
    $page = array_reverse($page);        
    
    $pagename = ($page_exp == '/') ? '' : $page[$position];
    
    return $pagename;
}

/*
 * RETORNA NOME DA AREA
 * PARA APLICAR A CLASS ativo NO SUBMENU
 * POR EXEMPLO: NA AREA DE USUARIO QUANDO ACESSA OUTRAS PARTES
 * COMO POR EXEMPLO O LOG, O SUBMENU NÃO FICA ATIVO,
 * POIS A VERIFICAÇÃO É FEITA PELO NOME DA PÁGINA
 * ENTÃO FOI CRIADO UMA FLAG NO BANCO PARA PEGAR POR AREA
 * ENTÃO QUANDO ESTIVER NA PAGINA usuario_form.php POR EXEMPLO
 * ELE ESTA NA AREA usuario, ENTÃO VAI FICAR COMO ATIVO O SUBMENU USUÁRIO
 */
function area_name(){    
    $pagename = page_name(0);
    $pagename = explode('_', $pagename);
    $pagename = $pagename[0];
    
    return $pagename;
}

function calculaIdade($data_nascimento) {   
    
    //formato default dd/mm/YYYY
    
    // Separa em dia, mês e ano
    list($dia, $mes, $ano) = explode('/', $data_nascimento);
    
    // Descobre que dia é hoje e retorna a unix timestamp
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    // Descobre a unix timestamp da data de nascimento do fulano
    $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
    
    // Depois apenas fazemos o cálculo já citado :)
    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
    
    return $idade;
}

//TRAZER ALGUMA INFORMAÇÃO RELACIONADA A QUALQUER TABELA
function getDB($tabela, $condicao, $retorno) {
    $sql = montaQuery($tabela, "*", $condicao, null, null, null, false);    
    $res = mysql_fetch_assoc($sql);
    
    return $res[$retorno];
}

function inicialPalavraMaiuscula($param) {
    $string = ucwords(strtolower($param));
    
    return $string;
}
?>