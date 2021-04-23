<?php

class Administracao extends Globals {    
    
    public $erro;
    public $sucess;    
    public $cod;        
    
    public static function getCargoF($key, $value, $retorno = null) {
        $qry = montaQuery("cargo AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    public static function getServicoF($key, $value, $retorno = null) {
        $qry = montaQuery("servico AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getCargoDataTable($requestData) {
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.*
            FROM cargo AS A";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" WHERE A.nome LIKE '%".$requestData['search']['value']."%' ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.nome ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //EDITAR
                if(Globals::verificaPermissoesAcoes(22)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_cargo toolt' data-action='editar' data-original-title='Editar' data-key='{$res['id_cargo']}'><i class='fa fa-pencil'></i></a> ";
                }
                
                $dataI[] = $res["nome"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_cargo_{$res['id_cargo']}";
                
                $data[] = $dataI;
            }
        }
        
        $info = array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
        
        return $info;        
    }        
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getServicoDataTable($requestData) {
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.*
            FROM servico AS A";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" WHERE A.nome LIKE '%".$requestData['search']['value']."%' ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.nome ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //EDITAR
                if(Globals::verificaPermissoesAcoes(26)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_servico toolt' data-action='editar' data-original-title='Editar' data-key='{$res['id_servico']}'><i class='fa fa-pencil'></i></a> ";
                }
                
                $dataI[] = $res["nome"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_servico_{$res['id_servico']}";
                
                $data[] = $dataI;
            }
        }
        
        $info = array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
        
        return $info;        
    }        
    
    public static function getProfissaoF($key, $value, $retorno = null) {
        $qry = montaQuery("profissao AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getProfissaoDataTable($requestData) {
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.*
            FROM profissao AS A";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" WHERE A.nome LIKE '%".$requestData['search']['value']."%' ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.nome ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //EDITAR
                if(Cidadao::verificaPermissoesAcoes(24)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_profissao toolt' data-action='editar' data-original-title='Editar' data-key='{$res['id_profissao']}'><i class='fa fa-pencil'></i></a> ";
                }
                
                $dataI[] = $res["nome"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_profissao_{$res['id_profissao']}";
                
                $data[] = $dataI;
            }
        }
        
        $info = array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
        
        return $info;        
    }
    
    public static function getFuncaoF($key, $value, $retorno = null) {
        $qry = montaQuery("funcao AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getFuncaoDataTable($requestData) {
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.*
            FROM funcao AS A";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" WHERE A.nome LIKE '%".$requestData['search']['value']."%' ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.nome ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //EDITAR
                if(Cidadao::verificaPermissoesAcoes(26)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_funcao toolt' data-action='editar' data-original-title='Editar' data-key='{$res['id_funcao']}'><i class='fa fa-pencil'></i></a> ";
                }
                
                $dataI[] = $res["nome"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_funcao_{$res['id_funcao']}";
                
                $data[] = $dataI;
            }
        }
        
        $info = array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
        
        return $info;        
    }
    
    public static function getFormaEntradaF($key, $value, $retorno = null) {
        $qry = montaQuery("cidadao_forma_entrada AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getFormaEntradaDataTable($requestData) {
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.*
            FROM cidadao_forma_entrada AS A";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" WHERE A.nome LIKE '%".$requestData['search']['value']."%' ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.nome ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //EDITAR
                if(Cidadao::verificaPermissoesAcoes(28)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_forma_entrada toolt' data-action='editar' data-original-title='Editar' data-key='{$res['id_forma_entrada']}'><i class='fa fa-pencil'></i></a> ";
                }
                
                $dataI[] = $res["nome"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_forma_entrada_{$res['id_forma_entrada']}";
                
                $data[] = $dataI;
            }
        }
        
        $info = array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
        
        return $info;        
    }
    
    public static function getSituacaoF($key, $value, $retorno = null) {
        $qry = montaQuery("cidadao_situacao AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getSituacaoDataTable($requestData) {
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.*
            FROM cidadao_situacao AS A";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" WHERE A.nome LIKE '%".$requestData['search']['value']."%' ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.nome ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //EDITAR
                if(Cidadao::verificaPermissoesAcoes(30)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_situacao toolt' data-action='editar' data-original-title='Editar' data-key='{$res['id_situacao']}'><i class='fa fa-pencil'></i></a> ";
                }
                
                $dataI[] = $res["nome"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_situacao_{$res['id_situacao']}";
                
                $data[] = $dataI;
            }
        }
        
        $info = array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
        
        return $info;        
    }
    
    public static function getFormacaoF($key, $value, $retorno = null) {
        $qry = montaQuery("formacao_teologica AS A",
                "A.*",
                "A.{$key} = '{$value}'", null, null, null, false);
        
        if(!is_null($retorno)){
            $res = mysql_fetch_assoc($qry);
            
            return $res[$retorno];
        }else{
            return $qry;
        }
    }
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getFormacaoDataTable($requestData) {
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.*
            FROM formacao_teologica AS A";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" WHERE A.nome LIKE '%".$requestData['search']['value']."%' ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.nome ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //EDITAR
                if(Cidadao::verificaPermissoesAcoes(32)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_formacao toolt' data-action='editar' data-original-title='Editar' data-key='{$res['id_formacao']}'><i class='fa fa-pencil'></i></a> ";
                }
                
                $dataI[] = $res["nome"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_formacao_{$res['id_formacao']}";
                
                $data[] = $dataI;
            }
        }
        
