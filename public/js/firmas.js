$(function(){
    activar_opcion();

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#orden").val($(this).attr('href'));
        $("#pedido").val($(this).data('idpedido'));
        $("#nro_pedido").val($(this).data('nropedido'));

        obtenerOrden($("#orden").val());

        $("#modalProcess").fadeIn();

        return false;
    });

    $(".tabs li").on("click","a",function (e) {
        e.preventDefault();

        var idx = "#"+$(this).attr("href");
        var id  =  $(this).attr("href");

        $(".tabs li a").removeClass("option_tab_selected");
        $(this).addClass("option_tab_selected");

        $(".tab").hide();
        $(idx).show();

        switch (id) {
            case "tab1":
                obtenerOrden($("#orden").val());
                break;
            case "tab2":
                obtenerPedido($("#pedido").val());
                break;
        }

        return false;
    })

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut()

        $.post(RUTA+"firmas/listaOrdenes", {data:0},
            function (data, textStatus, jqXHR) {
                $("#tabla_pedidos tbody")
                    .empty()
                    .append(data);            
            },
            "text"
        );
    
        return false;
    });

    //ventana de observaciones
    $("#addObservation").on("click", function (e) {
        e.preventDefault();

        var date = fechaActual();

        var row = '<tr class="h35px">'+
                        '<td class="con_borde pl20 mayusculas" data-grabar="on">'+$(".userData h3").text()+'</td>'+
                        '<td class="con_borde centro"><input type="date" class="sin_borde" value="'+ date +'" readonly></td>'+
                        '<td class="con_borde"><input type="text" class="sin_borde pl20 w100p h35px" placeholder="Escriba su comentario"></td>'+
                        '<td class="con_borde centro"><a href="#" id="deleteComment"><i class="far fa-trash-alt"></i></a></td>'+
                    '</tr>';

        $('#table_observacion > tbody tr:eq(0)').before(row);

        return false;
    });

    $("#closeModalObservation").on("click", function (e) {
        e.preventDefault();

        $("#modalObservaciones").fadeOut();

        return false;
    });

    $("#table_observacion tbody").on("click","a", function (e) {
        e.preventDefault();

        if ($(this).attr("id") == "saveComment"){
            $.post(RUTA+"firmas/newComment", { user:$("#id_user").text(),
                                             date:fechaActual(),
                                             comment:$(this).parent().parent().find('td').eq(2).children().val(),
                                             regmov:$("#orden").val()
                                        },
                function (data, textStatus, jqXHR) {
                    
                },
                "text"
            );
        }else {
            $(this).parent().parent().remove();
        }
        return false;
    });

    //firmar orden
    $("#btnSignature").on("click", function (e) {
        e.preventDefault();

        var cargo = $("#cargo_user").text();

        $.post(RUTA + "firmas/signature", {cod:$("#orden").val(),car:cargo},
            function (data, textStatus, jqXHR) {
                switch (data) {
                    case "7204":
                        $("#firma_log")
                            .removeClass("firma_pendiente")
                            .addClass("firma_confirmada")
                            .html('<span><i class="far fa-check-square"></i> Logística</span>');
        
                        break;
                    case "1029":
                        $("#firma_fin")
                            .removeClass("firma_pendiente")
                            .addClass("firma_confirmada")
                            .html('<span><i class="far fa-check-square"></i> Finanzas</span>');
        
                        break;
                    case "7152":
                        $("#firma_ope")
                            .removeClass("firma_pendiente")
                            .addClass("firma_confirmada")
                            .html('<span><i class="far fa-check-square"></i> Operaciones</span>');
                        break;
                    case "1030":
                        $("#firma_ope")
                            .removeClass("firma_pendiente")
                            .addClass("firma_confirmada")
                            .html('<span><i class="far fa-check-square"></i> Operaciones</span>');
        
                        break;
                }
            },
            "text"
        );
    
        return false;
    });

    $("#commentItem").click(function (e) { 
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"ordenes/consultaObservaciones", {orden:$("#orden").val()},
            function (data, textStatus, jqXHR) {
                cerrarVentanaEspera();
 
                $("#table_observacion tbody")
                 .empty()
                 .append(data);
                 
                $("#modalComentarios").fadeIn();
            },
            "text"
        );
 
        return false;
    });

    $(".buttonCloseAction").click(function (e) { 
        e.preventDefault();

        let observaciones = JSON.stringify(obtenerComentarios());
        
        $.post(RUTA+"ordenes/observaciones", {observaciones},
            function (data, textStatus, jqXHR) {
                
            },
            "text"
        );

        $(this).parent().fadeOut();

        return false;
    });

    $("#btnVerProformas").click(function (e) { 
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"adjudicacion/proformasPdf", {cod:$("#pedido").val()},
            function (data, textStatus, jqXHR) {
                $("#atachList")
                    .empty()
                    .append(data);
                cerrarVentanaEspera();
                $("#viewAtach").fadeIn();
            },
            "text"
        );

        return false;
    });

    //mostrar el archivo adjunto
    $("#atachList").on("click","a", function (e) {
        e.preventDefault();

        $("#previewAttach iframe").attr("src",$(this).attr("href"));

        return false;
    });

    $("#closeViewAtach").click(function (e) { 
        e.preventDefault();
        
        $("#previewAttach iframe").attr("src","");
        $(this).parent().fadeOut();

        return false;
    });
})

