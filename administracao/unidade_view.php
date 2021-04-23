<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$unidade = new Unidade();

$id_unidade = $_REQUEST['id'];

$sql_unidade = $unidade->getUnidadeI("id_unidade", $id_unidade);
$res_unidade = mysql_fetch_assoc($sql_unidade);
?>
<style>
    tbody tr td{
        border: 0!important;
    }
</style>
<div class="row">
    <div class="col-md-12 col-sm-12 over-h">
        <div class="panel panel-default panel-with-tabs" data-sortable-id="ui-unlimited-tabs-2">
            <table class="table no-border p-0 m-0">
                <tbody class="f-s-12">
                    <tr>
                        <td>Unidade</td>
                        <td>
                            <?php echo $res_unidade['unidade']; ?>
                            
                            <?php if($res_unidade['igreja_sede'] == 1){ ?>
                            &nbsp;<span class="label label-info">Sede</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Telefone</td>
                        <td><?php echo mascara_stringTel($res_unidade['telefone']); ?></td>
                    </tr>
                    <tr>
                        <td>Nome do Responsável</td>
                        <td><?php echo $res_unidade['nome_responsavel']; ?></td>
                    </tr>
                    <tr>
                        <td>Telefone do Responsável</td>
                        <td><?php echo mascara_stringTel($res_unidade['telefone_responsavel']); ?></td>
                    </tr>                    
                    <tr>
                        <td>Endereço</td>
                        <td>
                            <?php 
                            $end_numero = ($res_unidade['numero'] != 0) ? $res_unidade['numero'] : '';
                            $end_cep = ($res_unidade['cep'] != 0) ? mascara_string("#####-###", $res_unidade['cep']) : '';

                            echo "{$res_unidade['logradouro']} {$end_numero} {$res_unidade['complemento']} {$res_unidade['bairro']}<br />";
                            echo $res_unidade['cidade'] . " " . getDB("uf", "uf_id = {$res_unidade['uf']}", "uf_sigla") . " {$end_cep}"; 
                            ?>
                        </td>
                    </tr>                                                                                                               
                </tbody>
            </table>
        </div>
    </div>
</div>