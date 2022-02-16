var contador    = 0;

$(function(){
    activar_opcion();

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();

        $.post(RUTA+"aprobacion/regById", {data:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#cod_pedido").val(data.id_regmov);
                $("#cod_proy").val(data.ncodpry);
                $("#cod_cost").val(data.ncodcos);
                $("#cod_area").val(data.ncodarea);
                $("#cod_transporte").val(data.ctiptransp);
                $("#cod_estdoc").val(data.nEstadoDoc);
                $("#cod_registro").val(data.nEstadoReg);
                $("#cod_solicitante").val(data.ncodper);
                $("#cod_tipo").val(data.ctipmov);
                $("#estado").val(data.nflgactivo);
                $("#atencion").val(data.nNivAten);
                $("#numero").val(data.cnumero);
                $("#fecha").val(data.ffechadoc);
                $("#usuario").val(data.id_cuser);
                $("#proyecto").val(data.ccodpry + ' ' + data.cdespry);
                $("#area").val(data.ccodarea + ' ' + data.cdesarea);
                $("#costos").val(data.ccodcos + ' ' + data.cdescos);
                $("#transporte").val(data.cod_transporte + ' ' + data.transporte);
                $("#concepto").val(data.cconcepto);
                $("#solicitante").val(data.dni + ' ' + data.apellidos + ',' + data.nombres);
                $("#registro").val(data.estado);
                $("#documento").val(data.estado);
                $(".bubleInfo").text(data.atachs);
                $("#espec_items").val(data.mdetalle);
                $("#tipo")
                    .val( data.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS")
                    .addClass("desactivado");
                
                $("#registro, #documento")
                    .removeClass('proceso')
                    .addClass('emitido');

                $.post(RUTA +"aprobacion/detailsById", {data:$("#cod_pedido").val()},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_pedido tbody")
                        .empty()
                        .append(data);
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

        $.post(RUTA+"aprobacion/mainList",
            function (data, textStatus, jqXHR) {
                $("#tabla_pedidos tbody")
                    .empty()
                    .append(data);
                $("#modalProcess").fadeOut()            
            },
            "text"
        );
    
        return false;
    });

    $("#saveItem").on("click", function (e) {
        e.preventDefault();

        $("#dialogSend").fadeIn();
        
        return false;
    });

    //llamar a la venta de vista de adjuntos
    $("#attachItem").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA+"aprobacion/attachs", {cod:$("#cod_pedido").val()},
            function (data, textStatus, jqXHR) {
                if (data != 0){
                    $("#atachList")
                        .empty()
                        .append(data);
                    $("#viewAtach").fadeIn();
                }
                else
                    mostrarMensaje("msj_info","No hay adjunto en el documento")
            },
            "text"
        );

        return false;
    });

    //mostrar el archivo adjunto
    $("#atachList").on("click","a", function (e) {
        e.preventDefault();

        $("#previewAttach object").attr("data",$(this).attr("href"));

        return false;
    });
    
    //llamar la lista de correos
    $("#callEmailList").on("click", function (e) {
        e.preventDefault();

        $(".listEmails").css('right',"0");

        $.post(RUTA+"aprobacion/correos", {data:0},
            function (data, textStatus, jqXHR) {
                $("#corporativos tbody")
                .empty()
                .append(data);
            },
            "text"
        );

        return false;
    });

    //#region cuadro de correos
    //pasar la lista de correos
    $("#corporativos tbody").on("click","tr", function (e) {
        e.preventDefault();

        var correo = $(this).children('td:eq(1)').text();

        if (!checkExistTable($("#listMailToSend tbody tr"),correo,0)){
            contador++;
            var row = '<tr class="con_borde">'+
                        '<td class="con_borde pl20">'+$(this).data("correo")+'</td>'+
                        '<td class="con_borde centro"><a href="#"><i class="fas fa-trash-alt"></i></a></td>'+
                    '</tr>';

            if (contador < 10) {
                $("#listMailToSend tbody").append(row);
            }else{
                mostrarMensaje("msj_info","Maximo 10 correos");
            }
        }else{
            mostrarMensaje("msj_error","El correo ya esta en la lista");
        }
        return false;
    });

    //borrar los correos
    $("#listMailToSend tbody").on("click","tr", function (e) {
        e.preventDefault();
        if (contador > 0)
            contador--;
            
        $(this).remove();

        return false;
    });

    //cerrar la lista de emails
    $(".listEmails").on("click","button", function (e) {
        e.preventDefault();

        $(".listEmails").css('right',"-100%");

        return false;
    });

    //cancelar los envios
    $("#btnSendCancel").on("click", function (e) {
        e.preventDefault();
        $("#listMailToSend tbody").empty();
        $(".listEmails").css('right',"-100%");
        $("#dialogSend").fadeOut();
        return false;
    });

    $("#btnSendConfirm").on("click", function (e) {
        e.preventDefault();

        if ( $("#listMailToSend tbody tr").length <= 0 ) {
            mostrarMensaje("msj_error","Por favor elija un correo de respuesta");
            return false;
        }

        $(".listEmails").css('right',"-100%");

        $.post(RUTA + "aprobacion/apruebaPedido", { pedido:$("#cod_pedido").val(),
                                                    correos:JSON.stringify(getMails()),
                                                    items:JSON.stringify(dataUpdateItems()),
                                                    usuario:$(".userData h3").text()},
            function (data, textStatus, jqXHR) {
                if (data.response){
                    mostrarMensaje("msj_info","Pedido aprobado");
                }
                else{
                    mostrarMensaje("msj_error","Error al aprobar");
                }
            },
            "json"
        );

        return false;
    });
    ////#endregion

    //#region vistaprevia
    $("#previewItem").on("click", function (e) {
        e.preventDefault();
        
        if ( $("#detalle_pedido tbody tr").length > 0) {
            //en el caso de que sea bienes *** aca ira la codicion para que sea la vista previa de servicios
            //
            let result = {};
    
            $.each($("#formProcess").serializeArray(),function(){
                result[this.name] = this.value;
            })

            $.post(RUTA+"aprobacion/genPreview", {cabecera:result,
                                                  detalles:JSON.stringify(dataPreviewBienes()),
                                                  aprueba:$(".userData H3").text(),
                                                  mensaje:"EMITIDO"},
                function (data, textStatus, jqXHR) {
                    if(data.response){
                        $(".insidePreview object")
                        .attr("data","")
                        .attr("data",data.archivo);
                        $("#modalPreview").fadeIn();
                    }else{
                        mostrarMensaje("msj_error","No se puede ver la vista previa");
                    }
                },
                "json"
            );
        }else {
            mostrarMensaje("msj_error","No se puede generar vista previa, faltan datos");
        }
        
        return false;
    });

    $("#closeModalPreview").on("click", function (e) {
        e.preventDefault()
        $(".insidePreview object").attr("data","");
        $("#modalPreview").fadeOut();
        
        return false;
    });

    $("#closeViewAtach").on("click", function (e) {
        e.preventDefault()
        $(".insidePreview object").attr("data","");
        $("#viewAtach").fadeOut();
        
        return false;
    });
    //#endregion
})

