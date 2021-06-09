$(function(){
    activar_opcion();

    ///llamar a la notas de ingreso
    $("#tabla_ingresos tbody").on("click","a", function (e) {
        e.preventDefault();

        $(".seleccion").fadeOut();
        $("#waitmodal").fadeIn();

        $.post(RUTA+"calidad/llamaIngresoPorId", {nota:$(this).attr('href')},
            function (data, textStatus, jqXHR) {
                $("#waitmodal").fadeOut();
                $("#importOrd").addClass("oculto");
                $("#modalProcess").fadeIn();

                $("#id_ingreso").val(data.id_ingreso);
                $("#id_entidad").val(data.id_entidad);
                $("#cod_almacen").val(data.cod_almacen);
                $("#cod_movimento").val(data.cod_movimento);
                $("#cod_autoriza").val(data.cod_autoriza);
                $("#cod_proyecto").val(data.cod_proyecto);
                $("#cod_area").val(data.cod_area);
                $("#cod_costos").val(data.cod_costos);
                $("#order_file").val(data.order_file);
                $("#cargo_almacen").val(data.cargo_almacen);
                $("#idorden").val(data.idorden);
                $("#idpedido").val(data.idpedido);
                $("#estado").val(data.estado);
                
                $("#fechadoc").val(data.fechadoc);
                $("#fechacont").val(data.fechacont);
                $("#almacen").val(data.almacen);
                $("#fechadoc").val(data.fechadoc);
                $("#fechacont").val(data.fechacont);
                $("#nro_ingreso").val(data.nro_ingreso);
                $("#proyecto").val(data.proyecto);
                $("#area").val(data.area);
                $("#costos").val(data.costos);
                $("#solicita").val(data.solicita);
                $("#aprueba").val(data.aprueba);
                $("#tipomov").val(data.tipomov);
                $("#movalmacen").val(data.movalmacen);
                $("#nrord").val(data.nrord);
                $("#nroped").val(data.nroped);
                $("#nruc").val(data.nruc);
                $("#nroguia").val(data.nroguia);
                $("#entidad").val(data.entidad);
                $("#concepto").val(data.concepto);
                $("#espec").val(data.espec);
                $("#registro").val(data.registro);
                $("#documento")
                    .val(data.documento)
                    .removeClass("proceso")
                    .addClass(data.documento.toLowerCase());
                $("#order_file").val(data.order_file);

                $.post(RUTA+"calidad/llamarDetallesCodigo", {index:data.id_ingreso},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_ingreso tbody")
                            .empty()
                            .append(data);

                        var items = $("#detalle_ingreso tbody tr").length;

                        $("#items").val(items);
                    },
                    "text"
                );
            },
            "json"
        );

        return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut()
        $("#waitmodal").fadeIn();

        $.post(RUTA+"calidad/refreshMain",
            function (data, textStatus, jqXHR) {
                $("#waitmodal").fadeOut();
                $("#tabla_ingresos tbody")
                    .empty()
                    .append(data);
            },
            "text"
        );

        return false;
    });

    //opciones de la tabla de detalles
    $("#detalle_ingreso tbody").on("click","a", function (e) {
        e.preventDefault();
        if ( $(this).data("action") == "register") {

            var descrip = $(this).parent().parent().find('td').eq(3).text(),
                idprod  = $(this).parent().parent().find('td').eq(1).data('idprod')

            $("#descripProducto").text(descrip);
            $("#codigoProducto").text(idprod);

            $.post(RUTA+"calidad/llamarSeries", {index:$("#id_ingreso").val(),prod:idprod},
                function (data, textStatus, jqXHR) {
                    $("#detalle_series tbody")
                    .empty()
                    .append(data);
                },
                "text"
            );

            $("#modalSerie").fadeIn();
        }

        return false;
    });

    $("#btnConfirmSerial").on("click", function (e) {
        e.preventDefault();

        $("#modalSerie").fadeOut();
        $("#detalle_series tbody").empty();

        return false;
    });

    //vista de la orden 
    $("#orderDetail").on("click", function (e) {
        e.preventDefault();

        $("#modalOrderDetail .insidePreview object")
                .attr("data","")
                .attr("data",$("#order_file").val());

        $("#modalOrderDetail").fadeIn();

        return false;
    });

    $("#closeModalOrderDetail").on("click", function (e) {
        e.preventDefault();

        $("#modalOrderDetail").fadeOut();

        return false;
    });

    //vista de documentos adjuntos
    $("#docsAtach").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + "calidad/llamarAdjuntos", {index:$("#id_ingreso").val()},
        function (data, textStatus, jqXHR) {
            $("#atachList")
                .empty()
                .append(data);
        },
        "text"
        );

        /*$("#modalPreview .insidePreview iframe")
                    .attr("src","")
                    .attr("src",response);*/

        $("#modalPreview").fadeIn(); 

        return false;
    });

    //mostrar el archivo adjunto
    $("#atachList").on("click","a", function (e) {
        e.preventDefault();

        $("#modalPreview object").attr("data",$(this).attr("href"));

        return false;
    });

    $("#closeModalPreview").on("click", function (e) {
        e.preventDefault();

        $("#modalPreview").fadeOut();

        return false;
    });

    //grabar las observaciones
    $("#saveDoc").on("click", function (e) {
        e.preventDefault();

        getDetails();

        $.post(RUTA+"calidad/cambiarEstado", {ningreso:$("#id_ingreso").val(),
                                             detalles:JSON.stringify(DETALLES)},
            function (data, textStatus, jqXHR) {
                if (data){
                    mostrarMensaje("msj_correcto","Observaciones actualizadas");
                }else{
                    mostrarMensaje("msj_error","No se anotaron las observaciones");
                }
            },
            "text"
        );

        return false;
    });
})

function getDetails(){
    DETALLES = [];

    var TABLA = $("#detalle_ingreso tbody > tr");

    TABLA.each(function(){
        var NIDDETA     = $(this).find('td').eq(1).data('iddeta'),
            NESTADO     = $(this).find("select[name='estado']").val(),
            IDDETPED    = $(this).find('td').eq(1).data('iddetpedido'),
            IDDERORD    = $(this).find('td').eq(1).data('iddetorden'),
            OBSERVAC    = $(this).find('td').eq(7).children().val(),

            item = {};

            if (NIDDETA !== ''){
                item["nestado"]     = NESTADO;
                item["niddeta"]     = NIDDETA;
                item["iddetped"]    = IDDETPED;
                item["iddetord"]    = IDDERORD;
                item["observ"]      = OBSERVAC;
            }

            DETALLES.push(item);
    })

    return DETALLES;
}