        $info = array(
            "totalData" => $totalData,
            "totalFiltered" => $totalFiltered,
            "data" => $data
        );
        
        return $info;        
    }
    
    public static function getFormaEntrada($id = null, $list = null) {
        if(!is_null($id)){
            $and = "id_forma_entrada = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("cidadao_forma_entrada", "*", $and, "nome", null, null, false);   
        
        if(!is_null($list)){
            return $sql;
        }else{
            $forma_entrada = array("" => "Selecione uma Forma");        
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $forma_entrada[$rst['id_forma_entrada']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $forma_entrada[$id];
            }else{
                return $forma_entrada;
            }
        }
    }
    
    public static function getSituacao($id = null, $list = null) {
        if(!is_null($id)){
            $and = "id_situacao = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("cidadao_situacao", "*", $and, "nome", null, null, false);   
        
        if(!is_null($list)){
            return $sql;
        }else{
            $situacao = array("" => "Selecione uma Situação");        
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $situacao[$rst['id_situacao']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $situacao[$id];
            }else{
                return $situacao;
            }
        }
    }
    
    public static function getFormacao($id = null, $list = null) {
        if(!is_null($id)){
            $and = "id_formacao = '{$id}'";
        }else{
            $and = null;
        }
        
        $sql = montaQuery("formacao_teologica", "*", $and, "nome", null, null, false);   
        
        if(!is_null($list)){
            return $sql;
        }else{
            $formacao = array("" => "Selecione uma Formação");        
            
            while ($rst = mysql_fetch_assoc($sql)) {
                $formacao[$rst['id_formacao']] = $rst['nome'];
            }
            
            if(!is_null($id)){
                return $formacao[$id];
            }else{
                return $formacao;
            }
        }
    }
    
    // verifica se existe cargo cadastrada
    public function verificaCargoExist($nome) {
        $qry = montaQuery("cargo", "*", "nome = '{$nome}'", null, null, null);
        return $qry;
    }
    
    public function verificaServicoExist($nome) {
        $qry = montaQuery("servico", "*", "nome = '{$nome}'", null, null, null);
        return $qry;
    }
    
    public function cadCargo($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("cargo", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 33, null, "Cadastrou o cargo {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }                
    
    public function cadServico($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("servico", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 33, null, "Cadastrou o serviço {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }       
    
    public function editServico($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("servico", $_campos, "id_servico = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 34, null, "Alterou o serviço {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
    
    public function editCargo($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("cargo", $_campos, "id_cargo = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 34, null, "Alterou o cargo {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
    
    // verifica se existe profissao cadastrada
    public function verificaProfissaoExist($nome) {
        $qry = montaQuery("profissao", "*", "nome = '{$nome}'", null, null, null);
        return $qry;
    }
    
    public function cadProfissao($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("profissao", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 35, null, "Cadastrou a profissão {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }                
    
    public function editProfissao($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("profissao", $_campos, "id_profissao = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 36, null, "Alterou a profissão {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
        
    public function verificaFuncaoExist($nome) {
        $qry = montaQuery("funcao", "*", "nome = '{$nome}'", null, null, null);
        return $qry;
    }
    
    public function cadFuncao($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("funcao", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 37, null, "Cadastrou a função {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }                
    
    public function editFuncao($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("funcao", $_campos, "id_funcao = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 41, null, "Alterou a função {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
        
    public function verificaFormaEntradaExist($nome) {
        $qry = montaQuery("cidadao_forma_entrada", "*", "nome = '{$nome}'", null, null, null);
        return $qry;
    }
    
    public function cadFormaEntrada($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("cidadao_forma_entrada", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 38, null, "Cadastrou a forma de entrada {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }                
    
    public function editFormaEntrada($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("cidadao_forma_entrada", $_campos, "id_forma_entrada = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 42, null, "Alterou a forma de entrada {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
    
    public function verificaSituacaoExist($nome) {
        $qry = montaQuery("cidadao_situacao", "*", "nome = '{$nome}'", null, null, null);
        return $qry;
    }
    
    public function cadSituacao($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("cidadao_situacao", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 39, null, "Cadastrou a situação do membro {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }                
    
    public function editSituacao($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("cidadao_situacao", $_campos, "id_situacao = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 43, null, "Alterou a situação do membro {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
    
    public function verificaFormacaoExist($nome) {
        $qry = montaQuery("formacao_teologica", "*", "nome = '{$nome}'", null, null, null);
        return $qry;
    }
    
    public function cadFormacao($campos_cad) {
        foreach($campos_cad as $indice => $valor){
            $_campos[$indice] = $valor;
        }                
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("formacao_teologica", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_SESSION['id_usuario'], 40, null, "Cadastrou a formação teológica {$_campos['nome']} | ID: {$id}");
        }
        
        return $insere;
    }                
    
    public function editFormacao($id, $campos_edit) {
        foreach($campos_edit as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $altera = sqlUpdate("formacao_teologica", $_campos, "id_formacao = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 44, null, "Alterou a formação teológica {$_campos['nome']} | ID: {$id}");
        }
        
        return $altera;
    }
    
    public function getErro() {
        return $this->erro;
    }
    
    public function setErro($erro) {
        $this->erro = $erro;
    }
    
    public function getSucess() {
        return $this->sucess;
    }
    
    public function setSucess($sucess) {
        $this->sucess = $sucess;
    }
    
}

?>