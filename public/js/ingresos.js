$(function(){
    activar_opcion();

    $("#newreg").on("click", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();
        $(".process_header, .details_item").removeClass("desactivado");
        $(".sides_process div").removeClass("no_modificar");
        $("#formProcess")[0].reset();
        //$("#cod_pedido,#cod_proy,#cod_cost,#cod_area,#cod_transporte,#cod_solicitante,#cod_tipo").val("");
        //$("#registro, #documento").val("EN PROCESO");
        $("#detalle_pedido tbody").empty();
        $("#saveItem span").removeClass('parpadea');
        $(".seleccion").fadeOut();
        $("#registro, #documento")
            .removeClass('aprobado','emitido')
            .addClass('proceso');

        /*vistaItem = 0;

        $.post(RUTA+"pedidos/newRequest", {data:0},
            function (data, textStatus, jqXHR) {
                $("#numero").val(data.numero);
                $("#cod_pedido").val(data.codigo);
            },
            "json"
        );*/

        accion = "n";

        return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut()

        return false;
    });

    $("#almacen").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_almacen").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#listaAlmacen").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_almacen").val($(this).attr("href"));
        $("#almacen").val($(this).text());

        $.post(RUTA+"ingresos/nroingreso", {data:$("#cod_almacen").val()},
            function (data, textStatus, jqXHR) {
                $("#nro_ingreso").val(data.guia_nmr);
                $("#movalmacen").val(data.mov_nmr);
            },
            "json"
        );

        $(this).parent().parent().parent().slideUp();
        $("#saveDoc span").addClass('parpadea');

        return false;
    });

    $("#importOrd").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA+"ingresos/ordenes",
            function (data, textStatus, jqXHR) {
                $("#lista_ordenes tbody")
                    .empty()
                    .append(data);
                $("#modalOrdenes").fadeIn();      
            },
            "text"
        );

        return false;
    });
})