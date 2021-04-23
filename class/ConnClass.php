<?php

/**
 * Conn.class [ CONEXÃO ]
 * Classe abstrata de conexão. Padrão SingleTon.
 * Retorna um objeto PDO pelo método estático getConn();
 * 
 * @copyright (c) 2015, Maxuel R. Santos SEMEAR SISTEMAS
 */
class Conn {
    
    private static $Host = HOST;
    private static $User = USER;
    private static $Pass = PASS;
    private static $Dbsa = DBSA;
    
    // atributo que retorna objeto PDO
    /** @var PDO */
    private static $Connect = null;
    
    /**
     * conecta com o banco de dados com o pattern singleton
     * retorna um objeto PDO
     */
    private static function Conectar() {
        try {
            $dsn = 'mysql:host=' .  self::$Host . ';dbname=' . self::$Dbsa;
            $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
            if(self::$Connect == null){
                self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
            }
        } catch (PDOException $e) {
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getMessage());
            die;
        }
        
        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }
    
    /** retorna um objeto PDO SingleTon Pattern */
    public static function getConn() {
        return self::Conectar();
    }
    
}

?>