function dataPreviewBienes(){
    DETALLES = [];
    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        var ITEM        = $(this).find('td').eq(0).text(),
            CODITEM     = $(this).find('td').eq(1).text(),
            DESCRIPCION = $(this).find('td').eq(2).text(),
            UNIDAD      = $(this).find('td').eq(3).text(),
            CANTPED     = $(this).find('td').eq(4).text(),
            CANTAPR     = $(this).find('td').eq(6).children().val(),
            ESTADO      = $(this).find('td').eq(0).text(),
            OBSERV      = $(this).find('td').eq(7).children().val(),
            APROB       = $(this).find('td').eq(8).children().prop("checked")

        item = {};

        if ( APROB ){
            item["item"]        = ITEM;
            item["coditem"]     = CODITEM;
            item["cantped"]     = CANTPED;
            item["cantapr"]     = CANTAPR;
            item["descripcion"] = DESCRIPCION;
            item["estado"]      = ESTADO;
            item["unidad"]      = UNIDAD;
            item["observ"]      = OBSERV;
            item["aprob"]       = APROB;
        
            //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
            DETALLES.push(item);
        }
    });

    return DETALLES;
}

function dataUpdateItems(){
    ITEMS = [];
    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        var ITEM        = $(this).find('td').eq(0).text(),
            IDDETALLE   = $(this).find('td').eq(0).data("iddetalle"),
            CODITEM     = $(this).find('td').eq(1).text(),
            DESCRIPCION = $(this).find('td').eq(2).text(),
            UNIDAD      = $(this).find('td').eq(3).text(),
            CANTPED     = $(this).find('td').eq(4).text(),
            CANTATE     = $(this).find('td').eq(5).text(),
            CANTAPR     = $(this).find('td').eq(6).children().val(),
            OBSERV      = $(this).find('td').eq(7).children().val(),
            APROB       = $(this).find('td').eq(8).children().prop("checked"),

        item = {};

        if ( APROB ){
            item["item"]        = ITEM;
            item['iddetalle']   = IDDETALLE;
            item["coditem"]     = CODITEM;
            item["cantped"]     = CANTPED;
            item["cantapr"]     = CANTAPR;
            item["cantate"]     = CANTATE;
            item["descripcion"] = DESCRIPCION;
            item["unidad"]      = UNIDAD;
            item["observ"]      = OBSERV;
            item["aprob"]       = APROB;

            //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
            ITEMS.push(item);
        }
    });

    return ITEMS;
}

function getMails(){
    if ( $("#listMailToSend tbody tr").length > 0 ){
        CORREOS = [];
        var TABLA = $("#listMailToSend tbody > tr");

        TABLA.each(function(){
            var MAIL    = $(this).find('td').eq(0).text(),
                MSG     = $("#mail_mssg").val(),
            
            item = {};

            if (MAIL !== '' ){
                item["mail"]    = MAIL;
                item["msg"]     = MSG;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                CORREOS.push(item);
            }
        });

        return CORREOS;
    }
}

function getListRequest(){
    $.post(RUTA+"aprobacion/mainList", {data:0},
        function (data, textStatus, jqXHR) {
            $("#tabla_pedidos tbody")
                .empty()
                .append(data);
        },
        "text"
    );
}