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
                $("#tipoPedido").val(data.ctipmov);
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

        let relleno = obtenerDetallesPedido();

        if($("#pedido").val() == ""){
            mostrarMensaje("msj_error","Seleccione el pedido a procesar");
            return false
        }else if(detallesSeleccionados() <= 0 ){
            mostrarMensaje("msj_error","Seleccione por lo menos un item");
            return false;
        }
    
        $(this).parent().parent().parent().fadeOut();

        //pasar los datos de la cabecera del pedido a la orden
        $.post(RUTA+"ordenes/datosProforma", {ident:$("#id_entidad").val(),pedido:$("#pedido").val()},
            function (data, textStatus, jqXHR) {
                $("#proyectoOrd").val( $("#proyecto").text() );
                $("#areaOrd").val( $("#area").text() );
                $("#costosOrd").val( $("#costos").text() );
                $("#transporteOrd").val( $("#transporte").text() );
                $("#conceptoOrd").val( $("#concepto").text() );
                $("#detalleOrd").val( $("#espec_items").text() );

                $("#tipoOrd").val( $("#tipo").text() );
                $("#entidad").val( $("#tabla_detalle_pedidos tbody tr").find('td').eq(7).text() );
                $("#ruc").val( $("#tabla_detalle_pedidos tbody tr").find('td').eq(7).data('ruc') );

                $("#monedaOrd").val(data.moneda);
                $("#condpago").val(data.pago);
                $("#condentrega").val(data.entrega);
                $("#mon_abrevia").val(data.abrevia);
                $("#idmoneda").val(data.idmoneda);
                $("#cotizacion").val(data.cotizacion);
        
                $("#detalle_orden tbody")
                    .empty()
                    .append(relleno);
            },
            "json"
        );

        return false;
    });

    $("#btnCancelarPedido").click(function (e) { 
        e.preventDefault();
        
        $(".datoCabecera, #tabla_detalle_pedidos tbody").empty();

        $(this).parent().parent().parent().fadeOut();
        
        return false;
    });

    $("#btnGenDoc").click(function (e) { 
        e.preventDefault();

        if( $("#entrega").val() == ""){
            mostrarMensaje("msj_error","Por favor indique una fecha de entrega");
            return false;
        }else if ($("#cod_almacen").val() == ""){
            mostrarMensaje("msj_error","Por favor indique un almacen de entrega");
            return false;
        }else if ($("#detalle_orden tbody tr").length == 0 ){
            mostrarMensaje("msj_error","No se ha procesado items");
            return false;
        }


        var result = {};

        detallesparaOrden();
        let detalles = JSON.stringify(DETALLES);

        $.each($("#formProcessOrder").serializeArray(),function(){
            result[this.name] = this.value;
        })

        $.ajax({
            type: "POST",
            url: RUTA+"ordenes/enviaDatosOrden",
            data: {
                cabecera:result,
                detalles,
                codicion:0
            },
            dataType: "text",
            success: function (response) {
                if (response) {
                    $("#ordenpdf").val(response);
                    $(".insidePreview iframe").attr("src",response);
                    $("#modalPreview").fadeIn();
                }
            }
        });

        return false;
    });

    $("#lugarEntrega").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_cost").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#listaAlmacen").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_almacen").val($(this).attr("href"));
        $("#lugarEntrega").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

        return false;
    });

    $("#saveItem").click(function (e) { 
        e.preventDefault();

        if( $("#entrega").val() == ""){
            mostrarMensaje("msj_error","Por favor indique una fecha de entrega");
            return false;
        }else if ($("#cod_almacen").val() == ""){
            mostrarMensaje("msj_error","Por favor indique un almacen de entrega");
            return false;
        }else if ($("#detalle_orden tbody tr").length == 0 ){
            mostrarMensaje("msj_error","No se ha procesado items");
            return false;
        }
        
        var result = {};

        detallesparaOrden();
        let detalles = JSON.stringify(DETALLES);

        $.each($("#formProcessOrder").serializeArray(),function(){
            result[this.name] = this.value;
        })

        $.ajax({
            type: "POST",
            url: RUTA+"ordenes/grabaOrden",
            data: {
                cabecera:result,
                detalles
            },
            dataType: "text",
            success: function (response) {
                if (response) {
                    mostrarMensaje("msj_correcto","Se grabo la orden correctamente");s
                }
            }
        });

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
    let fila = "";
    let suma = 0;
    let total = 0;
    let unitario = 0;
    let entidad = "";

    TABLE.each(function(){
        if ( $(this).find('td').eq(0).children().prop("checked")){

            total = $(this).find('td').eq(0).data('unitario')* $(this).find('td').eq(5).text();
            unitario = parseFloat($(this).find('td').eq(0).data('unitario'));
            suma = suma + total;
            entidad = $(this).find('td').eq(7).data('entidadid');
            idpet =  $(this).find('td').eq(0).data('iddet');

            fila += '<tr>'+
                        '<td class="con_borde centro" data-iddet="'+idpet+'" data-entidad="'+entidad+'">'+ $(this).find('td').eq(1).text()+'</td>'+
                        '<td class="con_borde centro">'+ $(this).find('td').eq(2).text()+'</td>'+
                        '<td class="con_borde pl10">'+ $(this).find('td').eq(3).text()+'</td>'+
                        '<td class="con_borde centro">'+ $(this).find('td').eq(4).text()+'</td>'+
                        '<td class="con_borde drch pr10">'+ $(this).find('td').eq(5).text()+'</td>'+
                        '<td class="con_borde drch pr10">'+ unitario.toFixed(2) +'</td>'+ //precio unitario
                        '<td class="con_borde drch pr10 total">'+  total.toFixed(2) +'</td>'+ //total
                        '<td class="con_borde"></td>'+
                        '<td class="con_borde centro">'+ $("#nropedido").val()+'</td>'+
                    '</tr>'
        }
    })

    $("#precioOrd").val(suma.toFixed(2));
    $("#id_entidad").val(entidad);
    
    return fila;
}

function detallesparaOrden(){
    DETALLES = [];

    let TABLE = $("#detalle_orden tbody > tr");

    TABLE.each(function(){
        var ITEM = $(this).find('td').eq(0).text(),
            CODIGO = $(this).find('td').eq(1).text(),
            DESCRIP = $(this).find('td').eq(2).text(),
            UND = $(this).find('td').eq(3).text(),
            CANT = $(this).find('td').eq(4).text(),
            PUNIT = $(this).find('td').eq(5).text(),
            TOTAL = $(this).find('td').eq(6).text(),
            NPARTE = $(this).find('td').eq(7).text(),
            PEDIDO = $(this).find('td').eq(8).text();
            IDDET = $(this).find('td').eq(0).data('iddet'),
            ENTIDAD = $(this).find('td').eq(0).data('entidad'),

        
        item = {};

        if (ITEM !== '') {  
            item['item'] = ITEM;
            item['codigo'] = CODIGO;
            item['descripcion'] = DESCRIP;
            item['unidad'] = UND;
            item['cantidad'] = CANT;
            item['punit'] = PUNIT;
            item['total'] = TOTAL;
            item['nparte'] = NPARTE;
            item['pedido'] = PEDIDO;
            item['iddet'] = IDDET;
            item['entidad'] = PEDIDO;
        }

        DETALLES.push(item);
    })

    return DETALLES;
}
