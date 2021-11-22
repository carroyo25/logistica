var atachs      = 0;
var FILES       = [];
var accion      = "";
var itemsfila   = 0;
var SERIES = [];

$(function(){
    activar_opcion();

    $("#editreg, #editreg, #savereg, #cancelreg, #deletereg, #exportexcel").addClass("oculto");

    $("#newreg").on("click", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();
        $(".process_header, .details_item").removeClass("desactivado");
        $(".sides_process div").removeClass("no_modificar");
        $("#formProcess")[0].reset();
        $("#cod_almacen,#cod_movimento,#cod_autoriza,#nroguia").val("");
        $("#detalle_pedido tbody").empty();
        $("#saveItem span").removeClass('parpadea');
        $("#importOrd").removeClass("oculto");
        $("#detalle_series tbody, #tableAdjuntos tbody, #detalle_ingreso tbody").empty();
        $("#documento")
                .removeClass("emitido")
                .addClass("proceso")
                .val('proceso');

        accion = "n";

        return false;
    });

    $("#saveDoc").on("click", function (e) {
        e.preventDefault(e);

        if ($("#cod_almacen").val().length == 0){
            mostrarMensaje("msj_error","Seleccione un almacen");
            return false;
        }else if ($("#cod_movimento").val().length == 0){
            mostrarMensaje("msj_error","Seleccione el motivo de movimiento");
            return false;
        }else if ($("#cod_autoriza").val().length == 0){
            mostrarMensaje("msj_error","Seleccione la persona que autoriza");
            return false;
        }else if ($("#nroguia").val().length == 0){
            mostrarMensaje("msj_error","Ingrese el Nro. de guia");
            return false;
        }

        getDetails();
        //getSeries();
        registerAtachs();

        if ( accion == "n" ) {
            $.post(RUTA + "ingresos/nuevoIngreso", {ningreso:$("#nro_ingreso").val(),
                                                    fecha:$("#fechadoc").val(),
                                                    origen:$("#cod_almacen").val(),
                                                    fcoontable:$("#fechacont").val(),
                                                    entidad:$("#id_entidad").val(),
                                                    guia:$("#nroguia").val(),
                                                    orden:$("#idorden").val(),
                                                    pedido:$("#idpedido").val(),
                                                    estado:$("#estado").val(),
                                                    autoriza:$("#cod_autoriza").val(),
                                                    cod_mov:$("#cod_movimento").val(),
                                                    num_mov:$("#movalmacen").val(),
                                                    cod_pry:$("#cod_proyecto").val(),
                                                    cod_area:$("#cod_area").val(),
                                                    cod_cos:$("#cod_costos").val(),
                                                    calidad:$("#chkCalidad").prop("checked"),
                                                    detalles:JSON.stringify(DETALLES),
                                                    series:JSON.stringify(SERIES),
                                                    adjuntos:JSON.stringify(ADJUNTOS)
                                                },
                function (data, textStatus, jqXHR) {
                    if (data){
                        mostrarMensaje("msj_correcto","Registro insertado...");
                        $("#modalProcess").fadeOut();
                    }else{
                        mostrarMensaje("msj_error","Error.. no se inserto el registro");
                    }
                },
                "text"
            );
        }else {
            $.post(RUTA+"ingresos/actualizaIngreso", {index:$("#id_ingreso").val(),
                                                      guia:$("#nroguia").val(),
                                                      autoriza:$("#cod_autoriza").val(),
                                                      detalles:JSON.stringify(DETALLES),
                                                      series:JSON.stringify(SERIES),
                                                      adjuntos:JSON.stringify(ADJUNTOS)},
                function (data, textStatus, jqXHR) {
                    if (data){
                        mostrarMensaje("msj_correcto","Registro actualizado...");
                        $("#modalProcess").fadeOut();
                    }else{
                        mostrarMensaje("msj_error","Error.. no se actualizo el registro");
                    }
                },
                "text"
            );
        }
        
        return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut()

        return false;
    });

    $("#closeModalOrders").on("click", function (e) {
        e.preventDefault()
       
        $("#modalOrdenes").fadeOut();

        return false;
    });
    
    //lista de Almacenes
    $("#almacen").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#cod_almacen").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }
        

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

    //lista de motivos de movimientos

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

        $("#cod_movimento").val($(this).attr("href"));
        $("#tipomov").val($(this).text());

        $(this).parent().parent().parent().slideUp();
        $("#saveDoc span").addClass('parpadea');

        return false;
    });

    //lista de personal que aprueba

    $("#aprueba").focus(function (e) { 
        e.preventDefault();
        
        if (accion == "n") {
            $("#cod_autoriza").val("");
            $(this).select();
            $(".seleccion").fadeOut();

            $(this).next(".seleccion").slideDown();
        }

        return false;
    });

    $("#listaAprueba").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_autoriza").val($(this).attr("href"));
        $("#aprueba").val($(this).text());
        $("#cargo_almacen").val($(this).data("cargo"));

        $(this).parent().parent().parent().slideUp();
        $("#saveDoc span").addClass('parpadea');

        return false;
    });

    //tabla de oodenes;
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
    
    //llamar los detalles de las ordenes
    $("#lista_ordenes tbody").on("click","tr", function (e) {
        e.preventDefault();

        $.post(RUTA+"ingresos/importOrder",{codigo:$(this).data('id')},
            function (data, textStatus, jqXHR) {
                $("#proyecto").val(data.proyecto);
                $("#area").val(data.area);
                $("#costos").val(data.costos);
                $("#solicita").val(data.solicita);
                $("#entidad").val(data.entidad);
                $("#concepto").val(data.concepto);
                $("#espec").val(data.detalle);
                $("#nruc").val(data.ruc);
                $("#entidad").val(data.entidad);
                $("#nroped").val(data.pedido);
                $("#nrord").val(data.orden);
                $("#documento").val(data.estado);
                $("#order_file").val(data.pdf);
                $("#id_ingreso").val(data.id);
                $("#idorden").val(data.idord);
                $("#idpedido").val(data.idped);
                $("#id_entidad").val(data.ident);
                $("#cod_proyecto").val(data.idproy);
                $("#cod_area").val(data.idarea);
                $("#cod_costos").val(data.idcost);
                $("#entrega").val(data.ffechaent);

                $.post(RUTA+"ingresos/detailsOrder", {codigo:data.codigo},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_ingreso tbody")
                        .empty()
                        .append(data);

                        var items = $("#detalle_ingreso tbody tr").length;

                        $("#items").val(items);
                    },
                    "text"
                );

                $("#modalOrdenes").fadeOut();
            },
            "json"
        );

        return false;
    });

    //opciones de la tabla de detalles
    $("#detalle_ingreso tbody").on("click","a", function (e) {
        e.preventDefault();
        if ( $(this).data("action") == "register") {
            //llamar a la ventana de series de prouctos

            var descrip = $(this).parent().parent().find('td').eq(4).text(),
                idprod  = $(this).parent().parent().find('td').eq(2).data('idprod'),
                nroserials = $(this).parent().parent().find('td').eq(7).children().val();

            $.post(RUTA+"ingresos/llamarSeries", {index:$("#id_ingreso").val(),prod:idprod},
                function (data, textStatus, jqXHR) {
                    $("#detalle_series tbody")
                    .empty()
                    .append(data);

                    itemsfila = $("#detalle_ingreso tbody").length - 1;
                },
                "text"
            );
            
            $("#descripProducto").text(descrip);
            $("#codigoProducto").text(idprod);
            $("#nroItemSerial").text(nroserials);

            $("#modalSerie").fadeIn();
        }else{
            $(this).parent().parent().remove();

            fill3Tables($("#detalle_ingreso tbody > tr"),2);
        }

        return false;
    });

    $("#btnCancelSerial").on("click", function (e) {
        e.preventDefault();

        $("#modalSerie").fadeOut();
        $("#detalle_series tbody").empty();
        itemsfila = 0;

        return false;
    });

    $("#addSerials").on("click", function (e) {
        e.preventDefault();

        let maxSerial = $("#nroItemSerial").text();
        let codigoPro = $("#codigoProducto").text();
        let itemShow = $("#detalle_series tbody tr").length + 1;
        itemsfila++;

        if ( itemsfila <= maxSerial ) {
            var fila = '<tr>'+
                        '<td class="con_borde centro"><a href="'+ codigoPro +'"><i class="fas fa-trash-alt"></i></a></td>'+
                        '<td class="con_borde centro">'+ itemShow +'</td>'+
                        '<td class="con_borde"><input type ="text" class="sin_borde mayusculas pl20"></td>'+
                        '<td class="con_borde"><input type ="text" class="sin_borde"></td>'+
                    '</tr>'

            $("#detalle_series").append(fila);
            

        }else {
            mostrarMensaje("msj_error","Máximo número de series");
        }
        
        return false;
    });

    $("#btnConfirmSerial").on("click", function (e) {
        e.preventDefault();

        var maxSerial = $("#nroItemSerial").text();

        if ( itemsfila == maxSerial ){
            getSeries();
            $("#modalSerie").fadeOut();
        }else{
            mostrarMensaje("msj_error","Faltan series del producto");
        }

        return false;
    });

    //modal vista documento de la orden 
    $("#orderDetail").on("click", function (e) {
        e.preventDefault();

        if ( $("#order_file").val().length == 0 ) {
            mostrarMensaje("msj_error","Debe seleccionar una orden");
            return false;
        }

        let orden = "public/ordenes/aprobadas/OC"+$("#idorden").val()+".pdf";

        $("#modalOrderDetail .insidePreview object")
                .attr("data","")
                .attr("data",orden);

        $("#modalOrderDetail").fadeIn();

        return false;
    });

    $("#closeModalOrderDetail").on("click", function (e) {
        e.preventDefault();

        $("#modalOrderDetail").fadeOut();

        return false;
    });

    //modal para subir archvivos
    $("#docsAtach").on("click", function (e) {
        e.preventDefault();

        if ( $("#nrord").val().length == 0) {
            mostrarMensaje("msj_error","Debe seleccionar una orden");
            return false;
        }else {
            $.post(RUTA + "ingresos/llamarAdjuntos", {index:$("#id_ingreso").val()},
                    function (data, textStatus, jqXHR) {
                        $("#tableAdjuntos tbody")
                            .empty()
                            .append(data);
                    },
                    "text"
            );
        }

        $("#modalAtach").fadeIn();

        return false;
    });

    $("#btnCancelAtach").on("click", function (e) {
        e.preventDefault();

        $("#modalAtach").fadeOut();

        return false;
    });

    $("#btnConfirmAtach").on("click", function (e) {
        e.preventDefault();

        if ( $("#tableAdjuntos tbody tr").length == 0) {
            mostrarMensaje("msj_error","No selecciono ningun archivo");
            return false;
        }else {
            $("#fileAtachs").trigger("submit");
        }

        $("#modalAtach").fadeOut();

        return false;
    });

     //mostrar los archivos adjuntos

    $("#pickFiles").on("click", function (e) {
        e.preventDefault();

        $("#uploadAtach").trigger("click");
        
        return false;
    });

    $("#uploadAtach").on("change", function (e) {
        e.preventDefault();

        var fp = $("#uploadAtach");
        var lg = fp[0].files.length; // get length
        var items = fp[0].files;
        var fragment = "";

        if (lg > 0) {
            for (var i = 0; i < lg; i++) {
                var fileName = items[i].name; // get file name
                var fileSize = items[i].size; // get file size 
    
                // append li to UL tag to display File info
                fragment +="<tr><td classs='con_borde pl20'>"+fileName+"</td>"+"<td  classs='con_borde drch pr10'>"+fileSize+" Kb</td></tr>"
            }
    
            $("#tableAdjuntos tbody").append(fragment);
        }

        return false;
    });

    //añadir registro de adjuntos
    $("#fileAtachs").on("submit", function (e) {
        e.preventDefault()

        $.ajax({
            // URL to move the uploaded image file to server
            url: RUTA + 'ingresos/uploadDocuments',
            // Request type
            type: "POST", 
            // To send the full form data
            data: new FormData( this ),
            contentType:false,      
            processData:false,
            dataType:"json",    
            // UI response after the file upload  
            success: function(data)
            {   
                $.each (data, function (item) { 
                    FILES.push(data[item]); 
                });
                
                $("#modalAtach").fadeOut();
                $("#fileAtachs")[0].reset();
                //$("#tableAdjuntos tbody").empty();
            }
        });
        
        return false;
    });

    //vista previa
    $("#preview").on("click", function (e) {
        e.preventDefault();

        if ($("#cod_almacen").val().length == 0){
            mostrarMensaje("msj_error","Seleccione un almacen");
            return false;
        }else if ($("#cod_movimento").val().length == 0){
            mostrarMensaje("msj_error","Seleccione el motivo de movimiento");
            return false;
        }else if ($("#cod_autoriza").val().length == 0){
            mostrarMensaje("msj_error","Seleccione la persona que autoriza");
            return false;
        }else if ($("#nroguia").val().length == 0){
            mostrarMensaje("msj_error","Ingrese el Nro. de guia");
            return false;
        }

        getDetails();

        $.ajax({
            type: "POST",
            url: RUTA+"ingresos/preview",
            data:{  proyecto: $("#proyecto").val(),
                    origen: $("#almacen").val(),
                    movimiento: $("#tipomov").val(),
                    fecha: $("#fechadoc").val(),
                    orden: $("#nrord").val(),
                    pedido: $("#nroped").val(),
                    entidad: $("#entidad").val(),
                    guia: $("#nroguia").val(),
                    autoriza: $("#aprueba").val(),
                    cargo:$("#cargo_almacen").val(),
                    condicion: 0,
                    ndoc:$("#nro_ingreso").val(),
                    tipo:"I",
                    details:JSON.stringify(DETALLES)},
            dataType: "text",
            success: function (response) {
                
                $("#modalPreview .insidePreview iframe")
                    .attr("src","")
                    .attr("src",response);

                $("#modalPreview").fadeIn();                
            }
        });

        return false;
    });

    $("#closeModalPreview").on("click", function (e) {
        e.preventDefault();

        $("#modalPreview").fadeOut();

        return false;
    });

    //buscar ordenes
    $("#nroorden").on("keypress", function (e) {
        //e.preventDefault()
        if(e.which == 13 && $(this).val().length > 2) {
            $("#waitmodal").fadeIn();
            $.post(RUTA+"ingresos/ordenesPalabra", {palabra:$(this).val()},
                function (data, textStatus, jqXHR) {
                    $("#lista_ordenes tbody")
                        .empty()
                        .append(data);
                    $("#waitmodal").fadeOut();  
                },
                "text"
            );
        }
    });

    //llamar a las ordenes anterioes
    $("#tabla_ingresos tbody").on("click","a", function (e) {
        e.preventDefault();

        $(".seleccion").fadeOut();
        abrirVentanaEspera();

        $.post(RUTA+"ingresos/llamaIngresoPorId", {nota:$(this).attr('href')},
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

                $.post(RUTA+"ingresos/llamarDetallesCodigo", {index:data.id_ingreso},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_ingreso tbody")
                            .empty()
                            .append(data);

                        var items = $("#detalle_ingreso tbody tr").length;

                        $("#items").val(items);
                        cerrarVentanaEspera();
                    },
                    "text"
                );

                accion = "u";
            },
            "json"
        );

        return false;
    });

    $("#detalle_ingreso tbody").on("blur","input", function (e) {
        e.preventDefault();

        let pediente = $(this).parent().parent().find('td').eq(8).text();
        let ingreso = $(this).val();
        let total = pediente - ingreso;

        if ( ingreso > -1 ) {
            $(this).parent().parent().find('td').eq(8).text( total.toFixed(2) );
        }else{
            mostrarMensaje("msj_error","Verifique la cantidad Ingresada");
            return false;
        }
        return false
    });
    

    //tienes que ver que grabe los almacenes en series y la calificacion de proveedores
    
    $("#closeDoc").click(function (e) { 
        e.preventDefault();
        getDetails();

        let sw = $("#chkCalidad").prop("checked") == true ? 10:11;
        let details = JSON.stringify(getDetails());

        $.post("ingresos/cierreIngreso", {cod:$("#id_ingreso").val(),details,condicion:sw,
                                            pedido:$("#idpedido").val(),orden:$("#idorden").val(),entidad:$("#id_entidad").val()},
            function (data, textStatus, jqXHR) {
                if (data){
                    mostrarMensaje("msj_info","Ingreso actualizado");
                }
            },
            "text"
        );

        return false;
    });
})

