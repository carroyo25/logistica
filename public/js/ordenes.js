$(function(){
    activar_opcion();

    var accion = "";

    $("#newreg").click(function (e) { 
        e.preventDefault();
       
        abrirVentanaEspera();

        $.post(RUTA+"ordenes/genNumeroOrden", {user:$("#elaborado").val()},
            function (data, textStatus, jqXHR) {
                $("#numOrd").val(data);
                $("#modalProcessOrden").fadeIn();

                cerrarVentanaEspera();
            },
            "text"
        );

        return false;
    });

    $("#btnImpPedido").click(function (e) { 
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"ordenes/pedidosCostos",
            function (data, textStatus, jqXHR) {
                $("#tabla_lista_pedidos tbody")
                    .empty()
                    .append(data);
                
                $("#modalPedidos").fadeIn();
                cerrarVentanaEspera();

            },
            "text"
        );

        return false;
    });

    $(".buttonClose").click(function (e) { 
        e.preventDefault();

        $(".insidePreview iframe").attr("src","");
        $(this).parent().fadeOut();

        return false;
    });

    $("#tabla_lista_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();
        
        abrirVentanaEspera();

        $.post(RUTA+"ordenes/caberaPedidoID", {id:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#pedido").val(data.id_regmov);
                $("#cod_transporte").text(data.ctiptransp);
                $("#cod_proyecto").val(data.ncodpry);
                $("#cod_area").val(data.ncodarea);
                $("#cod_costos").val(data.ncodcos);
                $("#cod_solicitante").val(data.ncodper);
                $("#cod_estdoc").text(data.nEstadoDoc);
                $("#cod_registro").text(data.nEstadoReg);
                $("#cod_solicitante").text(data.ncodper);
                $("#cod_tipo").text(data.ctipmov);
                $("#estado").text(data.nflgactivo);
                $("#atencion").text(data.nNivAten);
                $("#nropedido").val(data.cnumero);
                $("#numero").text(data.cnumero);
                $("#fecha").text(data.ffechadoc);
                $("#usuario").text(data.id_cuser);
                $("#proyecto").text(data.ccodpry + ' ' + data.cdespry);
                $("#area").text(data.ccodarea + ' ' + data.cdesarea);
                $("#costos").text(data.ccodcos + ' ' + data.cdescos);
                $("#transporte").text(data.cod_transporte + ' ' + data.transporte);
                $("#concepto").text(data.cconcepto);
                $("#solicitante").text(data.dni + ' ' + data.apellidos + ',' + data.nombres);
                $("#registro").text(data.estado);
                $("#documento").text(data.estado);
                $("#fechaven").text(data.ffechaven);
                $("#tipo").text( data.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS");
                $("#espec_items").text(data.mdetalle);

                    $.post(RUTA+"ordenes/detallesPedido", {id:data.id_regmov},
                        function (data, textStatus, jqXHR) {
                            $("#tabla_detalle_pedidos tbody")
                            .empty()
                            .append(data);

                            cerrarVentanaEspera();
                        },
                        "text"
                    );
            },
            "json"
        );
        return false;
    });

    $("#btnAceptarPedido").click(function (e) { 
        e.preventDefault();

        if($("#pedido").val() == ""){
            mostrarMensaje("msj_error","Seleccione el pedido a procesar");
            return false
        }else if(detallesSeleccionados() <= 0){
            mostrarMensaje("msj_error","Seleccione por lo menos un item");
            return false;
        }
    
        $(this).parent().parent().parent().fadeOut();

        $("#detalle_orden tbody")
            .empty()
            .append(obtenerDetallesPedido());

        return false;
    });
})

function detallesSeleccionados(){
    let TABLE = $("#tabla_detalle_pedidos tbody tr");
    let sw = 0;

    TABLE.each(function(){
        if ($(this).find('td').eq(0).children().prop("checked")){
            sw++;
        }
    })

    return sw;
}

function obtenerDetallesPedido(){
    let TABLE = $("#tabla_detalle_pedidos tbody tr");
    let fila = "No se ve";

    TABLE.each(function(){
        if ( $(this).find('td').eq(0).children().prop("checked")){

            let total = $(this).find('td').eq(0).data('unitario')* $(this).find('td').eq(5).text();
            let unitario = parseFloat($(this).find('td').eq(0).data('unitario'));

            fila += '<tr>'+
                        '<td class="con_borde centro">'+ $(this).find('td').eq(1).text()+'</td>'+
                        '<td class="con_borde centro">'+ $(this).find('td').eq(2).text()+'</td>'+
                        '<td class="con_borde pl10">'+ $(this).find('td').eq(3).text()+'</td>'+
                        '<td class="con_borde centro">'+ $(this).find('td').eq(4).text()+'</td>'+
                        '<td class="con_borde drch pr10">'+ $(this).find('td').eq(5).text()+'</td>'+
                        '<td class="con_borde drch pr10">'+ unitario.toFixed(2) +'</td>'+ //precio unitario
                        '<td class="con_borde drch pr10">'+  total.toFixed(2) +'</td>'+ //total
                        '<td class="con_borde"></td>'+
                        '<td class="con_borde centro">'+ $("#nropedido").val()+'</td>'+
                    '</tr>'
        }
    })

    return fila;
}
