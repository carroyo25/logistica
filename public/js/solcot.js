$(function(){
    var origen = 0;
    if ($("#verifica").val() == 1){
        $("#btnEnviar,#btnAdjuntar").css("display","none");
    }

    $("#btnEnviar").on("click", function (e) {
        e.preventDefault();

        return false;
    });

    $("#detalle_pedido tbody").on("keypress",".precio", function (e) {

        let cant = $(this).parent().parent().find("td").eq(3).text();
        let precio = $(this).val();
        let total = (parseFloat(cant) * parseFloat(precio));

        let suma = 0;
        let igv = parseFloat($('input:radio[name=igv]:checked').val());

        if(e.which == 13) {
            $(this).parent().parent().find("td").eq(5).text(total.toFixed(2));

            //obtiene la suma de la cotizacion
            $('#detalle_pedido tbody > tr').each(function(){ //filas con clase 'dato', especifica una clase, asi no tomas el nombre de las columnas
                suma += parseFloat($(this).find('td').eq(5).text()||0,10) //numero de la celda 3
            })

            if (suma > 0) {
                $("#subtotal").val(suma.toFixed(2));
                $("#igv").val((suma*igv).toFixed(2));
                $("#total").val((suma*(1+igv)).toFixed(2));
            }
        }

    });

    $("#detalle_pedido tbody").on("change",".fechaentrega",function(e){
        let entrega = new Date($(this).val()).getTime();
        let actual  = new Date($("#fechaDoc").val()).getTime();

        let diff = (entrega - actual)/(1000*60*60*24);

        if (diff < 1 ) {
            mostrarMensaje("msj_error","Verifique la fecha de entrega");
            return false;
        }
    })

    $("#detalle_pedido tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#tipo").val("item");
        $("#item").val($(this).attr("href"));
        
        $("#uploadfile").trigger("click");

        return false;
    });

    $("#uploadfile").change(function (e) { 
        e.preventDefault();
        
        $("#formCotizacion").trigger("submit");

        return false;
    });

    $("#formCotizacion").on("submit", function (e) {
        e.preventDefault();
        
        $.ajax({
            // URL to move the uploaded image file to server
            url:'uploadToServer.php',
            // Request type
            type: "POST", 
            // To send the full form data
            data: new FormData( this ),
            contentType:false,      
            processData:false,
            dataType: "json",     
            // UI response after the file upload 
            beforeSend: function() {
                // setting a timeout
                $("#modalWait").fadeIn();
            }, 
            success: function(data)
            {
                if (data.estado)
                    mostrarMensaje("msj_info",data.mensaje);
                else 
                    mostrarMensaje("msj_error",data.mensaje);

                if (data.tipo == "cotizacion") {
                    $("#archivo_cotizacion").val(data.archivo);
                }

                $("#modalWait").fadeOut();
            }
        });
        return false;
    });

    $("#btnAdjuntar").on("click", function (e) {
        e.preventDefault();

        if($("#cotizacion").val().length == 0){
            mostrarMensaje("msj_error","Rellene el numero de cotización");
            return false;
        }

        $("#tipo").val("cotizacion");
        $("#uploadfile").trigger("click");

        return false;
    });

    $("#btnEnviar").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeIn();

        return false;
    });

    $("#btnAceptarEnvio").on("click", function (e) {
        e.preventDefault();

        
        if($("#fechaDoc").val().length == 0) {
            mostrarMensaje("msj_error","Rellene la fecha de emisión");
            return false;
        }else if($("#fechaVig").val().length == 0){
            mostrarMensaje("msj_error","Rellene la fecha de vigencia");
            return false;
        }else if($("#cotizacion").val().length == 0){
            mostrarMensaje("msj_error","Rellene el numero de cotización");
            return false;
        }else if($("#cuenta").val().length == 0){
            mostrarMensaje("msj_error","Indique un numero de cuenta");
            return false;
        }else if($("#moneda").val() == -1){
            mostrarMensaje("msj_error","Seleccione el tipo de moneda");
            return false;
        }else if($("#condicion").val() == -1){
            mostrarMensaje("msj_error","Indique la condicion de pago");
            return false;
        }else if($("#plazo").val().length == 0){
            mostrarMensaje("msj_error","Indique el plazo de entrega");
            return false;
        }else if($("#total").val() == 0){
            mostrarMensaje("msj_error","No registro el precio de productos");
            return false;
        };

        getDetails();
        let detalles = JSON.stringify(DETALLES);
        
        //serializar los resultados para enviarlos a la php
        var result = { };
        $.each($('form').serializeArray(), function() {
            result[this.name] = this.value;
        });

        $.ajax({
            type: "POST",
            url: "sendDataToServer.php",
            data: {
                    datos:result,
                    detalles,
                },
            dataType: "text",
            success: function (response) {
                //if (response) {
                    $("#btnEnviar").css("display","none");
                    $("#dialogConfirm").fadeOut();
                    mostrarMensaje("msj_correcto","Proforma enviada");
                //}  
            }
        });
        return false;
    });

    $("#btnCancelarEnvio").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();

        return false;
    });
})

function getDetails(){
    DETALLES = [];

    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        var CODPROD      = $(this).find('td').eq(0).data('codprod'),
            DETPED       = $(this).find('td').eq(0).data('detped'),
            PEDIDO       = $(this).find('td').eq(0).data('pedido'),
            CANTCOT      = $(this).find('td').eq(3).text(),
            PRECIO       = $(this).find('td').eq(4).children().val(),
            ENTREGA      = $(this).find('td').eq(6).children().val(),
            OBSERVACION  = $(this).find('td').eq(7).children().val(),
            ENTIDAD      = $("#identi").val();

            item = {};

            if (CODPROD !== ''){
                item["codprod"]      = CODPROD;
                item["detped"]       = DETPED;
                item["pedido"]       = PEDIDO;
                item["cantcot"]      = CANTCOT;
                item["precio"]       = PRECIO
                item["entrega"]      = ENTREGA;
                item["observacion"]  = OBSERVACION;
                item["entidad"]      = ENTIDAD;
            }

        DETALLES.push(item);
    })

    return DETALLES;
}