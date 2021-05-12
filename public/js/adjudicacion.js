var FILES       = [];
var atachs      = 0;


$(function(){

    activar_opcion();

    $("#tabla_pedidos tbody").on("click","a", function (e) {
        e.preventDefault();

        $("#modalProcess").fadeIn();

            $.post(RUTA+"adjudicacion/regById", { data:$(this).attr("href") },
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

                    $.post(RUTA +"adjudicacion/detailsById", {cod:$("#cod_pedido").val(),tip:$("#cod_tipo").val()},
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

    $("#detalle_pedido tbody").on("keypress",".punit", function (e) {
        //e.preventDefault()
        var cant = $(this).parent().parent().find("td").eq(9).children().val();
        var punit = $(this).val();
        var total = (parseFloat(cant) * parseFloat(punit))*1.18;


        if(e.which == 13) {
            $(this).parent().parent().find("td").eq(12).text(total.toFixed(2));
        }
        //return false;
    });

    $("#detalle_pedido tbody").on("keypress",".cantcot", function (e) {
        //e.preventDefault()
        var cantped = $(this).parent().parent().find("td").eq(5).text();
        var cantcod = $(this).val()

        if(e.which == 13) {
            if (cantped < cantcod) {
                mostrarMensaje("msj_error","Error en las cantidades solicitadas");
                return false;
            }
        }
    });

    $("#detalle_pedido tbody").on("change",".fechaentrega",function(e){

        var entrega = new Date($(this).val()).getTime();
        var actual  = new Date(fechaActual()).getTime();

        var diff = (entrega - actual)/(1000*60*60*24);

        if (diff < 1 ) {
            mostrarMensaje("msj_error","Verifique la fecha de entrega");
            return false;
        }else {
            $(this).parent().parent().find("td").eq(15).text(diff);
        }
    })

    $("#detalle_pedido tbody").on("click","#fileItemAtach", function (e) {
        e.preventDefault();

        $("#fileProdId").val( $(this).data('idprod') );
        $("#fileDetId").val( $(this).data('idreg') );
        $("#modalAtachItems").fadeIn();

        return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut()

        return false;
    });

    $("#closeModalItems").on("click", function (e) {
        e.preventDefault();

        $("#modalItems").fadeOut();

        return false;
    });

    $("#closeModalPreview").on("click", function (e) {
        e.preventDefault()
        $(".insidePreview object").attr("data","");
        $("#modalPreview").fadeOut();
        
        return false;
    });

    //procedimiento para generar la oc
    $("#genDoc").on("click", function () {
        
        var TABLE = $("#detalle_pedido tbody tr");
        var contador = 0;
        var importe = 0;

        TABLE.each(function(){
            if ( $(this).find('td').eq(2).children().prop("checked") ){
                contador++;
                importe += parseFloat($(this).find('td').eq(12).text());
                prov = $(this).find("td").eq(5).data("idproveedor");
            }
        })

        if (contador == 0) {
            mostrarMensaje("msj_error","No hay items para el documento");
        }else{
            $.post(RUTA+"adjudicacion/entities", {cod:prov,tipo:$("#cod_tipo").val()},
                function (data, textStatus, jqXHR) {
                    $("#id_prov").val(prov);
                    $("#selrq").val(contador);
                    $("#importerq").val(importe.toFixed(2));
                    $("#modalItems").fadeIn(); 
                    
                    $("#provrq").val(data.crazonsoc);
                    $("#dirrq").val(data.cviadireccion);
                    $("#rucrq").val(data.cnumdoc);
                    $("#nrorq").val(data.nrorq);

                    var tiporq = $("#cod_tipo").val() == "B" ? "ORDEN DE COMPRA" : "ORDEN DE SERVICIO";

                    $("#rqTitle").text(tiporq + " " + data.nrorq);
                },
                "json"
            );
        }
    });

    $("#closeItems").on("click", function (e) {
        e.preventDefault();

        var TABLE = $("#detalle_pedido tbody tr");

        TABLE.each(function(){
            if ( $(this).find('td').eq(0).children().prop("checked") ){
                closeRegisters( $(this).find('td').eq(4).data('iddetalle') );      
            }
        })

        return false;
    });

    $("#btnItemAtach").on("click", function (e) {
        e.preventDefault();

        $("#modalAtach").fadeIn();

        return false;
    });

    //buscar los items en la tabla de pedidos
    $("#searchItemsProcess").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        /*$("#detalle_pedido tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });*/
    });

    $("#btnItemPreview").on("click", function (e) {
        e.preventDefault();

        if ( $("#detalle_pedido tbody tr").length > 0) {
            if (verifyOperation()){
                dataPreview();
                $("#modalPreview").fadeIn();
             }else{
                 mostrarMensaje("msj_error","Faltan datos");
             }
        }else {
            mostrarMensaje("msj_error","No se puede generar vista previa, faltan datos");
        }
        
        return false;
    });

    //monedas
    $("#monrq").focus(function (e) { 
        e.preventDefault();

        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    //seleccion de la lista grupos
    $("#listaMonedas").on("click","a", function (e) {
        e.preventDefault();

        $("#id_mon").val($(this).attr("href"));
        $("#monrq").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //pagos
    $("#pagorq").focus(function (e) { 
        e.preventDefault();

        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    //seleccion de la lista grupos
    $("#listaPago").on("click","a", function (e) {
        e.preventDefault();

        $("#id_pago").val($(this).attr("href"));
        $("#pagorq").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //entrega
    $("#entregarq").focus(function (e) { 
        e.preventDefault();

        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    //seleccion de la lista grupos
    $("#listaEntrega").on("click","a", function (e) {
        e.preventDefault();

        $("#id_entrega").val($(this).attr("href"));
        $("#entregarq").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //lugar entrega
    $("#lEntrega").focus(function (e) { 
        e.preventDefault();

        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#lugarEntrega").on("click","a", function (e) {
        e.preventDefault();

        $("#id_alma").val($(this).attr("href"));
        $("#lEntrega").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //PROCESO PARA ADJUTAR ARCHVOS
    //subir archivos
    $("#upAttach").on("click", function (e) {
        e.preventDefault();
        
        $("#modalAtach").fadeIn();

        return false;
    });

    $("#pickFiles").on("click", function (e) {
        e.preventDefault();

        $("#uploadAtach").trigger("click");
        
        return false;
    });

    //mostrar los archivos adjuntos
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
                fragment +="<tr><td>"+fileName+"</td>"+"<td>"+fileSize+" Kb</td></tr>"
            }
    
            $("#tableAdjuntos tbody").append(fragment);
        }

        return false;
    });

    $("#btnConfirmAtach").on("click", function (e) {
        e.preventDefault();

        if ($("#tableAdjuntos tbody tr").length > 0) {
            $("#fileAtachs").trigger("submit");
        }else{
            mostrarMensaje("msj_error","No ha seleccionado archivos");
        }
        return false;
    });

    $("#btnCancelAtach").on("click", function (e) {
        e.preventDefault();

        $("#modalAtach").fadeOut();
        $("#fileAtachs")[0].reset();
        $("#tableAdjuntos tbody").empty();

        return false;

    });

    //añadir registro de adjuntos
    $("#fileAtachs").on("submit", function (e) {
        e.preventDefault()

        $.ajax({
            // URL to move the uploaded image file to server
            url: RUTA + 'adjudicacion/uploadsAtachs',
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
                $("#tableAdjuntos tbody").empty();
            }
        });
        
        return false;
    });

    $("#tableAdjuntosItems tbody").on("click","a", function (e) {
        e.preventDefault();

        var file = "#"+$(this).data('file');

        $(file).trigger('click');

        return false;
    });

    $(".manual").on("change", function (e) {
        e.preventDefault();

        var fileId = "#"+ $(this).attr("id");

        console.log(fileId);

        var fileName = e.target.files[0].name;
        var fileSize = e.target.files[0].size + 'Kb';

        $("#tableAdjuntosItems").find(fileId).parent().parent().children('td').eq(1).text( fileName );
        $("#tableAdjuntosItems").find(fileId).parent().parent().children('td').eq(2).text( fileSize );

        return false;
    });
   
    $("#btnConfirmAtachItems").on("click", function (e) {
        e.preventDefault();

        $("#fileAtachsItems").trigger("submit");
        
        return false;
    });

    $("#fileAtachsItems").on("submit", function (e) {
        e.preventDefault();

        $.ajax({
            // URL to move the uploaded image file to server
            url: RUTA + 'adjudicacion/uploadsAtachsItems',
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
                $("#modalAtachItems").fadeOut();
            }
        });

        return false;
    });

    $("#btnCancelAtachItems").on("click", function (e) {
        e.preventDefault();

        $("#modalAtachItems").fadeOut();
        $("#fileAtachsItems")[0].reset();

        var TABLE = $("#tableAdjuntosItems tbody tr");

        TABLE.each(function(){
            $(this).find('td').eq(1).text(""),
            $(this).find('td').eq(2).text("");    
        })

        return false;
    });

    $("body").on("click",".titulo_producto a", function (e) {
        e.preventDefault();

        codigo = $(this).attr("href");

        selectRow( calculateMin(codigo) , codigo );

        return false;
    });

    //grabar la orden compra
    $("#btnItemAcept").on("click", function (e) {
        e.preventDefault();

         if (verifyOperation()){
            passOData();
         }else{
             mostrarMensaje("msj_error","Faltan datos");
         }

        return false;
    });

    $("#btnItemCancel").on("click", function (e) {
        e.preventDefault();

        $("#formGenDoc")[0].reset();
        $("#modalItems").fadeOut();
        return false;
    });
})


function calculateMin(codigo){
    var TABLE = $("#detalle_pedido tbody tr");

    valores = [];

    TABLE.each(function(){
            var ITEM = $(this).find('td').eq(12).text(),
                CODI = $(this).find('td').eq(12).data("idprod");

            if ( CODI == codigo ) {
                valores.push(parseFloat(ITEM));
            }    
    })

    var min = Math.min.apply(null, valores);

    return min;
}

function selectRow(value,codigo){
    var TABLE = $("#detalle_pedido tbody tr");

    TABLE.each(function(){
        var ITEM  = $(this).find('td').eq(12).text(),
            CODI = $(this).find('td').eq(12).data("idprod");

        if (ITEM == value &&  CODI == codigo) {
            $(this).addClass("adjudicado_item");
        }
    })

}

function passOData() {
    DATA = [];

    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        if ( $(this).find('td').eq(2).children().prop("checked") ){
            var PROD = $(this).find('td').eq(4).data('prod'),
                CPRO = $(this).find('td').eq(12).data('idprod'),
                IDET = $(this).find('td').eq(4).data('iddetalle'),
                UNID = $(this).find('td').eq(6).data('codmed'),
                CANT = $(this).find('td').eq(7).text(),
                CCOT = $(this).find('td').eq(9).children().val(),
                PUNI = $(this).find('td').eq(11).children().val(),
                TOTA = $(this).find('td').eq(12).text(),
                FACT = $(this).find('td').eq(16).text(),

            item = {}

            if (PROD !== '') {
                item['cpro'] = CPRO;
                item["prod"] = PROD;
                item["idet"] = IDET;
                item["unid"] = UNID;
                item["cant"] = CANT;
                item["puni"] = PUNI;
                item["ccot"] = CCOT;
                item["tota"] = TOTA;
                item["fact"] = FACT;

                DATA.push(item);
            }
        }
    })

    $.ajax({
        type: "POST",
        url: RUTA + "adjudicacion/OCSHeader",
                data: { 
                pedido      : $("#cod_pedido").val(),
                fecha       : $("#fecharq").val(),
                tipo        : $("#cod_tipo").val(),
                proveedor   : $("#id_prov").val(),
                moneda      : $("#id_mon").val(),
                importe     : $("#importerq").val(),
                almacen     : $("#id_alma").val(),
                proyecto    : $("#cod_proy").val(),
                costos      : $("#cod_cost").val(),
                area        : $("#cod_area").val(),
                espec       : $("#espec_items").val(),
                solicitante : $("#cod_solicitante ").val(),
                pago        : $("#id_pago").val(),
                entrega     : $("#id_entrega").val(),
                fentrega    : $("#fentrega").val(), 
                cotizacion  : $("#cotrq").val(),
                atencion    : $("#atencion").val(),
                transporte  : $("#cod_transporte").val(),
                details:JSON.stringify(DATA)},
        dataType: "text",
        success: function (response) {
            registerCots(response);

            $("#formGenDoc")[0].reset();

            $("#modalItems").fadeOut();
        }
    });
}

function verifyOperation(){
    var operationOK = true;
    
    if ($("#monrq").val().length  < 1) {
        operationOK = false;
    }else if( $("#fentrega").val().length  < 1) {
        operationOK = false;
    }else if($("#lEntrega").val().length < 1){
        operationOK = false;
    }else if($("#pagorq").val().length < 1){
        operationOK = false;
    }
    
    return operationOK
}

function dataPreview() {
    DATA = [];

    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        if ( $(this).find('td').eq(2).children().prop("checked") ){
            var PROD = $(this).find('td').eq(4).data('prod'),
                CPRO = $(this).find('td').eq(12).data('idprod'),
                UNID = $(this).find('td').eq(6).text(),
                CANT = $(this).find('td').eq(7).text(),
                CCOT = $(this).find('td').eq(9).children().val(),
                PUNI = $(this).find('td').eq(11).children().val(),
                TOTA = $(this).find('td').eq(12).text(),

            item = {}

            if (PROD !== '') {
                item['cpro'] = CPRO;
                item["prod"] = PROD;
                item["unid"] = UNID;
                item["cant"] = CANT;
                item["puni"] = PUNI;
                item["ccot"] = CCOT;
                item["tota"] = TOTA;

                DATA.push(item);
            }
        }
    })

    $.ajax({
        type: "POST",
        url: RUTA + "adjudicacion/preview",
        data: { pedido          : $("#cod_pedido").val(),
                proveedor       : $("#id_prov").val(),
                idmoneda        : $("#id_mon").val(),
                tipo            : $("#cod_tipo").val(),
                condicion       : 0,
                fecha           : $("#fecharq").val(),
                moneda          : $("#monrq").val(),
                plazo_entrega   : $("#entregarq").val(),
                lugar_entrega   : $("#lEntrega").val(),
                cotizacion      : $("#cotrq").val(),
                pago            : $("#pagorq").val(),
                importe         : $("#importerq").val(),
                fentrega        : $("#fentrega").val(),
                details:JSON.stringify(DATA)},
        dataType: "text",
        success: function (response) {
            $(".insidePreview object")
                .attr("data","")
                .attr("data",response)
        }
    });
}

function registerCots(codigo){
    if( FILES.length > 0) {
        
        DATA = [];

        $.each(FILES, function(row){
            var NOMBRE = FILES[row],
                CODDOC = codigo;

                item = {};

                if (NOMBRE !== ""){
                    item['nombre'] = NOMBRE;
                    item['coddoc'] = CODDOC;
                }

                DATA.push(item);
        })

        INFO    = new FormData();
        aInfo   = JSON.stringify(DATA);
 
        INFO.append('data', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'adjudicacion/saveCots',
            processData: false, 
            contentType: false,
        });
    }
}

function closeRegisters(iddetalle){
    var TABLE = $("#detalle_pedido tbody tr");
    var sw = false;

    TABLE.each(function(){
        if ( $(this).find('td').eq(4).parent().data('iddetalle') == iddetalle){
            $(this).find('td').eq(4).parent().addClass("fila_inactivo");
        }
    })

    $.post(RUTA+"adjudicacion/itemClose", {data:iddetalle},
        function (data, textStatus, jqXHR) {
            
        },
        "text"
    );

    return false;
}