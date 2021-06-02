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
            case "tab3":
                obtenerMensajes($("#orden").val());
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

    //pestania de observaciones
    $("#addObservation").on("click", function (e) {
        e.preventDefault();

        var date = fechaActual();

        var $row = '<tr><td class="con_borde pl20 mayusculas">'+$(".userData h3").text()+'</td>'+
                        '<td class="con_borde centro"><input type="date" value="'+ date +'" readonly></td>'+
                        '<td class="con_borde"><input type="text" class="sin_borde pl20"></td>'+
                        '<td class="con_borde centro"><a href="#" id="saveComment"><i class="far fa-save"></i></a></td>'+
                        '<td class="con_borde centro"><a href="#" id="deleteComment"><i class="far fa-trash-alt"></i></a></td>'+
                    '</tr>';

        $("#table_observacion").append($row);

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
            $("#numOrd").val(response.numero);
            $("#fechaOrd").val(response.fechadoc);
            $("#elaborado").val(response.elaborado);
            $("#proyectoOrd").val(response.proyecto);
            $("#areaOrd").val(response.area);
            $("#costosOrd").val(response.costos);
            $("#transporteOrd").val(response.transporte);
            $("#conceptoOrd").val(response.concepto);
            $("#detalleOrd").val(response.detalle);
            $("#precioOrd").val(response.total);
            $("#monedaOrd").val(response.moneda);
            $("#tipoOrd").val(response.tipo);
            $("#entidad").val(response.entidad);
            $("#ruc").val(response.ruc);
            $("#atencion")
                .val(response.atencion)
                .addClass(response.atencion.toLowerCase());

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

function obtenerMensajes(codigo) {
    $.post(RUTA+"firmas/listComments", {cod:codigo},
        function (data, textStatus, jqXHR) {
            $("#table_observacion tbody")
                .empty()
                .append(data);
        },
        "text"
    );
}
