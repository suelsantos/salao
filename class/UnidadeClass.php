<?php

class Unidade extends Globals {
    
    public $erro;
    public $sucess;
    public $cod;
    
    public static function getUnidadeI($key, $value, $retorno = null, $array = null) {
        $qry = montaQuery("unidade AS A",
                "A.*",
                "A.{$key} = '{$value}' AND A.status = 1", null, null, null, false);
        
        if(is_null($array)){
            if(!is_null($retorno)){
                $res = mysql_fetch_assoc($qry);

                return $res[$retorno];
            }else{
                return $qry;
            }
        }else{
            $unidade = array("" => "Selecione uma Unidade");
            
            while ($rst = mysql_fetch_assoc($qry)) {
                $unidade[$rst['id_unidade']] = $rst['unidade'];
            }
            
            return $unidade;
        }
    }
    
    public function getListaUnidade() {
        $qry = montaQuery("unidade AS A", 
                "A.id_unidade, A.unidade, A.telefone, A.nome_responsavel", 
                "A.status = 1 AND A.unidade != 'Sistema'", null, null, null, false);
        return $qry;
    }
    
    /*
     * PEGAR INFORMAÇÕES PARA POPULAR DATATABLE
     */
    public static function getUnidadeDataTable($requestData) {        
        //OBTER REGISTROS DE NÚMERO TOTAL SEM QUALQUER PESQUISA
        $sql = "SELECT A.id_unidade, A.unidade, A.telefone, A.nome_responsavel
            FROM unidade AS A            
            WHERE A.status = 1 AND A.unidade != 'Sistema'";
        $query = mysql_query($sql) or die("ERRO get employees");
        $totalData = mysql_num_rows($query);
        $totalFiltered = $totalData;
        
        //OBTER REGISTROS DE NÚMERO TOTAL COM PESQUISA
        if(!empty($requestData['search']['value'])) {
            $sql .=" AND (A.unidade LIKE '%".$requestData['search']['value']."%' ";
            $sql .=" OR A.telefone LIKE '%".$requestData['search']['value']."%' ";    
            $sql .=" OR A.nome_responsavel LIKE '%".$requestData['search']['value']."%') ";
        }
        
        $query = mysql_query($sql) or die("ERRO get employees SEARCH");
        $totalFiltered = mysql_num_rows($query);
        
        $sql .= " ORDER BY A.id_unidade ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
        $query = mysql_query($sql) or die("ERRO get employees ORDER");
        
        $data = array();
        
        if($totalData > 0){
            while($res = mysql_fetch_assoc($query)){
                $dataI = array();
                
                $acoes = "";
                
                //VISUALIZAR
                if(Globals::verificaPermissoesAcoes(18)){
                    $acoes .= "<a href='javascript:;' class='btn btn-info btn-icon btn-square btn-sm btn_unidade toolt' id='toolt_view' data-action='visualizar' data-original-title='Visualizar' data-key='{$res["id_unidade"]}'><i class='fa fa-search'></i></a> ";
                }
                
                //EDITAR
                if(Globals::verificaPermissoesAcoes(19)){
                    $acoes .= "<a href='javascript:;' class='btn btn-warning btn-icon btn-square btn-sm btn_unidade toolt' data-action='editar' data-original-title='Editar' data-key='{$res["id_unidade"]}'><i class='fa fa-pencil'></i></a> ";
                }
                
                //EXCLUIR
                if(Globals::verificaPermissoesAcoes(20)){
                    $acoes .= "<a href='javascript:;' class='btn btn-danger btn-icon btn-square btn-sm btn_unidade toolt' data-action='excluir' data-original-title='Excluir' data-key='{$res["id_unidade"]}'><i class='fa fa-minus'></i></a> ";
                }
                
                if($res['igreja_sede'] == 1){
                    $sede = "&nbsp;<span class='label label-info'>Sede</span>";
                }else{
                    $sede = "";
                }
                
                $dataI[] = $res["id_unidade"];
                $dataI[] = "{$res["unidade"]} {$sede}";
                $dataI[] = mascara_stringTel($res['telefone']);
                $dataI[] = $res["nome_responsavel"];    
                $dataI[] = $acoes;
                
                $dataI['DT_RowId'] = "tr_{$res["id_unidade"]}";
                
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
    
    // VERIFICA SE EXISTE UNIDADE DEFINIDA COMO SEDE
    public function verificaUnidadeSede() {
        $qry = montaQuery("unidade", "*", "igreja_sede = 1 AND status = 1", null, null, null, false);
        return $qry;
    }
    
    public function cadUnidade() {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['unidade'] = acentoMaiusculo($_campos['unidade']);
        $_campos['telefone'] = removeMascara($_campos['telefone']);
        $_campos['nome_responsavel'] = acentoMaiusculo($_campos['nome_responsavel']);
        $_campos['telefone_responsavel'] = removeMascara($_campos['telefone_responsavel']);
        
        //ENDEREÇO
        $_campos['cep'] = removeMascara($_campos['cep']);
        $_campos['logradouro'] = acentoMaiusculo($_campos['logradouro']);
        $_campos['bairro'] = acentoMaiusculo($_campos['bairro']);
        $_campos['cidade'] = acentoMaiusculo($_campos['cidade']);
        $_campos['uf'] = getDB("uf", "uf_sigla = '{$_campos['uf']}'", "uf_id");
        $_campos['complemento'] = acentoMaiusculo($_campos['complemento']);
                
        $_campos['user_cad'] = $_SESSION['id_usuario'];
        $_campos['data_cad'] = date('Y-m-d H:i:s');
        
//        print_array($_campos); exit();
        
        //DIVISÃO DE CAMPOS E VALORES
        foreach ($_campos as $campo => $valor) {
            $campos[] = $campo;
            $valores[] = $valor;
        }
        
        $insere = sqlInsert("unidade", $campos, $valores);
        
        if($insere){
            $id = mysql_insert_id();
            Globals::gravaLog($_campos['user_cad'], 57, null, "Cadastrou a unidade {$_campos['unidade']} | ID: {$id}");
        }
        
        if($insere){
            $this->sucess = "Cadastrado com sucesso!";
        }else{
            $this->erro = "Erro ao cadastrar, tente mais tarde";
        }
    }
        
    public function editUnidade($id) {
        foreach($_REQUEST['cadastro'] as $indice => $valor){
            $_campos[$indice] = $valor;
        }
        
        $_campos['unidade'] = acentoMaiusculo($_campos['unidade']);
        $_campos['telefone'] = removeMascara($_campos['telefone']);
        $_campos['nome_responsavel'] = acentoMaiusculo($_campos['nome_responsavel']);
        $_campos['telefone_responsavel'] = removeMascara($_campos['telefone_responsavel']);
        
        //ENDEREÇO
        $_campos['cep'] = removeMascara($_campos['cep']);
        $_campos['logradouro'] = acentoMaiusculo($_campos['logradouro']);
        $_campos['bairro'] = acentoMaiusculo($_campos['bairro']);
        $_campos['cidade'] = acentoMaiusculo($_campos['cidade']);
        $_campos['uf'] = getDB("uf", "uf_sigla = '{$_campos['uf']}'", "uf_id");
        $_campos['complemento'] = acentoMaiusculo($_campos['complemento']);
        
        $_campos['user_alter'] = $_SESSION['id_usuario'];
        $_campos['data_alter'] = date('Y-m-d H:i:s');
        
//        print_array($_campos); exit();
                
        $altera = sqlUpdate("unidade", $_campos, "id_unidade = {$id}");
        
        if($altera){
            Globals::gravaLog($_SESSION['id_usuario'], 59, null, "Alterou a unidade {$_campos['unidade']} | ID: {$id}");            
        }
        
        if($altera){
            $this->sucess = "Dados atualizados com sucesso!";
            $this->cod = $id;
            header("refresh: 3; unidade_list.php");
        }else{
            $this->erro = "Erro ao atualizar dados, tente mais tarde";
            $this->cod = $id;
            header("refresh: 3; unidade_list.php");
        }
    }
    
    public function excluiUnidade($id) {
        $nome_unidade = Unidade::getUnidadeI("id_unidade", $id, "unidade");                
        
        Globals::gravaLog($_SESSION['id_usuario'], 58, null, "Excluiu a unidade {$nome_unidade} | ID: {$id}");
        
        $sql = sqlUpdate("unidade", "status = 0", "id_unidade = {$id}", false);
        
        return $sql;
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