<?php

class File {
        
    public $Embed;
    public $Watch;
    public $DataFile;
    
    public function getUltimoVideo($categoria) {
        $qry = montaQuery("video AS A", 
                "A.id_video, A.titulo, A.subtitulo, A.link, A.id_fonte, A.data_video, A.data_cad", 
                "A.id_categoria = {$categoria} AND A.status = 1", "A.data_cad DESC", "1", null);
        $res = mysql_fetch_assoc($qry);
        return $res;
    }
    
    public function getFonte($fonte, $link) {
        // youtube
        if($fonte == 1){
            $this->Embed = "https://www.youtube.com/embed/{$link}?rel=0&amp;showinfo=0";
            $this->Watch = "https://www.youtube.com/watch?v={$link}";
        }
    }
    
    public function getData($data_cad, $data_file = null) {
        if(!empty($data_file)){
            $this->DataFile = $data_file;
        }else{
            $this->DataFile = $data_cad;
        }
    }
    
    public function getFile($tipo, $limite, $order = FALSE) {
        if($order){
            $ordem = "A.ordem";
        }else{
            $ordem = "A.data_cad DESC";
        }
        
        $qry = montaQuery("file AS A
                LEFT JOIN file_extensao AS B ON(A.id_extensao = B.id_extensao)", 
                "A.id_file, A.data_cad, B.nome AS extensao", 
                "A.id_tipo = {$tipo} AND A.status = 1", $ordem, $limite, null);
        return $qry;
    }
    
    public function setEmbed($Embed) {
        $this->Embed = $Embed;
    }
    
    public function setWatch($Watch) {
        $this->Watch = $Watch;
    }
    
}

?>