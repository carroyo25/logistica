$(function(){
    activar_opcion();

    var accion = "n";

    $("#newreg").click(function (e) { 
        e.preventDefault();
       
        abrirVentanaEspera();
        $("#status").val(0);

        $(".firmasTitulo div")
            .removeClass("firma_pendiente, firma_confirmada")
            .addClass("firma_pendiente");
        $(".datoCabecera, #tabla_detalle_pedidos tbody,#detalle_orden tbody").empty();
        $("#saveItem span").removeClass('parpadea');
        $("#formProcessOrder")[0].reset();

        $.post(RUTA+"ordenes/genNumeroOrden", {user:$("#elaborado").val()},
            function (data, textStatus, jqXHR) {
                $("#numOrd").val(data.numero);
                $("#orden").val(data.orden);
                $("#modalProcessOrden").fadeIn();

                cerrarVentanaEspera();
            },
            "json"
        );

        return false;
    });

    $("#btnImpPedido").click(function (e) { 
        e.preventDefault();

        if ($("#status").val() == 0) {
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
        }else {
            mostrarMensaje("msj_info","La orden no puede ser modificada, por estar en firmas");
        }

        return false;
    });

    $("#btnSendMail").click(function (e) { 
        e.preventDefault();

        let logistica = $("#logistica").val() == '1' ? true : false;
        let operaciones = $("#operaciones").val() == '1' ? true : false;
        let finanzas = $("#finanzas").val() == '1' ? true : false;

        if (logistica && operaciones && finanzas) {
            $("#dialogConfirm").fadeIn();
        }else {
            mostrarMensaje("msj_error","Debe completar las firmas.");
        }

        return false;
    });

    $("#btnYes").click(function (e) { 
        e.preventDefault();

        var result = {};

        $("#ordenpdf").val("");

        $.each($("#formProcessOrder").serializeArray(),function(){
            result[this.name] = this.value;
        })

        $.ajax({
            type: "POST",
            url: RUTA+"ordenes/correoProveedor",
            data: {
                cabecera:result,
                detalles:JSON.stringify(detallesparaOrden()),
                condicion:1
            },
            dataType: "text",
            success: function (response) {
                
            }
        });
        $(this).parent().parent().parent().parent().fadeOut();

        return false;
    });

    $("#btnNo").click(function (e) { 
        e.preventDefault();
        
        $("#dialogConfirm").fadeOut();

        return false;
    });

    $("#closeModalProcess").click(function (e) { 
        e.preventDefault();
        
        abrirVentanaEspera();
        $.post(RUTA+"ordenes/actualizaPrincipal",
            function (data, textStatus, jqXHR) {
                $(this).parent().fadeOut();
                $("#tabla_ordenes tbody")
                .empty()
                .append(data);

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

    $("#tabla_ordenes").on("click","a", function (e) {
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"ordenes/ordenesPorId", {cod:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#orden").val(data.orden);
                $("#pedido").val(data.pedido);
                $("#nropedido").val(data.nropedido);
                $("#id_entidad").val(data.id_entidad);
                $("#cod_proyecto").val(data.cod_proyecto);
                $("#cod_area").val(data.cod_area);
                $("#cod_costos").val(data.cod_costos);
                $("#cod_transporte").val(data.cod_transporte);
                $("#cod_solicitante").val(data.cod_solicitante);
                $("#cod_almacen").val(data.cod_almacen);
                $("#ordenpdf").val(data.ordenpdf);
                $("#tipoPedido").val(data.tipoPedido);
                $("#mon_abrevia").val(data.mon_abrevia);
                $("#idmoneda").val(data.idmoneda);
                $("#idpago").val(data.idpago);
                $("#identrega").val(data.identrega);
                $("#cotizacion").val(data.cotizacion);
                $("#numOrd").val(data.numOrd);
                $("#elaborado").val(data.elaborado);
                $("#proyectoOrd").val(data.proyectoOrd);
                $("#suma_orden").val(data.precioNumero);
                $("#areaOrd").val(data.areaOrd);
                $("#costosOrd").val(data.costosOrd);
                $("#conceptoOrd").val(data.conceptoOrd);
                $("#detalleOrd").val(data.detalleOrd);
                $("#precioOrd").val(data.precioOrd);
                $("#entrega").val(data.entrega);
                $("#condpago").val(data.condpago);
                $("#condentrega").val(data.condentrega);
                $("#entidad").val(data.entidad);
                $("#ruc").val(data.ruc);
                $("#atencion").val(data.atencion);
                $("#transporteOrd").val(data.transporteOrd);
                $("#lugarEntrega").val(data.lugarEntrega);
                $("#monedaOrd").val(data.monedaOrd);
                $("#tipoOrd").val(data.tipoOrd);
                $("#logistica").val(data.logistica);
                $("#operaciones").val(data.operaciones);
                $("#finanzas").val(data.finanzas);
                $("#fechaOrd").val(data.fechaOrd);
                $("#status").val(data.nEstadoDoc);
                $("#nivel_atencion").val(data.nNivAten);
                
                if (data.logistica == 1) {
                    $(".logistica")
                        .removeClass("firma_pendiente")
                        .addClass("firma_confirmada")
                }
                
                if (data.logistica == 1) {
                    $(".logistica")
                        .removeClass("firma_pendiente")
                        .addClass("firma_confirmada")
                }

                if (data.operaciones == 1) {
                    $(".operaciones")
                        .removeClass("firma_pendiente")
                        .addClass("firma_confirmada")
                }

                if (data.finanzas == 1) {
                    $(".finanzas")
                        .removeClass("firma_pendiente")
                        .addClass("firma_confirmada")
                }

                accion = "u";

                $.post(RUTA+"ordenes/detallesOrden", {cod:data.orden,pedido:data.nropedido},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_orden tbody")
                            .empty()
                            .append(data);

                            $("#modalProcessOrden").fadeIn();
                            cerrarVentanaEspera();
                    },
                    "text"
                );
            },
            "json"
        );
       
        
        return false;
    });

    $("#tabla_lista_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();
        
        abrirVentanaEspera();

        $.post(RUTA+"ordenes/caberaPedidoID", {id:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#pedido").val(data.id_regmov);
                $("#cod_transporte").val(data.ctiptransp);
                $("#cod_proyecto").val(data.ncodpry);
                $("#cod_area").val(data.ncodarea);
                $("#cod_costos").val(data.ncodcos);
                $("#cod_solicitante").val(data.ncodper);
                $("#cod_estdoc").text(data.nEstadoDoc);
                $("#cod_registro").text(data.nEstadoReg);
                $("#cod_solicitante").val(data.ncodper);
                $("#tipoPedido").val(data.ctipmov);
                $("#estado").text(data.nflgactivo);
                $("#atencion").text();
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
                $("#nivel_atencion").val(data.nNivAten);

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
                $("#idpago").val(data.idpago);
                $("#identrega").val(data.identrega);
                $("#precioOrd").val(data.total);
        
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
                condicion:0
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

        if ($("#status").val() == 1) {
            mostrarMensaje("msj_info","La orden no puede ser modificada, por estar en firmas");
            return false;
        }

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

        $.each($("#formProcessOrder").serializeArray(),function(){
            result[this.name] = this.value;
        })

        $.post(RUTA+"ordenes/grabaOrden", {accion,cabecera:result,detalles:JSON.stringify(detallesparaOrden())},
            function (data, textStatus, jqXHR) {
                if (data) {
                    $(".datoCabecera, #tabla_detalle_pedidos tbody,#detalle_orden tbody").empty();
                    $("#modalProcessOrden").fadeOut();
                    $("#saveItem span").removeClass('parpadea');
                    $("#formProcessOrder")[0].reset();

                    cerrarVentanaEspera();

                    mostrarMensaje("msj_correcto","Se grabo la orden correctamente");
                }else {
                    mostrarMensaje("msj_error","No se pudo grabar el registro");
                }
            },
            "text"
        );

        return false;
    });

   $("#cancelItem").click(function (e) { 
       e.preventDefault();
        
        $(".datoCabecera, #tabla_detalle_pedidos tbody,#detalle_orden tbody").empty();
        $("#modalProcessOrden").fadeOut();
        $("#saveItem span").removeClass('parpadea');
        $("#formProcessOrder")[0].reset();
        $(".firmasTitulo div")
            .removeClass("firma_pendiente, firma_confirmada")
            .addClass("firma_pendiente");
        
       return false
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

    $("#table_observacion tbody").on("click","a", function (e) {
        e.preventDefault();
        
        $(this).parent().parent().remove();

        return false;
    });

    $("#tabla_detalle_pedidos tbody").on("click","input", function (e) {
        
        if ( $(this).prop("checked") == true ){
            if (checkExistTable($("#detalle_orden tbody"),$(this).parent().parent().find("td").eq(2).text(),1)){
                $(this).prop("checked",false);
                mostrarMensaje("msj_error","Detalle registrado");
            }
        }
    });

    $("#singItem").click(function (e) { 
        e.preventDefault();
       
        $("#dialogAutorize").fadeIn();

        return false;
    });

    $("#btnSingYes").click(function (e) { 
        e.preventDefault();

        $.post(RUTA+"ordenes/autoriza", {orden:$("#orden").val(),
                                         pedido:$("#pedido").val(),
                                         detalles:JSON.stringify(detallesAutorizacion())},
            function (data, textStatus, jqXHR) {
                if(data){
                    mostrarMensaje("msj_correcto","Se paso la orden para su firma");
                }else{
                    mostrarMensaje("msj_error","No se completo el proceso.");
                }
                $("#dialogAutorize").fadeOut();
            },
            "text"
        );
        return false;
    });

    $("#btnSingNo").click(function (e) { 
        e.preventDefault();

        $("#dialogAutorize").fadeOut();

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
            idprod = $(this).find('td').eq(2).data('idprod');
            idunid = $(this).find('td').eq(4).data('idunid');
            factor = $(this).find('td').eq(4).data('factor');
            grabar = '0';

            fila += '<tr>'+
                        '<td class="con_borde centro" data-iddet="'+idpet+'" data-entidad="'+entidad+'" data-grabar="'+grabar+'">'+ $(this).find('td').eq(1).text()+'</td>'+
                        '<td class="con_borde centro" data-idprod="'+idprod+'">'+ $(this).find('td').eq(2).text()+'</td>'+
                        '<td class="con_borde pl10">'+ $(this).find('td').eq(3).text()+'</td>'+
                        '<td class="con_borde centro" data-idunid="'+idunid+'" data-factor="'+factor+'">'+ $(this).find('td').eq(4).text()+'</td>'+
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
            IDPROD = $(this).find('td').eq(1).data('idprod'),
            IDUND = $(this).find('td').eq(3).data('idunid'),
            FACTOR = $(this).find('td').eq(3).data('factor'),
            GRABAR = $(this).find('td').eq(0).data("grabar")

        
        item = {};

        if ( GRABAR == '0' ) {  
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
            item['entidad'] = ENTIDAD;
            item['idprod'] = IDPROD;
            item['idunid'] = IDUND;
            item['factor'] = FACTOR;
            item['grabar'] = GRABAR;

            DETALLES.push(item);
        }
    })

    return DETALLES;
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

function detallesAutorizacion(){
    FIRMAS = [];

    let TABLE = $("#detalle_orden tbody > tr");

    TABLE.each(function(){
        var IDDET = $(this).find('td').eq(0).data('iddet'),
            ENTIDAD = $(this).find('td').eq(0).data('entidad')
            
        item = {};

        if ( IDDET !== '' ) {  
            item['iddet'] = IDDET;
            item['entidad'] = ENTIDAD;
            

            FIRMAS.push(item);
        }
    })

    return FIRMAS;
}
