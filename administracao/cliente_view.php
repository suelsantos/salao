<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$cliente = new ClienteSalao();

$id_cliente = $_REQUEST['id'];

$sql_cliente = $cliente->getCliente("id_cliente", $id_cliente);
$res_cliente = mysql_fetch_assoc($sql_cliente);

//PESQUISA SE TEM FOTO
$pesquisa_foto = $global->getFileGlobal(8, $id_cliente);
$result_foto = mysql_fetch_assoc($pesquisa_foto);
$total_foto = mysql_num_rows($pesquisa_foto);

if($total_foto == 0){
    $foto = "0.jpg";
}else{
    $foto = "{$id_cliente}.{$result_foto['extensao']}";
}
?>
<style>
    tbody tr td{
        border: 0!important;
    }
</style>
<div class="row">
    <div class="col-md-3 col-sm-12 over-h m-b-20 text-center">
        <div class="profile-left">            
            <img src="<?php echo "../uploads/".COD_IGREJA."/clientes/{$foto}?t=".filemtime("../uploads/".COD_IGREJA."/clientes/{$foto}"); ?>" id="img_perf" class="media-object rounded-corner">
            <p class="m-t-15">
                <?php echo ($res_cliente['whatsapp'] != '') ? "<a href='https://web.whatsapp.com/send?phone=55{$res_cliente['whatsapp']}' target='_blank' class='btn btn-success btn-icon btn-circle btn-sm' title='Whatsapp'><i class='fa fa-whatsapp'></i></a>" : ""; ?>
                <?php echo ($res_cliente['email'] != '') ? "<a href='mailto:{$res_cliente['email']}' target='_blank' class='btn btn-info btn-icon btn-circle btn-sm' title='Email'><i class='fa fa-envelope'></i></a>" : ""; ?>
            </p>
            <i class="fa fa-user hide"></i>
            <strong class="f-s-12"><?php echo $res_cliente['nome']; ?></strong>
        </div>
    </div>
    <div class="col-md-9 col-sm-12 over-h">
        <table class="table no-border">            
            <tbody class="f-s-12">                
                <tr>
                    <td><b>Email</b></td>
                    <td><?php echo $res_cliente['email']; ?></td>
                </tr>
                <tr>
                    <td><b>Celular</b></td>
                    <td><?php echo mascara_stringTel($res_cliente['celular']); ?></td>
                </tr>
                <tr>
                    <td><b>Telefone</b></td>
                    <td><?php echo mascara_stringTel($res_cliente['telefone']); ?></td>
                </tr>
                <tr>
                    <td><b>Data de Nascimento</b></td>
                    <td><?php echo ($res_cliente['data_nascimento'] != '0000-00-00') ? converteData($res_cliente['data_nascimento'], "d/m/Y") : ''; ?></td>
                </tr>
                <tr>
                    <td><b>Sexo</b></td>
                    <td><?php echo ($res_cliente['sexo'] == 1) ? "Masculino" : "Feminino"; ?></td>
                </tr>
                <tr>
                    <td><b>Endereço</b></td>
                    <td>
                        <?php 
                        $end_numero = ($res_cliente['numero'] != 0) ? $res_cliente['numero'] : '';
                        $end_cep = ($res_cliente['cep'] != 0) ? mascara_string("#####-###", $res_cliente['cep']) : '';
                        
                        echo "{$res_cliente['logradouro']} {$end_numero} {$res_cliente['complemento']} {$res_cliente['bairro']}<br />";
                        echo $res_cliente['cidade'] . " " . getDB("uf", "uf_id = {$res_cliente['uf']}", "uf_sigla") . " {$end_cep}"; 
                        ?>
                    </td>
                </tr>                
                <?php if($res_cliente['observacoes'] != ''){ ?>
                <tr>
                    <td><b>Observações</b></td>
                    <td><?php echo $res_cliente['observacoes']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>                        
    </div>
</div>