function obtenerOrden(codigo){
    $.ajax({
        type: "POST",
        url: RUTA+"firmas/ordHeader",
        data: { 
            cod:codigo
        },
        dataType: "json",
        success: function (response) {
            $("#numOrd").val(response.numOrd);
            $("#fechaOrd").val(response.fechaOrd);
            $("#elaborado").val(response.elaborado);
            $("#proyectoOrd").val(response.proyectoOrd);
            $("#areaOrd").val(response.areaOrd);
            $("#costosOrd").val(response.costosOrd);
            $("#transporteOrd").val(response.transporteOrd);
            $("#conceptoOrd").val(response.conceptoOrd);
            $("#detalleOrd").val(response.detalleOrd);
            $("#precioOrd").val(response.precioOrd);
            $("#monedaOrd").val(response.monedaOrd);
            $("#tipoOrd").val(response.tipoOrd);
            $("#entidad").val(response.entidad);
            $("#ruc").val(response.ruc);
            $("#logistica").val(response.logistica);
            $("#operaciones").val(response.operaciones);
            $("#finanzas").val(response.finanzas);

            $("#atencion")
                .val(response.atencion)
                .addClass(response.atencion);

            if (response.logistica == null){
                $("#firma_log")
                    .addClass("firma_pendiente")
                    .html('<span><i class="far fa-square"></i> Logística</span>');
            }else{
                $("#firma_log")
                .addClass("firma_confirmada")
                .html('<span><i class="far fa-check-square"></i> Logística</span>');
            }

            if (response.finanzas == null){
                $("#firma_fin")
                .addClass("firma_pendiente")
                .html('<span><i class="far fa-square"></i> Finanzas</span>');
            }else{
                $("#firma_fin")
                .addClass("firma_confirmada")
                .html('<span><i class="far fa-check-square"></i> Finanzas</span>');
            }

            if (response.operaciones == null){
                $("#firma_ope")
                .addClass("firma_pendiente")
                .html('<span><i class="far fa-square"></i> Operaciones</span>');
            }else{
                $("#firma_ope")
                .addClass("firma_confirmada")
                .html('<span><i class="far fa-check-square"></i> Operaciones</span>');
            }

            $.post(RUTA+"firmas/orcDetails", {cod:codigo,pedido:$("#nro_pedido").val()},
                function (data, textStatus, jqXHR) {
                    $("#detalle_orden tbody")
                        .empty()
                        .append(data);
                },
                "text"
            );
        }
    });
}

function obtenerPedido(codigo){
    $.ajax({
        type: "POST",
        url: RUTA+"firmas/pedHeader",
        data: {cod:codigo},
        dataType: "json",
        success: function (response) {
            $("#estado").val(response.nflgactivo);
            $("#atencion").val(response.nNivAten);
            $("#numero").val(response.cnumero);
            $("#fecha").val(response.ffechadoc);
            $("#usuario").val(response.id_cuser);
            $("#proyecto").val(response.ccodpry + ' ' + response.cdespry);
            $("#area").val(response.ccodarea + ' ' + response.cdesarea);
            $("#costos").val(response.ccodcos + ' ' + response.cdescos);
            $("#transporte").val(response.cod_transporte + ' ' + response.transporte);
            $("#concepto").val(response.cconcepto);
            $("#solicitante").val(response.dni + ' ' + response.apellidos + ',' + response.nombres);
            //$("#registro").val(response.estado);
            //$("#documento").val(response.estado);
            $("#tipo")
                .val( response.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS")
                .addClass("desactivado");
            $("#espec_items").val(response.mdetalle);

            $.post(RUTA+"firmas/pedDetails", {cod:codigo},
                function (data, textStatus, jqXHR) {
                    $("#detalle_pedido tbody")
                        .empty()
                        .append(data);
                },
                "text"
            );
        }
    });
}

function obtenerComentarios() {
    COMENTARIOS = [];

    let TABLE = $("#table_observacion tbody > tr");

    TABLE.each(function(){
        var USER = $(this).find('td').eq(0).text(),
            FECHA = fechaActual(),
            COMMENT = $(this).find('td').eq(2).children().val(),
            ORDEN = $("#orden").val();
            SW = $(this).find('td').eq(0).data('grabar')

        item = {}

        if (SW == "on") {
            item['user'] = USER;
            item['fecha'] = FECHA;
            item['comment'] = COMMENT;
            item['orden'] = ORDEN;

            COMENTARIOS.push(item);
        }
    });

    return COMENTARIOS;
}
