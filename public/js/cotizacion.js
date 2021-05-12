ITEMS = [];
MAILS = [];
contador = 0;

$(function(){
    activar_opcion();

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        if ($(this).data("poption") == "editar"){
            $("#modalProcess").fadeIn();

            $.post(RUTA+"cotizacion/regById", { data:$(this).attr("href") },
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
                    $("#tipo")
                        .val( data.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS")
                        .addClass("desactivado");
                    $("#espec_items").val(data.mdetalle);
                    
                    $("#registro, #documento")
                        .removeClass('proceso')
                        .addClass('aprobado');

                    $.post(RUTA +"cotizacion/detailsById", {cod:$("#cod_pedido").val(),tip:$("#cod_tipo").val()},
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
        }else {
            $(this).parent().parent().find('td').eq(8)
            .text("Urgente")
            .css("background","red")
            .css("color","#fff");
            
            $.post(RUTA+"cotizacion/priority", {cod:$(this).attr("href")},
                function (data, textStatus, jqXHR) {
                        mostrarMensaje("msj_info","Se va a cambiar la prioridad del pedido");        
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

    $("#requestItem").on("click", function () {
        $("#dialogSend").fadeIn();
    });

    $("#selectDist").on("click", function () {
        
        countItemsSel();

        $("#dialogSend").fadeIn();
    
    });

    $("#closeItem").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeIn();

        return false;
    });

    $("#btnYes").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA+"cotizacion/close", {cod:$("#cod_pedido").val()},
            function (data, textStatus, jqXHR) {
                $("#dialogConfirm, #modalProcess").fadeOut();
                $("#tabla_pedidos tbody")
                .empty()
                .append(data);
            },
            "text"
        );

        return false;
    });

    $("#btnNo").on("click", function (e) {
        e.preventDefault();
        
        $("#dialogConfirm").fadeOut();

        return false;
    });

    $("#callEmailList").on("click", function (e) {
        e.preventDefault();

        $(".listEmails").css('right',"0");

        $.post(RUTA+"cotizacion/distEmails", {data:0},
            function (data, textStatus, jqXHR) {
                $("#corporativos tbody")
                .empty()
                .append(data);
            },
            "text"
        );

        return false;
    });

    //cerrar la lista de emails
    $(".listEmails").on("click","button", function (e) {
        e.preventDefault();

        $(".listEmails").css('right',"-100%");

        return false;
    });

    $("#btnSendCancel").on("click", function (e) {
        e.preventDefault();
        $("#detalle_pedido tbody  [type=checkbox]").prop("checked", false);
        $("#listMailToSend tbody").empty();
        $(".listEmails").css('right',"-100%");
        $("#dialogSend").fadeOut();
        return false;
    });

    $("#corporativos tbody").on("click","tr", function (e) {
        e.preventDefault();

        var correo = $(this).children('td:eq(1)').text();

        if (!checkExistTable($("#listMailToSend tbody tr"),correo,0)){
            contador++;
            var row = '<tr class="con_borde">'+
                        '<td class="con_borde pl20">'+$(this).data("correo")+'</td>'+
                        '<td class="con_borde centro"><a href="#"><i class="fas fa-trash-alt"></i></a></td>'+
                        '<td class="oculto">'+$(this).data("id")+'</td>'+
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


    //filtrar los correos de proveedores
    $("#searchEmailText").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#corporativos tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#btnSendConfirm").on("click", function (e) {
        e.preventDefault();

        if ( $("#listMailToSend tbody tr").length > 0){
            getMails($("#cod_pedido").val());
        }else{
            mostrarMensaje("msj_error","No hay direcciones de correo");
        }

        return false;
    });

    $("#checkItems").on("click", function (e) {
        e.preventDefault();

        $("#detalle_pedido tbody  [type=checkbox]").prop("checked", true);

        return false;
    });

})

function countItemsSel() {
    ITEMS = [];
    var TABLA = $("#detalle_pedido tbody > tr");
    var counter = 0;

    TABLA.each(function(){
        var IDDETALLE   = $(this).find('td').eq(1).data("iddetalle"),
            CODITEM     = $(this).find('td').eq(2).text(),
            IDXITEM     = $(this).find('td').eq(2).data("indice"),
            DESITEM     = $(this).find('td').eq(3).text(),
            UNIDAD      = $(this).find('td').eq(4).text(),
            CANTIDAD    = $(this).find('td').eq(5).text(),
            CHECKED     = $(this).find('td').eq(0).children().prop("checked"),
            FACTOR      = $(this).find('td').eq(7).text(),
            
        
        item = {};
        counter++;

        if ( CHECKED ){
            item['iddetalle']= IDDETALLE;
            item["coditem"]  = CODITEM;
            item["indice"]   = IDXITEM;
            item["cantidad"] = CANTIDAD;
            item["desitem"]  = DESITEM;
            item["unidad"]   = UNIDAD;
            item["checked"]  = CHECKED;
            item['factor']   = FACTOR;

            //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
            ITEMS.push(item);
        }

        counter = ITEMS.length;

        $("#num_items").text(counter);

    });
}

function getMails(codigo){
    MAILS = [];
    if ( $("#listMailToSend tbody tr").length > 0){
        var TABLA = $("#listMailToSend tbody > tr");

        TABLA.each(function(){
            var MAIL    = $(this).find('td').eq(0).text(),
                CODPROV = $(this).find('td').eq(2).text(),
                MSG     = $("#mail_mssg").val(),
                CODPED  = codigo,
            
            mail = {};

            if ( MAIL !== '' ){
                mail["mail"]    = MAIL;
                mail["msg"]     = MSG;
                mail["codped"]  = CODPED;
                mail["codprov"] = CODPROV;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                MAILS.push(mail);
            }
        });

        $.ajax({
            type: "POST",
            url: RUTA + 'cotizacion/mails',
            data: {
                mails:JSON.stringify(MAILS),
                items:JSON.stringify(ITEMS)
                },
            dataType: "json",
            success: function (response) {
                if (response.respuesta){
                    $("#btnSendCancel").trigger("click");
                    mostrarMensaje("msj_correcto","Correo enviado");
                }else{
                    mostrarMensaje("msj_erroe","No se pudo enviar el mensaje");
                }
            }
        });
    }
}
