$(function(){
    activar_opcion();

    $("#tabla_pedidos tbody").on("click",'a',function(e){
        e.preventDefault();
        
        abrirVentanaEspera();

        $.post(RUTA+"verificar/verPedido", {cod:$(this).attr("href")},
            function (data, textStatus, jqXHR) 
            {
                $("#cod_pedido").val(data.id_regmov);
                $("#cod_proy").val(data.ncodpry);
                $("#cod_cost").val(data.ncodcos);
                $("#cod_area").val(data.ncodarea);
                $("#cod_transporte").val(data.ctiptransp);
                $("#cod_estdoc").val(data.nEstadoDoc);
                $("#cod_registro").val(data.nEstadoReg);
                $("#cod_solicitante").val(data.ncodper);
                $("#cod_tipo").val(data.ctipmov);
                $("#estado").val(data.nflgactivo);
                $("#atencion").val(data.nNivAten);
                $("#numero").val(data.cnumero);
                $("#fecha").val(data.ffechadoc);
                $("#usuario").val(data.id_cuser);
                $("#proyecto").val(data.ccodpry + ' ' + data.cdespry);
                $("#area").val(data.ccodarea + ' ' + data.cdesarea);
                $("#costos").val(data.ccodcos + ' ' + data.cdescos);
                $("#transporte").val(data.cod_transporte + ' ' + data.transporte);
                $("#concepto").val(data.cconcepto);
                $("#solicitante").val(data.dni + ' ' + data.apellidos + ',' + data.nombres);
                $("#registro").val(data.estado);
                $("#documento").val(data.estado);
                $("#fechaven").val(data.ffechaven);
                $("#tipo")
                    .val( data.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS")
                    .addClass("desactivado");
                $("#espec_items").val(data.mdetalle);
                
                $(".process_header, .details_item").addClass('no_modificar');
                $("#registro")
                    .removeClass("proceso")
                    .addClass(data.estado.toLowerCase());

                $.post(RUTA+"verificar/verDetalle", {cod:$("#cod_pedido").val(),tipo:$("#cod_tipo").val()},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_pedido tbody")
                            .empty()
                            .append(data);
                    },
                    "text"
                );

                cerrarVentanaEspera();
                $("#modalProcess").fadeIn();        
            },
            "json"
        );

        return false;
    })

    $(".buttonClose").click(function (e) { 
        e.preventDefault();

        $(".insidePreview iframe").attr("src","");
        $(this).parent().fadeOut();

        return false;
    });

    $("#saveItem").click(function (e) { 
        e.preventDefault();
        
        $("#dialogConfirm").fadeIn();

        return false
    });

    $("#btnYes").click(function (e) { 
        e.preventDefault();

        $(this).parent().parent().parent().parent().fadeOut();
        let listaItems = JSON.stringify(obtenerItems());
        abrirVentanaEspera();

        $.post(RUTA+"verificar/darConformidad", {cod:$("#cod_pedido").val(),detalles:listaItems},
            function (data, textStatus, jqXHR) {
                if (data){
                    cerrarVentanaEspera();
                    mostrarMensaje("msj_correcto","Items aprobados : " + data);
                }
            },
            "text"
        );
    });

    $("#btnNo").click(function (e) { 
        e.preventDefault();
        
        $(this).parent().parent().parent().parent().fadeOut();

        return false;
    });

    $("#detalle_pedido").on("click","a", function (e) {
        e.preventDefault();

        if ($(this).attr("href") != 0){
            $(".insidePreview iframe").attr("src",$(this).attr("href"));
            $("#modalPreview").fadeIn();
        }else{
            mostrarMensaje("msj_info","No se adjuntaron detalles");
        }
    
        return false;
    });
})

function obtenerItems(){
    DATA = [];

    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        var CHECKED     = $(this).find('td').eq(7).children().prop("checked"),
            IDDET       = $(this).find('td').eq(1).data("indice"),
            ENTIDAD     = $(this).find('td').eq(1).data("entidad"),
            PEDIDO      = $(this).find('td').eq(1).data("pedido"),
            COMENTARIOS = $(this).find('td').eq(10).children().val(),

        item = {};

        if ( CHECKED ){
            item["checked"]     = CHECKED;
            item['iddet']       = IDDET;
            item["entidad"]     = ENTIDAD;
            item["pedido"]      = PEDIDO;
            item['comentarios'] = COMENTARIOS;
            

            //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
            DATA.push(item);
        }
    });

    return DATA;
}
