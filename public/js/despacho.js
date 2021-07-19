var accion          = "n";

$(function(){
    activar_opcion();

    $("#newreg").on("click", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();
        $(".process_header, .details_item").removeClass("desactivado");
        $(".sides_process div").removeClass("no_modificar");
        $("#formProcess")[0].reset();

        $("#registro, #documento").val("EN PROCESO");
        $("#detalle_pedido tbody").empty();
        $("#saveItem span").removeClass('parpadea');
        $("#tipo").removeClass("desactivado");
        $(".seleccion").fadeOut();
        $("#registro, #documento")
            .removeClass('aprobado','emitido')
            .addClass('proceso');

        /*
        $.post(RUTA+"despachos/newRequest", {data:0},
            function (data, textStatus, jqXHR) {
                $("#numero").val(data.numero);
                $("#cod_pedido").val(data.codigo);
            },
            "json"
        );*/

        accion = "n";

        return false;
    });

    $("#tabla_guias tbody").on("click","a", function (e) {
        e.preventDefault();

        $.post(RUTA+"despacho/salidaId", {idx:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#id_ingreso").val(data.id_ingreso);
                $("#id_salida").val( data.id_salida );
                $("#id_entidad").val(data.id_entidad);
                $("#cod_almacen").val(data.cod_almacen);
                $("#cod_movimiento").val(data.cod_movimiento);
                $("#cod_autoriza").val(data.cod_autoriza);
                $("#cod_proyecto").val(data.cod_proyecto);
                $("#cod_area").val(data.cod_area);
                $("#cod_costos").val(data.cod_costos);
                $("#order_file").val(data.order_file);
                $("#cargo_almacen").val(data.cargo_almacen);
                $("#idorden").val(data.idorden);
                $("#idpedido").val(data.idpedido);
                $("#entidad").val(data.entidad);
                $("#guia").val(data.guia);
                $("#nrosalida").val(data.nrosalida);
                $("#movalma").val(data.movalma);
                $("#fechadoc").val(data.fechadoc);
                $("#fechacont").val(data.fechacont);
                $("#proyecto").val(data.proyecto);
                $("#solicita").val(data.solicita);
                $("#aprueba").val(data.aprueba);
                $("#almacen").val(data.almacen);
                $("#tipomov").val(data.tipomov);
                $("#nroped").val(data.nroped);
                $("#fecped").val(data.fecped);
                $("#nrord").val(data.nrord);
                $("#fecord").val(data.fecord);
                $("#espec").val(data.espec);
                $("#estadoc").val(data.estadoc);

                $("#estadoc").addClass(data.estadoc.toLowerCase());

                $.post(RUTA+"despacho/detallesGuiaId",{id:$("#id_salida").val()},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_despacho tbody")
                            .empty()
                            .append(data);
                    },
                    "text"
                );
            },
            "json"
        );
        $("#modalProcess").fadeIn();

        return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut();
    
        return false;
    });

    //importar notas de ingreso
    $("#importarIngreso").on("click",function(e){
        e.preventDefault();

        $("#modalNotas").fadeIn();
        $("#waitmodal").fadeIn();

        $.post(RUTA+"despacho/notas",
            function (data, textStatus, jqXHR) {
                $("#waitmodal").fadeOut();
                $("#listaIngresos tbody")
                    .empty()
                    .append(data);
            },
            "text"
        );
        
        return false;
    })

    $("#closeModalNotas").on("click", function (e) {
        e.preventDefault()
       
        $("#modalNotas").fadeOut();
    
        return false;
    });

    //SELECCIONAR LA NOTA DE INGRESO
    $("#listaIngresos tbody").on("click","a", function (e) {
        e.preventDefault();
        
        $("#modalNotas").fadeIn();
        $.post(RUTA+"despacho/notaId",{idx: $(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#id_ingreso").val(data[0].id_regalm);
                $("#id_salida").val(data[1].idreg);
                $("#id_entidad").val(data[0].id_centi);
                $("#cod_almacen").val(data[0].ncodalm1);
                $("#cod_movimento").val();
                $("#cod_autoriza").val(data[0].id_userAprob);
                $("#cod_proyecto").val(data[0].ncodpry);
                $("#cod_area").val(data[0].ncodarea);
                $("#cod_costos").val(data[0].ncodcos);
                $("#order_file").val(data[0].cdocPDF);
                $("#cargo_almacen").val(data[0].dcargo);
                $("#idorden").val(data[0].idref_abas);
                $("#idpedido").val(data[0].idref_pedi);
                $("#estado").val(1);
                $("#nrosalida").val(data[1].nrodoc);
                $("#movalma").val(data[1].nromov);
                $("#proyecto").val(data[0].proyecto);
                $("#solicita").val(data[0].solicita);
                $("#aprueba").val(data[0].aprueba);
                $("#almacen").val(data[0].almacen);
                $("#nroped").val(data[0].nroped);
                $("#fecped").val(data[0].fechaPedido);
                $("#nrord").val(data[0].nrorden);
                $("#fecord").val(data[0].fechaOrden);
                //$("#espec").val(data[0].cconcepto);
                $("#guia").val(data[0].cnumguia);
                $("#entidad").val(data[0].crazonsoc);

                $.post(RUTA+"despacho/detallesIngresosId",{idx:data[0].id_regalm},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_despacho tbody")
                            .empty()
                            .append(data);
                        $("#modalNotas").fadeOut();
                    },
                    "text"
                );
            },
            "json"
        );
        
        return false;
    });

    //acciones en la tabla de detalles
    $("#detalle_despacho tbody").on("click","a", function (e) {
        e.preventDefault();

        return false;
    });

    //mostrar los datos para la guia de remision 
    $("#guiaDetail").on("click", function (e) {
        e.preventDefault();
        
        if ($("#id_ingreso").val().length == 0){
            mostrarMensaje("msj_error","Seleccione una nota de ingreso");
            return false;
        }else if ($("#cod_movimiento").val().length == 0){
            mostrarMensaje("msj_error","Seleccione el tipo de movimiento");
            return false;
        }


        $.post(RUTA+"despacho/nuevoNroGuia",
            function (data, textStatus, jqXHR) {
                $("#serieguia").val('001 - ');
                $("#nroguia").val(data);
            },
            "text"
        );

        $("#modalGuia").fadeIn();

        return false;
    });

    //lista de modalidad de movimientos
    $("#tipomov").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#cod_movimento").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaMotivo").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_movimiento").val($(this).attr("href"));
        $("#tipomov").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveDoc span").addClass('parpadea');

        return false;
    });

     //lista de modalidad de movimientos
     $("#modtras").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#codmodalidadguia").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaModalidadGuia").on("click","a", function (e) {
        e.preventDefault();

        $("#codmodalidadguia").val($(this).attr("href"));
        $("#modtras").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveDoc span").addClass('parpadea');

        return false;
    });

    //lista de tipo de envio
    $("#tenvio").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#codmodalidadguia").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaTipoGuia").on("click","a", function (e) {
        e.preventDefault();

        $("#codtipoguia").val($(this).attr("href"));
        $("#tenvio").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });
    
    $("#btnCancelarGuia").on("click", function (e) {
        e.preventDefault();

        $("#formguia")[0].reset();
        $("#modalGuia").fadeOut();

        return false;
    });

    $("#btnAceptarGuia").on("click", function (e) {
        e.preventDefault();

        $("#formguia").trigger("submit");

        return false;
    });

     //lista de tipo de envio
     $("#almdest").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#codalmacendestino").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaAlmacenDestino").on("click","a", function (e) {
        e.preventDefault();

        $("#codalmacendestino").val($(this).attr("href"));
        $("#almdest").val($(this).text());
        $("#viatipodest").val($(this).data("via"));
        $("#vianomodest").val($(this).data("nombre"));
        $("#viadest").val($(this).data("nro"));
        $("#intdest").val($(this).data("interior"));
        $("#zondest").val($(this).data("zona"));
        $("#depdest").val($(this).data("dpto"));
        $("#distdest").val($(this).data("dist"));
        $("#provdest").val($(this).data("prov"));
        $("#ubigdest").val($(this).data("ubigeo"));

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //lista de tipo de envio
    $("#almorg").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#codalmacendestino").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaAlmacenOrigen").on("click","a", function (e) {
        e.preventDefault();

        $("#codalmacenorigen").val($(this).attr("href"));
        $("#almorg").val($(this).text());
        $("#viatiporg").val($(this).data("via"));
        $("#vianomorg").val($(this).data("nombre"));
        $("#nroorg").val($(this).data("nro"));
        $("#intorg").val($(this).data("interior"));
        $("#zonaorg").val($(this).data("zona"));
        $("#deporg").val($(this).data("dpto"));
        $("#distorg").val($(this).data("dist"));
        $("#provorg").val($(this).data("prov"));
        $("#ubigorg").val($(this).data("ubigeo"));

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //lista Autoriza
    $("#autoriza").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#codautoriza").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaAutoriza").on("click","a", function (e) {
        e.preventDefault();

        $("#codautoriza").val($(this).attr("href"));
        $("#autoriza").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //lista despacha
    $("#despacha").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#coddespacha").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaDespacha").on("click","a", function (e) {
        e.preventDefault();

        $("#coddespacha").val($(this).attr("href"));
        $("#despacha").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //lista Destinatario
    $("#destinatario").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#coddestinatario").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    //DESTINATARIOS
    $("#listaDestinatario").on("click","a", function (e) {
        e.preventDefault();

        $("#coddestinatario").val($(this).attr("href"));
        $("#destinatario").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });
    
    $("#btnCancelarGuia").on("click", function (e) {
        e.preventDefault();

        $("#formguia")[0].reset();
        $("#modalGuia").fadeOut();

        return false;
    });

    // //lista de entidades
    $("#raztransp").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#codentidad").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaEntidad").on("click","a", function (e) {
        e.preventDefault();

        $("#codentidad").val($(this).attr("href"));
        $("#raztransp").val($(this).text());
        $("#ructransp").val($(this).data("ruc"));
        $("#dirtransp").val($(this).data("direccion"));
        $("#representate").val($(this).data(""));

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //vista previa de la nota de salida
    $("#previewSalida").on("click", function (e) {
        e.preventDefault();

        if ($("#nrosalida").val().length == 0) {
            mostrarMensaje("msj_error","Selecione una guia de ingreso");
            return false;
        }else if ($("#cod_movimiento").val().length == 0){
            mostrarMensaje("msj_error","Selecione el tipo de movimiento");
            return false;
        }

        getDetails();

        $.ajax({
            type: "POST",
            url: RUTA+"despacho/preview",
            data:{  proyecto: $("#proyecto").val(),
                    origen: $("#almacen").val(),
                    movimiento: $("#tipomov").val(),
                    fecha: $("#fechadoc").val(),
                    orden: $("#nrord").val(),
                    pedido: $("#nroped").val(),
                    entidad: $("#entidad").val(),
                    guia: $("#guia").val(),
                    autoriza: $("#aprueba").val(),
                    cargo:$("#cargo_almacen").val(),
                    condicion: 0,
                    tipo:"S",
                    ndoc:$("#nrosalida").val(),
                    details:JSON.stringify(DETALLES)},
            dataType: "text",
            success: function (response) {
                
                $("#modalVistaNotaSalida .insidePreview iframe")
                    .attr("src","")
                    .attr("src",response);

                    $("#modalVistaNotaSalida").fadeIn();                
            }
        });

        
        return false;
    });

    $(".buttonClose").on("click", function (e) {
        $(this).parent().fadeOut();
    });

   //vista previa de la guia de salida cuando consulta
    $("#previewGuia").on("click", function (e) {
        e.preventDefault();

        let idx = $("#id_guia").val();

        return false;
    });

    //grabar y enviar la guia
    $("#formguia").on("submit", function (e) {
        e.preventDefault();

        getDetails();

        $.ajax({
            type: "POST",
            url: RUTA+"despacho/guiaRemision",
            data: { codmodalidadguia:$("#codmodalidadguia").val(),
                    codtipoguia:$("#codtipoguia").val(),
                    codalmacendestino:$("#codalmacendestino").val(),
                    codalmacenorigen:$("#codalmacenorigen").val(),
                    codautoriza:$("#codautoriza").val(),
                    coddespacha:$("#coddespacha").val(),
                    coddestinatario:$("#coddestinatario").val(),
                    codentidad:$("#codentidad").val(),
                    codchofer:$("#codchofer").val(),
                    serieguia:$("#serieguia").val(),
                    nroguia:$("#nroguia").val(),
                    packinlist:$("#packinlist").val(),
                    fecemin:$("#fecemin").val(),
                    feenttrans:$("#feenttrans").val(),
                    ruc:$("#ruc").val(),
                    razondest:$("#razondest").val(),
                    direccdest:$("#direccdest").val(),
                    almorg:$("#almorg").val(),
                    viatiporg:$("#viatiporg").val(),
                    vianomorg:$("#vianomorg").val(),
                    nroorg:$("#nroorg").val(),
                    intorg:$("#intorg").val(),
                    zonaorg:$("#zonaorg").val(),
                    deporg:$("#deporg").val(),
                    distorg:$("#distorg").val(),
                    provorg:$("#provorg").val(),
                    ubigorg:$("#ubigorg").val(),
                    mottrans:$("#mottrans").val(),
                    modtras:$("#modtras").val(),
                    tenvio:$("#tenvio").val(),
                    bultos:$("#bultos").val(),
                    peso:$("#peso").val(),
                    observaciones:$("#observaciones").val(),
                    autoriza:$("#autoriza").val(),
                    despacha:$("#despacha").val(),
                    destinatario:$("#destinatario").val(),
                    raztransp:$("#raztransp").val(),
                    ructransp:$("#ructransp").val(),
                    dirtransp:$("#dirtransp").val(),
                    representate:$("#representate").val(),
                    almdest:$("#almdest").val(),
                    vianomodest:$("#vianomodest").val(),
                    intdest:$("#intdest").val(),
                    zondest:$("#zondest").val(),
                    viatipodest:$("#viatipodest").val(),
                    nrodest:$("#nrodest").val(),
                    depdest:$("#depdest").val(),
                    distdest:$("#distdest").val(),
                    provdest:$("#provdest").val(),
                    ubigdest:$("#ubigdest").val(),
                    dnicond:$("#dnicond").val(),
                    detcond:$("#detcond").val(),
                    licencia:$("#licencia").val(),
                    certificado:$("#certificado").val(),
                    marca:$("#marca").val(),
                    placa:$("#placa").val(),
                    configveh:$("#configveh").val(),
                    detalles:$("#detalles").val(),
                    proyecto:$("#proyecto").val(),
                    costos:$("#cod_costos").val(),
                    salida:$("#id_salida").val(),
                    details:JSON.stringify(DETALLES)},
            dataType: "text",
            success: function (response) {
                $("#modalVistaGuia .insidePreview iframe").attr("src",response);
                $("#modalVistaGuia").fadeIn();
            }
        });

        return false;
    });

    //grabar el documento de salida
    $("#grabarDoc").on("click", function (e) {
        e.preventDefault();

        getDetails();

        $.post(RUTA+"despacho/grabaSalida", {
            id_ingreso:$("#id_ingreso").val(),
            id_salida:$("#id_salida").val(),
            id_entidad:$("#id_entidad").val(),
            cod_almacen:$("#cod_almacen").val(),
            cod_movimiento:$("#cod_movimiento").val(),
            cod_autoriza:$("#cod_autoriza").val(),
            cod_proyecto:$("#cod_proyecto").val(),
            cod_area:$("#cod_area").val(),
            cod_costos:$("#cod_costos").val(),
            order_file:$("#order_file").val(),
            cargo_almacen:$("#cargo_almacen").val(),
            idorden:$("#idorden").val(),
            idpedido:$("#idpedido").val(),
            entidad:$("#entidad").val(),
            docguia:$("#docguia").val(),
            nrosalida:$("#nrosalida").val(),
            movalma:$("#movalma").val(),
            fechadoc:$("#fechadoc").val(),
            fechacont:$("#fechacont").val(),
            proyecto:$("#proyecto").val(),
            solicita:$("#solicita").val(),
            aprueba:$("#aprueba").val(),
            almacen:$("#almacen").val(),
            tipomov:$("#tipomov").val(),
            nroped:$("#nroped").val(),
            fecped:$("#fecped").val(),
            nrord:$("#nrord").val(),
            fecord:$("#fecord").val(),
            espec:$("#espec").val(),
            estado:$("#estado").val(),
            ingreso:$("id_ingreso").val(),
            details:JSON.stringify(DETALLES)
        },
            function (data, textStatus, jqXHR) {
                if (data){
                    mostrarMensaje("msj_correcto","Registro insertado...");
                    $("#modalProcess").fadeOut();
                }else{
                    mostrarMensaje("msj_error","Error.. no se inserto el registro");
                }
            },
            "texto"
        );

        return false;
    });
})

