<?php
require('../class/ConfigClass.php');

if(empty($_SESSION['id_usuario'])){
    header("Location: ../");
}

$cidadao = new Cidadao();

$id = $_REQUEST['id'];

$qry = $cidadao->getCidadao("id_cidadao", $id);
$res_cidadao = mysql_fetch_assoc($qry);

//PESQUISA SE TEM FOTO
$pesquisa_foto = $global->getFileGlobal(10, $id);
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
            <img src="<?php echo "uploads/".COD_IGREJA."/cidadaos/{$foto}?t=".filemtime("../uploads/".COD_IGREJA."/cidadaos/{$foto}"); ?>" id="img_perf" class="media-object rounded-corner m-auto">        
            <p class="m-t-15">
                <?php echo ($res_cidadao['facebook'] != '') ? "<a href='{$res_cidadao['facebook']}' target='_blank' class='btn btn-primary btn-icon btn-circle btn-sm' title='Facebook'><i class='fa fa-facebook'></i></a>" : ""; ?>
                <?php echo ($res_cidadao['twitter'] != '') ? "<a href='{$res_cidadao['twitter']}' target='_blank' class='btn btn-info btn-icon btn-circle btn-sm' title='Twitter'><i class='fa fa-twitter'></i></a>" : ""; ?>            
                <?php echo ($res_cidadao['google_plus'] != '') ? "<a href='{$res_cidadao['google_plus']}' target='_blank' class='btn btn-danger btn-icon btn-circle btn-sm' title='Google+'><i class='fa fa-google-plus'></i></a>" : ""; ?>            
                <?php echo ($res_cidadao['linkedin'] != '') ? "<a href='{$res_cidadao['linkedin']}' target='_blank' class='btn btn-primary btn-icon btn-circle btn-sm' title='Linkedin'><i class='fa fa-linkedin'></i></a>" : ""; ?>            
                <?php echo ($res_cidadao['instagram'] != '') ? "<a href='{$res_cidadao['instagram']}' target='_blank' class='btn btn-default btn-icon btn-circle btn-sm' title='Instagram'><i class='fa fa-instagram'></i></a>" : ""; ?>                                  
            </p>
            <strong class="f-s-12">
                <?php 
                echo $res_cidadao['nome'];
                echo ($res_cidadao['apelido'] != '') ? " ({$res_cidadao['apelido']})" : "";
                ?>
            </strong>
            <p class="m-t-15 f-s-12">
                <?php if(!empty($res_cidadao['celular'])){ ?>
                <?php echo mascara_stringTel($res_cidadao['celular']); ?>
                <?php } ?>        
                <?php if(!empty($res_cidadao['telefone'])){ ?>
                <?php echo "/ " . mascara_stringTel($res_cidadao['telefone']); ?>
                <?php } ?>
            </p>
            <p class="m-t-15 f-s-12">
                <?php if(!empty($res_cidadao['email'])){ ?>
                <?php echo $res_cidadao['email']; ?>
                <?php } ?>
            </p>
        </div>
    </div>    
</form>