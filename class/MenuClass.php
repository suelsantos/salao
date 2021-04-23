<?php

class Menu{
    
    /**
     * Busca os módulos que o usuário logado tem permissão.
     * Vai na tabela de permissões por botão, busca todas as permissões e agrupa pelo Módulo
     * Trazendo apenas o nescessário para o saber quais modulos a pessoa tem acesso
     * @param type $user não é obrigatório, pegando a session
     * @return array
     */    
    public function getModulos() {
        $rsModulos = montaQuery("botoes_assoc AS A
            LEFT JOIN botoes AS B ON(A.botoes_id = B.botoes_id)
            LEFT JOIN botoes_menu AS C ON(B.botoes_menu = C.botoes_menu_id)
            LEFT JOIN botoes_area AS D ON(B.botoes_area = D.id_botoes_area)", 
            "C.botoes_menu_id, C.botoes_menu_nome, C.pagina_unica, C.fa_icon, C.diretorio, B.botoes_id, B.botoes_nome, B.botoes_link, D.area_nome", 
            "A.id_usuario = {$_SESSION['id_usuario']} AND B.status = 1", "C.ordem, B.botoes_menu, B.botoes_id", null, null, false, null);
        
        $modulos = array();
        
        while ($row = mysql_fetch_assoc($rsModulos)) {
            $modulos[$row['botoes_menu_id']]['id']                                  = $row['botoes_menu_id'];
            $modulos[$row['botoes_menu_id']]['nome']                                = $row['botoes_menu_nome'];
            $modulos[$row['botoes_menu_id']]['pagina_unica']                        = $row['pagina_unica'];
            $modulos[$row['botoes_menu_id']]['link']                                = $row['diretorio'];
            $modulos[$row['botoes_menu_id']]['icone']                               = $row['fa_icon'];
            $modulos[$row['botoes_menu_id']]['itens'][$row['botoes_id']]['id']      = $row['botoes_id'];
            $modulos[$row['botoes_menu_id']]['itens'][$row['botoes_id']]['nome']    = $row['botoes_nome'];
            $modulos[$row['botoes_menu_id']]['itens'][$row['botoes_id']]['link']    = $row['botoes_link'];
            $modulos[$row['botoes_menu_id']]['itens'][$row['botoes_id']]['area']    = $row['area_nome'];
        }
        
        //print_array($modulos);
        
        return $modulos;
    }
    
}

?>