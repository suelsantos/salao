<?php
include('./class/ConfigClass.php');

if (isset($_REQUEST['method']) && !empty($_REQUEST['method'])) {
            
    //MUDA O USUARIO DE UNIDADE
    if($_REQUEST['method'] == "troca_unidade"){
        $retorno = array("status" => "0");
        
        $unidade_de = $_REQUEST['unidade_de'];
        $unidade_para = $_REQUEST['unidade_para'];
        $usuario_unidade = $_SESSION['id_usuario'];
        
        $_SESSION['id_unidade'] = $unidade_para;
        
        $row_unidadeDe = $global->getUnidade($unidade_de);
        $row_unidadePara = $global->getUnidade($unidade_para);
        $nome_unidadeDe = acentoMaiusculo($row_unidadeDe['unidade']);
        $nome_unidadePara = acentoMaiusculo($row_unidadePara['unidade']);
        
        $sql = sqlUpdate("usuario", "id_unidade = '{$unidade_para}'", "id_usuario = '{$usuario_unidade}'", false);
        
        $global->gravaLog($usuario_unidade, 3, null, "Saiu de {$nome_unidadeDe} para {$nome_unidadePara}");        
        
        if($sql){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //OCULTA MENU NO SIDEBAR
    if($_REQUEST['method'] == "oculta_menu"){
        
        $retorno = array("status" => "0");
        
        $db_user = carregaUsuario();
        
        $sidebar = "";
        
        if($db_user['sidebar'] == 0){
            $sidebar = 1;
        }else{
            $sidebar = 0;
        }
        
        $sql = sqlUpdate("usuario", "sidebar = '{$sidebar}'", "id_usuario = '{$db_user['id_usuario']}'", false);
        
        if($sql){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //SALVAR/ALTERAR FOTO DO PERFIL DO USUARIO
    if($_REQUEST['method'] == "salva_foto_usuario"){
        $retorno = array("status" => "0");
        
        $user = new Usuario();
        $id = $_REQUEST['id'];
        
        $img = str_replace('data:image/'.$_REQUEST['crop']['type'].';base64,', '', $_REQUEST['crop']['string']);
	$img = str_replace(' ', '+', $img);
        
        $filename = "uploads/".COD_IGREJA."/usuarios/{$id}.{$_REQUEST['crop']['type']}";
        
        if(file_exists($filename)){
            unlink($filename);
            $upload = file_put_contents($filename, base64_decode($img));            
        }else{
            $upload = file_put_contents($filename, base64_decode($img));
        }
        
        if($upload){
            $altera_foto = $user->alteraIdFoto($id, $_REQUEST['crop']['type']);
            
            if($altera_foto){
                $retorno = array("status" => "1");
            }
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    if($_REQUEST['method'] == "salva_foto_cliente"){
        $retorno = array("status" => "0");
        
        $user = new Usuario();
        $id = $_REQUEST['id'];
        
        $img = str_replace('data:image/'.$_REQUEST['crop']['type'].';base64,', '', $_REQUEST['crop']['string']);
	$img = str_replace(' ', '+', $img);
        
        $filename = "uploads/".COD_IGREJA."/clientes/{$id}.{$_REQUEST['crop']['type']}";
        
        if(file_exists($filename)){
            unlink($filename);
            $upload = file_put_contents($filename, base64_decode($img));
        }else{
            $upload = file_put_contents($filename, base64_decode($img));
        }
        
        if($upload){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //DESATIVA USUARIO
    if($_REQUEST['method'] == "desativa_usuario"){
        $retorno = array("status" => "0");
        
        $user = new Usuario();
        $id = $_REQUEST['id'];
        
        $desativa = $user->desativaUsuario($id);
        
        if($desativa){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //RESETA SENHA DO USUARIO
    if($_REQUEST['method'] == "resetar_senha"){
        $retorno = array("status" => "0");
        
        $user = new Usuario();
        $id = $_REQUEST['id'];
        
        $reset = $user->resetaSenha($id);
        
        if($reset){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }        
    
    //CONSULTA PROFISSIONAIS
    if($_REQUEST['method'] == 'get_profissionais_agenda'){
        $anotacoes = array();
        
        $agenda = new Agenda();
        
        $query = $agenda->getProfissional(null, 1);
        
        while($rows = mysql_fetch_assoc($query)){
            $anotacoes[] = array(
                "id" => $rows['id_profissional'],
                "title" => $rows['apelido']
            );
        }
        
        echo  json_encode($anotacoes);
        exit();
    }
    
    //CONSULTA AGENDA
    if($_REQUEST['method'] == 'get_agendamentos'){
        $anotacoes = array();
        
        $agenda = new Agenda();
        
        $query = $agenda->getAgenda();
                        
        while($rows = mysql_fetch_assoc($query)){
            // print_array($rows);
            $sql_servico = $agenda->getServicoAssoc($rows['id']);                        
            
            while($res_servico = mysql_fetch_assoc($sql_servico)){
                $item_servico[] = inicialPalavraMaiuscula($res_servico['nome']);
            }
            
            $servicos = implode(", ", $item_servico);
            $nome_cliente = inicialPalavraMaiuscula($rows['nome_cliente']);    
            $nome_profissional = acentoMaiusculo($rows['nome_profissional']);
            
            $anotacoes[] = array(
                "id" => $rows['id'],
                "resourceId" => $rows['id_profissional'],
                "title" => "{$nome_profissional}: {$nome_cliente} - {$servicos}",
                "start" => $rows['start'],
                "end" => $rows['end']
            );
            
            unset($item_servico);
        }
        
        echo  json_encode($anotacoes);
        exit();
    }
    
    //CADASTRA AGENDA    
    if($_REQUEST['method'] == "cadastra_agenda"){
        $retorno = array("status" => "0");
        
        $agenda = new Agenda();
        
        $unidade = $_REQUEST['unidade'];
        $profissional = $_REQUEST['profissional'];
        $cliente = $_REQUEST['cliente'];
        $servico = $_REQUEST['servico'];
        $data = converteData($_REQUEST['data']);
        $hora_ini = date("H:i:s", strtotime($_REQUEST['hora_ini']));
        $hora_fim = date("H:i:s", strtotime($_REQUEST['hora_fim']));
        
        $start = $data . " " . $hora_ini;
        $end = $data . " " . $hora_fim;                
        
        $campos = array(
            "id_unidade" => $unidade,
            "id_profissional" => $profissional,
            "id_cliente" => $cliente,
            "start" => $start,
            "end" => $end
        );
        
        $cadastra = $agenda->cadAgenda($campos);
        
        if($cadastra){
            
            //CADASTRO DOS SERVIÇOS            
            foreach($servico as $servicos){
                sqlInsert("agenda_servico_assoc", "id_agenda, id_servico", "{$cadastra}, {$servicos}");
            }
        }
        
        if($cadastra){
            $retorno = array("status" => 1);
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EDITA AGENDA
    if($_REQUEST['method'] == "edita_agenda"){
        $retorno = array("status" => "0");
        
        $agenda = new Agenda();
        
        $id = $_REQUEST['id'];
        
        $unidade = $_REQUEST['unidade'];
        $profissional = $_REQUEST['profissional'];
        $cliente = $_REQUEST['cliente'];
        $servico = $_REQUEST['servico'];
        $data = converteData($_REQUEST['data']);
        $hora_ini = date("H:i:s", strtotime($_REQUEST['hora_ini']));
        $hora_fim = date("H:i:s", strtotime($_REQUEST['hora_fim']));
        
        $start = $data . " " . $hora_ini;
        $end = $data . " " . $hora_fim;
        
        $campos = array(
            "id_unidade" => $unidade,
            "id_profissional" => $profissional,
            "id_cliente" => $cliente,
            "start" => $start,
            "end" => $end
        );
        
        $edita = $agenda->editAgenda($id, $campos);
        
        if($edita){
            $retorno = array("status" => 1);
        }
        
        if($edita){
            $delete = sqlDelete("agenda_servico_assoc", "id_agenda = {$id}");                         
            
            if($delete){
                foreach($servico as $servicos){
                    sqlInsert("agenda_servico_assoc", "id_agenda, id_servico", "{$id}, {$servicos}", false);
                }
                
            } else {
                $retorno = array("status" => 0);
            }
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EXCLUI AGENDA
    if($_REQUEST['method'] == "exclui_agenda"){
        $retorno = array("status" => "0");
        
        $agenda = new Agenda();
        $id = $_REQUEST['id'];
        
        $exclui = $agenda->excluiAgenda($id);
        
        if($exclui){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EXCLUI CLIENTE
    if($_REQUEST['method'] == "exclui_cliente"){
        $retorno = array("status" => "0");
        
        $cliente = new ClienteSalao();
        $id = $_REQUEST['id'];
        
        $exclui = $cliente->excluiCliente($id);
        
        if($exclui){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EXCLUI UNIDADE
    if($_REQUEST['method'] == "exclui_unidade"){
        $retorno = array("status" => "0");
        
        $unidade = new Unidade();
        $id = $_REQUEST['id'];
        
        $desativa = $unidade->excluiUnidade($id);
        
        if($desativa){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //DATATABLE - LISTA DE UNIDADE
    if($_REQUEST['method'] == "dtable_unidade"){
        $unidade = new Unidade();
        
        //ARMAZENAR A MATRIZ GLOBAL DE SOLICITAÇÃO (IE, GET / POST) PARA UMA VARIÁVEL
        $requestData = $_REQUEST;
        
        //TRAZ INFORMAÇÕES PARA PREENCHER A MATRIZ DO DATATABLE
        $info = $unidade->getUnidadeDataTable($requestData);        
        
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($info['totalData']),
            "recordsFiltered" => intval($info['totalFiltered']),
            "data" => $info['data']
        );
        
        echo json_encode($json_data);
        exit;
    }
    
    //CADASTRA CARGO    
    if($_REQUEST['method'] == "cadastra_cargo"){
        $retorno = array("status" => "0");
        
        $administracao = new Administracao();
        
        $nome = $_REQUEST['nome'];        
        
        $campos = array(
            "nome" => $nome
        );
        
        $cargo_cad = mysql_num_rows($administracao->verificaCargoExist($nome));                
        
        if($cargo_cad > 0){
            $retorno = array("status" => 2, "nome" => $nome);
            
        }else{        
            $cadastra = $administracao->cadCargo($campos);

            if($cadastra){
                $retorno = array("status" => 1, "nome" => $nome);
            }
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EDITA CARGO    
    if($_REQUEST['method'] == "edita_cargo"){
        $retorno = array("status" => "0");
        
        $administracao = new Administracao();
        
        $nome = $_REQUEST['nome'];        
        $id = $_REQUEST['id'];
        
        $campos = array(
            "nome" => $nome            
        );
        
        $edita = $administracao->editCargo($id, $campos);
        
        if($edita){
            $retorno = array("status" => 1, "nome" => $nome, "id" => $id);
        }        
        
        echo json_encode($retorno);
        exit;
    }
        
    if($_REQUEST['method'] == "cadastra_servico"){
        $retorno = array("status" => "0");
        
        $administracao = new Administracao();
        
        $nome = $_REQUEST['nome'];        
        
        $campos = array(
            "nome" => $nome
        );
        
        $cargo_cad = mysql_num_rows($administracao->verificaServicoExist($nome));
        
        if($cargo_cad > 0){
            $retorno = array("status" => 2, "nome" => $nome);
            
        }else{
            $cadastra = $administracao->cadServico($campos);

            if($cadastra){
                $retorno = array("status" => 1, "nome" => $nome);
            }
        }
        
        echo json_encode($retorno);
        exit;
    }
        
    if($_REQUEST['method'] == "edita_servico"){
        $retorno = array("status" => "0");
        
        $administracao = new Administracao();
        
        $nome = $_REQUEST['nome'];        
        $id = $_REQUEST['id'];
        
        $campos = array(
            "nome" => $nome            
        );
        
        $edita = $administracao->editServico($id, $campos);
        
        if($edita){
            $retorno = array("status" => 1, "nome" => $nome, "id" => $id);
        }        
        
        echo json_encode($retorno);
        exit;
    }
    
    //DATATABLE - LISTA DE CARGOS
    if($_REQUEST['method'] == "dtable_cargo"){
        $administracao = new Administracao();
        
        //ARMAZENAR A MATRIZ GLOBAL DE SOLICITAÇÃO (IE, GET / POST) PARA UMA VARIÁVEL
        $requestData = $_REQUEST;       
        
        //TRAZ INFORMAÇÕES PARA PREENCHER A MATRIZ DO DATATABLE
        $info = $administracao->getCargoDataTable($requestData);
        
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($info['totalData']),
            "recordsFiltered" => intval($info['totalFiltered']),
            "data" => $info['data']
        );
        
        echo json_encode($json_data);
        exit;
    }
    
    //DATATABLE - LISTA DE CARGOS
    if($_REQUEST['method'] == "dtable_servico"){
        $administracao = new Administracao();
        
        //ARMAZENAR A MATRIZ GLOBAL DE SOLICITAÇÃO (IE, GET / POST) PARA UMA VARIÁVEL
        $requestData = $_REQUEST;       
        
        //TRAZ INFORMAÇÕES PARA PREENCHER A MATRIZ DO DATATABLE
        $info = $administracao->getServicoDataTable($requestData);
        
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($info['totalData']),
            "recordsFiltered" => intval($info['totalFiltered']),
            "data" => $info['data']
        );
        
        echo json_encode($json_data);
        exit;
    }
    
    //EXCLUI CATEGORIA
    if($_REQUEST['method'] == "exclui_categoria"){
        $retorno = array("status" => "0");
        
        $financeiro = new Financeiro();
        $id = $_REQUEST['id'];
        $tipo = $_REQUEST['tipo'];
        
        $verifica = $financeiro->verificaVinculoCategoria($id, $tipo);
        
        if($verifica > 0){
            $retorno = array("status" => 2, "qtd" => $verifica, "tipo" => $tipo);
            
        }else{        
            $exclui = $financeiro->excluiCategoria($id);
            
            if($exclui){
                $retorno = array("status" => "1");
            }
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //CADASTRA CATEGORIA    
    if($_REQUEST['method'] == "cadastra_categoria"){
        $retorno = array("status" => "0");
        
        $financeiro = new Financeiro();
        
        $nome = $_REQUEST['nome'];
        $tipo = $_REQUEST['tipo'];
        
        $campos = array(
            "nome" => $nome,
            "tipo" => $tipo
        );
        
        // verifica se existe categoria existe no bd
        $categoria_cad = mysql_num_rows($financeiro->verificaCategoriaExist($nome, $tipo));                
        
        if($categoria_cad > 0){
            $retorno = array("status" => 2, "nome" => $nome);
            
        }else{            
            $cadastra = $financeiro->cadCategoria($campos);
        
            if($cadastra){
                $retorno = array("status" => 1, "nome" => $nome);
            }            
        }                
        
        echo json_encode($retorno);
        exit;
    }
    
    //EDITA CATEGORIA    
    if($_REQUEST['method'] == "edita_categoria"){
        $retorno = array("status" => "0");
        
        $financeiro = new Financeiro();
        
        $nome = $_REQUEST['nome'];
        $tipo = $_REQUEST['tipo'];
        $id = $_REQUEST['id'];
        
        $campos = array(
            "nome" => $nome,
            "tipo" => $tipo
        );
        
        if($tipo == 1){
            $nome_tipo = "Entrada";
        }elseif($tipo == 2){
            $nome_tipo = "Saída";
        }
        
        $edita = $financeiro->editCategoria($id, $campos);
        
        if($edita){
            $retorno = array(
                "status" => 1, 
                "nome" => $nome, 
                "id" => $id, 
                "nome_tipo" => $nome_tipo
            );
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EXCLUI CONTA
    if($_REQUEST['method'] == "exclui_conta"){
        $retorno = array("status" => "0");
        
        $financeiro = new Financeiro();
        $id = $_REQUEST['id'];
        
        $verifica = $financeiro->verificaVinculoConta($id);
        
        if($verifica > 0){
            $retorno = array("status" => 2, "qtd" => $verifica);
            
        }else{                
            $exclui = $financeiro->excluiConta($id);

            if($exclui){
                $retorno = array("status" => "1");
            }
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //CADASTRA CONTA    
    if($_REQUEST['method'] == "cadastra_conta"){
        $retorno = array("status" => "0");
        
        $financeiro = new Financeiro();
        
        $nome = $_REQUEST['nome'];        
        $padrao_dizimo = $_REQUEST['padrao_dizimo'];        
        
        $campos = array(
            "nome" => $nome,
            "padrao_dizimo" => $padrao_dizimo
        );
        
        $cadastra = $financeiro->cadConta($campos);
        
        if($cadastra){
            $retorno = array("status" => 1, "nome" => $nome);
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EDITA CONTA    
    if($_REQUEST['method'] == "edita_conta"){
        $retorno = array("status" => "0");
        
        $financeiro = new Financeiro();
        
        $nome = $_REQUEST['nome'];
        $padrao_dizimo = $_REQUEST['padrao_dizimo'];
        $id = $_REQUEST['id'];
        
        $campos = array(
            "nome" => $nome,
            "padrao_dizimo" => $padrao_dizimo
        );
        
        $edita = $financeiro->editConta($id, $campos);
        
        if($edita){
            $retorno = array("status" => 1, "nome" => $nome, "id" => $id);
        }        
        
        echo json_encode($retorno);
        exit;
    }
    
    //EXCLUI ENTRADA
    if($_REQUEST['method'] == "exclui_entrada"){
        $retorno = array("status" => "0");
        
        $entrada = new Entrada();
        $id = $_REQUEST['id'];
        
        $desativa = $entrada->excluiEntrada($id);
        
        if($desativa){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //ESTORNA ENTRADA
    if($_REQUEST['method'] == "estorna_entrada"){
        $retorno = array("status" => "0");
        
        $entrada = new Entrada();
        $id = $_REQUEST['id'];                
        
        $estorna = $entrada->alteraStatusEntrada($id, "estorno");
        
        if($estorna){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //RECEBE ENTRADA
    if($_REQUEST['method'] == "recebe_entrada"){
        $retorno = array("status" => "0");
        
        $entrada = new Entrada();
        $id = $_REQUEST['id'];                
        
        $recebe = $entrada->alteraStatusEntrada($id, "recebe");
        
        if($recebe){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //EXCLUI SAIDA
    if($_REQUEST['method'] == "exclui_saida"){
        $retorno = array("status" => "0");
        
        $saida = new Saida();
        $id = $_REQUEST['id'];
        
        $desativa = $saida->excluiSaida($id);
        
        if($desativa){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //ESTORNA SAIDA
    if($_REQUEST['method'] == "estorna_saida"){
        $retorno = array("status" => "0");
        
        $saida = new Saida();
        $id = $_REQUEST['id'];                
        
        $estorna = $saida->alteraStatusSaida($id, "estorno");
        
        if($estorna){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
    
    //RECEBE SAIDA
    if($_REQUEST['method'] == "recebe_saida"){
        $retorno = array("status" => "0");
        
        $saida = new Saida();
        $id = $_REQUEST['id'];                
        
        $recebe = $saida->alteraStatusSaida($id, "pago");
        
        if($recebe){
            $retorno = array("status" => "1");
        }
        
        echo json_encode($retorno);
        exit;
    }
}
?>