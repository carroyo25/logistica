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
})