function getDetails(){
    DETALLES = [];

    var TABLA = $("#detalle_despacho tbody > tr");

    TABLA.each(function(){
        var CODITEM     = $(this).find('td').eq(2).text(),
            DESCRIPCION = $(this).find('td').eq(3).text(),
            UNIDAD      = $(this).find('td').eq(4).text(),
            CANTRQ      = $(this).find('td').eq(5).text(),
            CANTDES     = $(this).find('td').eq(6).children().val(),
            FACTOR      = $(this).find('td').eq(1).data('factor'),
            CODUNI      = $(this).find('td').eq(1).data('coduni'),
            IDPROD      = $(this).find('td').eq(1).data('idprod'),
            IDDETPED    = $(this).find('td').eq(1).data('iddetpedido'),
            IDDERORD    = $(this).find('td').eq(1).data('iddetorden'),
            CESTADO     = $(this).find('td').eq(7).text(),
            UBICACION   = $(this).find('td').eq(8).text()


            item = {};

            if (CODITEM !== ''){
                item["coditem"]     = CODITEM;
                item["descripcion"] = DESCRIPCION;
                item["unidad"]      = UNIDAD;
                item["cantidad"]    = CANTRQ;
                item["cantdes"]     = CANTDES
                item["factor"]      = FACTOR;
                item["coduni"]      = CODUNI;
                item["idprod"]      = IDPROD;
                item["iddetped"]    = IDDETPED;
                item["iddetord"]    = IDDERORD;
                item['cestado']     = CESTADO;
                item['ubicacion']   = UBICACION;
            }

            DETALLES.push(item);
    })

    return DETALLES;
}