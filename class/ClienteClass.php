<?php

class Cliente{
    
    public function setInfoCliente() {
        
//        Conn('localhost', 'maxsantos', 'Max22Santos@', 'igrejaweb');
        $dominio = $_SERVER['SERVER_NAME'];
//        $qry_cliente = mysql_query("SELECT * FROM clientes WHERE dominio = '{$dominio}'") or die("ERRO na seleção de cliente");
//        $cliente = mysql_fetch_assoc($qry_cliente);
        
        // CONFIGURAÇÕES DO BANCO
        if($dominio == 'localhost'){
            define('HOST', 'localhost');
            define('USER', 'alisites_maxsantos');
            define('PASS', '8nwP2VU8nT*7(x');
            define('DBSA', 'alisites_salao');
        } else {
            define('HOST', 'localhost');
            define('USER', 'alisites_maxsantos');
            define('PASS', 'Be22Rangel@');
            define('DBSA', 'alisites_salao');
        }
        
        // DEFINIÇÕES
        define('COD_IGREJA', 1);
        define('IGREJA', 'Salão de Beleza');
//        define('TELEFONE', mascara_stringTel($cliente['telefone']));
//        define('EMAIL', $cliente['email']);
//        define('FAVICON', "clientes/{$cliente['id_cliente']}/favicon.{$cliente['favicon']}");
//        define('LOGOTIPO', "clientes/{$cliente['id_cliente']}/logotipo.{$cliente['logotipo']}");
//        define('BIBLIA', 'https://www.bible.com/pt/bible/211/jhn.1.ntlh');
//        define('LINK_TV', 'http://tv.igrejadapiam.com.br/');
//        define('FACEBOOK', 'https://www.facebook.com/igrejabatistadapiam');
//        define('YOUTUBE', 'https://www.youtube.com/user/IBPiam');
//        define('TWITTER', 'https://twitter.com/ibpiam');
//        define('GOOGLE_PLUS', 'https://plus.google.com/105509930483811333604/posts');
    }
    
}

?>