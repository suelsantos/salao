<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$id = $_REQUEST['id'];

if($_REQUEST['tipo'] == "Cliente"){
    $cliente = new ClienteSalao();

    $qry = $cliente->getCliente("id_cliente", $id);
    $res = mysql_fetch_assoc($qry);
    
    $path = "clientes";
    $file_tipo = 8;
    
} else {
    $profissional = new Profissional();
    
    $qry = $profissional->getProfissional("id_profissional", $id);
    $res = mysql_fetch_assoc($qry);
    
    $path = "profissionais";
    $file_tipo = 11;
}

//PESQUISA SE TEM FOTO
$pesquisa_foto = $global->getFileGlobal($file_tipo, $id);
$result_foto = mysql_fetch_assoc($pesquisa_foto);
$total_foto = mysql_num_rows($pesquisa_foto);

if($total_foto == 0){
    $foto = "0.jpg";
}else{
    $foto = "{$id}.{$result_foto['extensao']}";
}
?>

<form action="" method="post" name="form_cidadao" class="form-horizontal" id="form_cidadao" data-parsley-validate enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-12 col-sm-12 over-h m-b-20 text-center">                             
            <img src="<?php echo "uploads/".COD_IGREJA."/{$path}/{$foto}?t=".filemtime("../uploads/".COD_IGREJA."/{$path}/{$foto}"); ?>" id="img_perf" class="media-object rounded-corner m-auto">        
            <p class="m-t-15">
                <?php echo (!empty($res['whatsapp'])) ? "<a href='https://web.whatsapp.com/send?phone=55{$res['whatsapp']}' target='_blank' class='btn btn-success btn-icon btn-circle btn-sm' title='Whatsapp'><i class='fa fa-whatsapp'></i></a>" : ""; ?>
                <?php echo (!empty($res['email'])) ? "<a href='mailto:{$res['email']}' target='_blank' class='btn btn-info btn-icon btn-circle btn-sm' title='Email'><i class='fa fa-envelope'></i></a>" : ""; ?>
            </p>
            <strong class="f-s-12">
                <?php 
                echo $res['nome'];
                echo ($res['apelido'] != '') ? " ({$res['apelido']})" : "";
                echo "<br><span class='label label-info'>" . $_REQUEST['tipo'] . "</label>";
                ?>
            </strong>
            <div class="m-t-15 f-s-12">
                <?php if(!empty($res['whatsapp'])){ ?> 
                <p><span class='fa fa-whatsapp fa-lg m-r-5'></span>&nbsp;<?= mascara_stringTel($res['whatsapp']) ?></p>               
                <?php } ?> 
                
                <?php if(!empty($res['celular'])){ ?>
                <p><span class='fa fa-mobile fa-lg m-r-5'></span>&nbsp;<?= mascara_stringTel($res['celular']) ?></p>                 
                <?php } ?> 
                
                <?php if(!empty($res['telefone'])){ ?>                
                <p><span class='fa fa-phone fa-lg m-r-5'></span>&nbsp;<?= mascara_stringTel($res['telefone']) ?></p>
                <?php } ?>
                
                <?php if(!empty($res['email'])){ ?>                
                <p><span class='fa fa-envelope fa-lg m-r-5'></span>&nbsp;<?= $res['email'] ?></p>
                <?php } ?>
            </div>
        </div>
    </div>    
</form>