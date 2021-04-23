<?php
require('../class/ConfigClass.php');

$_id = $_REQUEST['cod'];
?>

<div id="img_perfil">
    <ul class="cmd m-r-40">
        <li onclick="foo.import();">Importar</li>
        <li onclick="foo.zoom('min');">Mínimo</li>
        <li onclick="foo.zoom('max');">Máximo</li>
        <li onclick="foo.flip();">Inverter</li>
        <li onclick="foo.rotate();">Girar</li>
        <!--
        <li class="maskbg">Mask</li>
        <li><a id="unqiueID" onclick="foo.download(300, 300, 'test', 'png', this.id);" href="#">Download</a></li>
        <li onclick="console.log(foo.crop(300,300,'png'))" data-tooltip="Prints object to console">Crop</li>
        <li onclick="console.log(foo.original())" data-tooltip="Prints object to console">Original</li>
        -->
    </ul>
    
    <div class="example">
        <div class="default"></div>
    </div>
    
    <button type="submit" class="btn btn-info pull-right m-t-15" id="save_foto" name="save_foto" data-key="<?php echo $_id; ?>"><span class="fa fa-save"></span>&nbsp;&nbsp;Salvar</button>
    <button type="submit" class="btn btn-warning pull-right m-t-15 m-r-15" id="fecha_modal" name="fecha_modal"><span class="fa fa-close"></span>&nbsp;&nbsp;Fechar</button>
    
    <div class="clear-both"></div>
</div>

<script>
    $(function(){
        $("#save_foto").on("click", function(){
            var key = $(this).data("key");
            var crop = foo.crop(128, 128, 'jpeg');                          
            
            $("#crop_string").val(crop.string);
            $("#crop_type").val(crop.type);
            $(".bootstrap-dialog-close-button").click();
            $("#img_perf").attr("src", crop.string);
            
            $.ajax({
                type: "post",
                url: "../methods.php",
                dataType: "json",
                data: {
                    crop: crop,
                    id: key,
                    method: "salva_foto_cliente"
                },
                success: function(data) {
                    if(data.status == "1"){
                        $(".bootstrap-dialog-close-button").click();
                        $("#img_perf").attr("src", crop.string);
                    }
                }
            });
        });
        
        $("#fecha_modal").on("click", function(){
            $(".bootstrap-dialog-close-button").click();
        });
    });
    
    //  create a new instance
    // --------------------------------------------------------------------------
    
    // you may do multiple instances of the cropper on a single page
    // just be sure to give each a unique name
    var foo = new CROP();
    
    foo.init({
        // element to load the cropper into
        container: '.default',
        
        // image to load, accepts base64 string
        //image: 'test.jpg',
        
        // aspect ratio
        width: 128,
        height: 128,
        
        // prevent image from leaking outside of container. boolean
        mask: false,
        
        // input[range] attributes
        zoom: {

                // slider step change
                steps: 0.01,

                // minimum and maximum zoom
                min: 1,
                max: 5

        }
        
        // optional preview. remove this object if you wish to hide it
        //preview: {

                // element to load the preview into
        //	container: '.pre',

                // aspect ratio
        //	ratio: 0.5,

        //},
    });
    
    //  toggle mask
    // --------------------------------------------------------------------------
    
    $('body').on("click", 'li.maskbg', function () {
        $('.example').toggleClass('maskbg');
        $('.default').attr('data-mask', $('.default').attr('data-mask') === 'true' ? 'false' : 'true');
        $('li.maskbg').html($('li.maskbg').html() == 'Mask' ? 'Unmask' : 'Mask');
    });
</script>