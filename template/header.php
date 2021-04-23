<input type="hidden" name="id_usuario_logado" id="id_usuario_logado" value="" />
<div id="header" class="header navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a href="<?php echo $caminho_src; ?>index.php" class="navbar-brand"><span class="navbar-logo">Salão de Beleza</span></a>
            <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    <span>Unidade</span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu animated fadeInDown">
                    <li class="arrow"></li>
                    <?php
                    $sql_unidade = getUnidades($_SESSION['id_usuario']);
                    
                    $usuario = carregaUsuario();
                    $unidade_usuario = $usuario['id_unidade'];
                    
                    while($row_unidade = mysql_fetch_assoc($sql_unidade)){
                    ?>
                    <li>
                        <a href="javascript:;" data-key="<?php echo $row_unidade['id_unidade']; ?>" data-regiao="<?php echo $unidade_usuario; ?>" data-url="<?php echo $caminho_src; ?>methods.php" class="bt-troca-unidade <?php echo ($row_unidade['id_unidade'] == $unidade_usuario) ? 'bg-grey-lighter' : ''; ?>">
                            <?php echo "{$row_unidade['id_unidade']} - {$row_unidade['unidade']}"; ?>
                        </a>
                    </li>
                    <?php                     
                    } ?>
                </ul>
            </li>
            <li class="dropdown navbar-user">
                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
                    
                    <?php
                    if($usuario['nome_extensao'] == ""){
                        $foto_user = "0.jpg";
                    }else{
                        $foto_user = "{$_SESSION['id_usuario']}.{$usuario['nome_extensao']}";
                    }
                    ?>
                    
                    <img src="<?php echo "{$caminho_src}uploads/".COD_IGREJA."/usuarios/{$foto_user}?t=".filemtime("{$caminho_src}uploads/".COD_IGREJA."/usuarios/{$foto_user}"); ?>" alt="" />
                    <span class="hidden-xs"><?php echo retornaPalavras($_SESSION['nome_usuario']); ?></span>
                    <b class="caret"></b>
                </a>                                    
                <ul class="dropdown-menu animated fadeInLeft">
                    <li class="arrow"></li>
                    <!--<li><a href="javascript:;" class="edit_perfil_logado" data-key="<?php echo $_SESSION['id_usuario']; ?>" data-url="<?php echo $caminho_src; ?>sistema/usuario_form.php">Editar Perfil</a></li>-->
                    <li><a href="<?php echo $caminho_src; ?>sistema/usuario_form.php?id_usuario_logado=<?php echo $_SESSION['id_usuario']; ?>" class="edit_perfil_logado" data-key="<?php echo $_SESSION['id_usuario']; ?>" data-url="<?php echo $caminho_src; ?>sistema/usuario_form.php">Editar Perfil</a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $caminho_src; ?>logof.php">Sair do Sistema</a></li>
                </ul>                
            </li>
        </ul>
    </div>
</div>