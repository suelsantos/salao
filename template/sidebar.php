<?php
$menu = new Menu();

$modulos = $menu->getModulos();
$pagename = page_name(0);
$menuname = page_name(1);

$submenuname = area_name();
?>
<div id="sidebar" class="sidebar">			
    <div data-scrollbar="true" data-height="100%">				
        <ul class="nav">
            <li class="nav-profile">
                <div class="image">
                    <a href="javascript:;"><img src="<?php echo "{$caminho_src}uploads/".COD_IGREJA."/usuarios/{$foto_user}?t=".filemtime("{$caminho_src}uploads/".COD_IGREJA."/usuarios/{$foto_user}"); ?>" alt="" /></a>
                </div>
                <div class="info">
                    <?php echo retornaPalavras($_SESSION['nome_usuario']); ?>
                    <!--<small>Desenvolvedor</small>-->
                    <small><?php echo date("d/").mesesArray(date('m')).date(" H:i"); ?></small>
                </div>
            </li>
        </ul>
        <ul class="nav" id="modulos">
            <?php
            foreach ($modulos as $menus => $dados){
                $menuname_bd = split("/", $dados['link']);
                $menuname_bd = $menuname_bd[0];
                
                if($dados['pagina_unica'] == 0){
            ?>
            <li class="has-sub <?php echo selected_menu($menuname, $menuname_bd, 'active'); ?>">                
                <a href="javascript:;">                                       
                    <b class="caret pull-right"></b>
                    <i class="fa fa-<?php echo $dados['icone']; ?> fa-lg"></i>
                    <span><?php echo $dados['nome']; ?></span>
                </a>
                <ul class="sub-menu">
                    <?php
                    if($dados['pagina_unica'] == 0){
                        foreach ($dados['itens'] as $kes => $submenus){                            
                    ?>
                            <li class="<?php echo selected_menu($submenuname, $submenus['area'], 'active'); ?>"><a href="<?php echo $caminho_src.$submenus['link']; ?>"><?php echo $submenus['nome']; ?></a></li>
                    <?php
                        }
                    }?>
                </ul>
            </li>
            <?php
                }else{
            ?>
            <li class="has-sub <?php echo selected_menu($pagename, $menuname_bd, 'active'); ?>">
                <a href="<?php echo $caminho_src.$dados['link']; ?>">
                    <i class="fa fa-<?php echo $dados['icone']; ?> fa-lg"></i>
                    <span><?php echo $dados['nome']; ?></span>
                </a>
            </li>
            <?php
                }
            } ?>
            
            <li>
                <a href="javascript:;" class="sidebar-minify-btn" data-url="<?php echo $caminho_src; ?>methods.php" data-click="sidebar-minify"><i class="fa fa-angle-double-left fa-lg"></i></a>
            </li>                    
        </ul>                    
    </div>                
</div>
<div class="sidebar-bg"></div>

<!--USADO PARA PREENCHER VALOR RELACIONADO A SIDEBAR, SE ESTA ATIVO OU NÃO-->
<i class="active_sidebar hidden"><?php echo $sidebar_fixed; ?></i>