function getSeries(){
    var TABLA = $("#detalle_series tbody > tr");

    TABLA.each(function(){
        var SERIE    = $(this).find('td').eq(2).children().val(),
            OBSERV   = $(this).find('td').eq(3).children().val(),
            CODPRO   = $("#codigoProducto").text();

            item = {};

            if(SERIE !== undefined){
                item['serie']   = SERIE;
                item['observ']  = OBSERV;
                item['codpro']  = CODPRO;

                SERIES.push(item);
            }       
    })

    return SERIES;
}

function getDetails(){
    DETALLES = [];

    var TABLA = $("#detalle_ingreso tbody > tr");

    TABLA.each(function(){
        var ITEM        = $(this).find('td').eq(2).text(),
            CODITEM     = $(this).find('td').eq(3).text(),
            DESCRIPCION = $(this).find('td').eq(4).text(),
            UNIDAD      = $(this).find('td').eq(5).text(),
            CANTING     = $(this).find('td').eq(7).children().val(),
            NESTADO     = $(this).find("select[name='estado']").val(),
            TESTADO     = $(this).find("select[name='estado'] option:selected").text(),
            UBICACION   = '',
            LOTE        = $(this).find('td').eq(10).text(),
            VENCE       = $(this).find('td').eq(11).text(),
            NIDDETA     = $(this).find('td').eq(2).data('iddetalle'),
            FACTOR      = $(this).find('td').eq(2).data('factor'),
            CODUNI      = $(this).find('td').eq(2).data('coduni'),
            IDPROD      = $(this).find('td').eq(2).data('idprod'),
            IDDETPED    = $(this).find('td').eq(2).data('iddetpedido'),
            IDDERORD    = $(this).find('td').eq(2).data('iddetorden'),
            CANTORD     = $(this).find('td').eq(6).text(),
            CANTPEND    = $(this).find('td').eq(8).text(),    


            item = {};

            if (ITEM !== ''){
                item["item"]        = ITEM;
                item["coditem"]     = CODITEM;
                item["descripcion"] = DESCRIPCION;
                item["unidad"]      = UNIDAD;
                item["cantidad"]    = CANTING;
                item["nestado"]     = NESTADO;
                item["cestado"]     = TESTADO;
                item["ubicacion"]   = UBICACION;
                item["lote"]        = LOTE;
                item["vence"]       = VENCE;
                item["niddeta"]     = NIDDETA;
                item["factor"]      = FACTOR;
                item["coditem "]    = ITEM;
                item["coduni"]      = CODUNI;
                item["idprod"]      = IDPROD;
                item["iddetped"]    = IDDETPED;
                item["iddetord"]    = IDDERORD;
                item["cantord"]     = CANTORD;
                item["cantpend"]    = CANTPEND;
            }

            DETALLES.push(item);
    })

    return DETALLES;
}

function registerAtachs(){
    ADJUNTOS=[];

    $.each(FILES, function(row){
        var NOMBRE = FILES[row],

            item = {};

            if (NOMBRE !== ""){
                item['nombre'] = NOMBRE;
            }

            ADJUNTOS.push(item);
    })

    return ADJUNTOS;
}