var accion      = "n";
var item        = 0;
var operacion   = 0;
var DATA        = [];
var tipo        = 0;
var grupo       = 0;
var clase       = 0;
var familia     = 0;
var codigo      = 0;
var unidad      = "";
var contador    = 0;
var FILES       = [];
var nItemdel    = 0;
var changeState = 0;
var vistaItem   = 0;
var diff        = 0;
var tituloCorreo= '';
var estadoPedido= 0;


$(function() { 
activar_opcion();

$("#editreg, #editreg, #savereg, #cancelreg, #deletereg, #exportexcel").addClass("oculto");


//listar el pedido para editar
$("#tabla_pedidos tbody").on("click",'a',function(e){
    e.preventDefault();
    accion = "u";
    
    switch ($(this).data("poption")) {
        case "editar":
            $("#modalProcess").fadeIn();
            $.post(RUTA+"pedidos/updRequest", {cod:$(this).attr("href")},
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
                    $("#fechaven").val(data.ffechaven);
                    $("#verificarAlmacen").val(data.nflgVeryAlm);

                    $("#tipo")
                        .val( data.ctipmov == "B" ? "01 BIENES" : "02 SERVICIOS")
                        .addClass("desactivado");
                    $("#espec_items").val(data.mdetalle);

                    $.post(RUTA+"pedidos/updItems", {cod:data.id_regmov,tipo:data.ctipmov},
                        function (data, textStatus, jqXHR) {
                            $("#detalle_pedido tbody")
                            .empty()
                            .append(data);
                        },
                        "text"
                    );

                    $("#registro")
                        .removeClass("proceso","aprobado")
                        .addClass(data.estado.toLowerCase());

                    if ( data.nEstadoDoc  == 1 ) {
                        $(".sides_process div").removeClass('no_modificar');
                    }else {
                        $(".sides_process div").addClass('no_modificar');
                    }

                    $(".seleccion").fadeOut();

                    if ( $("#verificarAlmacen").val() != "1" ){
                        $("#requestAprob").removeClass("desactivado");
                        $("#sendItem").addClass("desactivado");
                    }else {
                        $("#requestAprob").addClass("desactivado");
                        $("#sendItem").removeClass("desactivado");
                    }
                },
                "json"
            );
            break;
    }

    return false;
})
//agregar pedidos
$("#newreg").on("click", function (e) {
    e.preventDefault();

    $("#modalProcess").fadeIn();
    $(".process_header, .details_item").removeClass("desactivado");
    $(".sides_process div").removeClass("no_modificar");
    $("#formProcess")[0].reset();
    $("#cod_pedido,#cod_proy,#cod_cost,#cod_area,#cod_transporte,#cod_solicitante,#cod_tipo").val("");
    $("#registro, #documento").val("EN PROCESO");
    $("#detalle_pedido tbody").empty();
    $("#saveItem span").removeClass('parpadea');
    $("#tipo").removeClass("desactivado");
    $(".seleccion").fadeOut();
    $("#registro, #documento")
        .removeClass('aprobado','consulta')
        .addClass('proceso');

    vistaItem = 0;

    $.post(RUTA+"pedidos/newRequest", {data:0},
        function (data, textStatus, jqXHR) {
            $("#numero").val(data.numero);
            $("#cod_pedido").val(data.codigo);
        },
        "json"
    );

    accion = "n";

    return false;
});

$("#cancelItem").on("click", function (e) {
    e.preventDefault();

    $("#modalProcess").fadeOut();
    $("#formProcess")[0].reset();
    $("#detalle_pedido tbody, #tableAdjuntos tbody").empty();
    $("#fileAtachs")[0].reset();
    $("#saveItem span").removeClass('parpadea');
    
    return false;
});

$("#preview").on("click", function (e) {
    e.preventDefault();
    
    if ( $("#detalle_pedido tbody tr").length > 0) {
        dataPreview();
        $("#modalPreview").fadeIn();
    }else {
        mostrarMensaje("msj_error","No se puede generar vista previa, faltan datos");
    }
    
    return false;
});

//cerrar el modal de busqueda
$("#closeModalProcess").on("click", function (e) {
    e.preventDefault()

    if ( $("#saveItem span").hasClass('parpadea') ) {
        $("#dialogConfirm").fadeIn();
    }
    else{
        $("#modalProcess").fadeOut();
        getListRequest();
    }
    
    return false;
});

$("#btnYes").on("click", function (e) {
    e.preventDefault();

    $("#modalProcess").fadeOut();
    $("#dialogConfirm").fadeOut();
    $("#formProcess")[0].reset();
    $("#detalle_pedido tbody").empty();
    $("#saveItem span").removeClass('parpadea');

    return false;
});

$("#btnNo").on("click", function (e) {
    e.preventDefault();

    $("#dialogConfirm").fadeOut();

    return false;
});

$("#closeModalItems").on("click", function (e) {
    e.preventDefault()

    $("#modalItems").fadeOut();
    
    return false;
});

$("#closeModalPreview").on("click", function (e) {
    e.preventDefault()
    $(".insidePreview object").attr("data","");
    $("#modalPreview").fadeOut();
    
    return false;
});

//desplegar las listas
//activar el cuadro de proyectos
$("#proyecto").focus(function (e) { 
    e.preventDefault();
    
    $("#cod_proy").val("");
    $(this).select();
    $(".seleccion").fadeOut();

    $(this).next(".seleccion").slideDown();

    return false;
});

//seleccion de las lista proyectos
$("#listaProyectos").on("click","a", function (e) {
    e.preventDefault();

    $("#cod_proy").val($(this).attr("href"));
    $("#proyecto").val($(this).text());

    $.post(RUTA+"pedidos/centroCostos", {cod: $(this).attr("href")},
        function (data, textStatus, jqXHR) {

            $("#cod_cost, #costos").val("");

            $("#listaCostos")
                .empty()
                .append(data);
        },
        "text"
    );

    $(this).parent().parent().parent().slideUp();
    $("#saveItem span").addClass('parpadea');

    return false;
});

 //activar el cuadro de proyectos
$("#costos").focus(function (e) { 
    e.preventDefault();
    
    $("#cod_cost").val("");
    $(this).select();
    $(".seleccion").fadeOut();

    $(this).next(".seleccion").slideDown();

    return false;
});

//seleccion de las lista proyectos
$("#listaCostos").on("click","a", function (e) {
    e.preventDefault();

    $("#cod_cost").val( $(this).attr("href") );
    $("#costos").val($(this).text());

    if ( $(this).data("verifica") == 1 ){
        $("#requestAprob").addClass("desactivado");
        $("#sendItem").removeClass("desactivado");
    }else {
        $("#requestAprob").removeClass("desactivado");
        $("#sendItem").addClass("desactivado");
    }

    $(this).parent().parent().parent().slideUp();
    $("#saveItem span").addClass('parpadea');


    return false;
});

//LISTA DESPLEGABLE PARA AREAS
//activar el cuadro de areas
$("#area").focus(function (e) { 
    e.preventDefault();
    
    $("#cod_area").val("");
    $(this).select();
    $(".seleccion").fadeOut();

    $(this).next(".seleccion").slideDown();

    return false;
});

//seleccion de la lista areas
$("#listaAreas").on("click","a", function (e) {
    e.preventDefault();

    $("#cod_area").val($(this).attr("href"));
    $("#area").val($(this).text());

    $(this).parent().parent().parent().slideUp();
    $("#saveItem span").addClass('parpadea');

    return false;
});

$("#area").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#listaAreas li a").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

/******************** */
//activar el transporte
$("#transporte").focus(function (e) { 
    e.preventDefault();
    
    $("#cod_transporte").val("");
    $(this).select();
    $(".seleccion").fadeOut();

    $(this).next(".seleccion").slideDown();

    return false;
});

//seleccion de la lista areas
$("#listaTransporte").on("click","a", function (e) {
    e.preventDefault();

    $("#cod_transporte").val($(this).attr("href"));
    $("#transporte").val($(this).text());

    $(this).parent().parent().parent().slideUp();
    $("#saveItem span").addClass('parpadea');

    return false;
});

///LISTA DESPLEGABLE PARA SOLICITANTE
//activar el solicitante

//seleccion de la lista solcitantes
$("#listaSolicitante").on("click","a", function (e) {
    e.preventDefault();

    $("#cod_solicitante").val($(this).attr("href"));
    $("#solicitante").val($(this).text());

    $(this).parent().parent().parent().slideUp();
    $("#saveItem span").addClass('parpadea');

    return false;
});

//filtrado en la lista de solicitante
$("#solicitante").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#listaSolicitante li a").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

//activar llenar los datos del solicitante
$("#solicitante").focus(function (e) { 
    e.preventDefault();
    
    $(this)
    .select()
    .parent().children("img").css("display","block");

    $(".seleccion").fadeOut();

    $.post(RUTA+"pedidos/solicitantes", {data:0},
        function (data, textStatus, jqXHR) {
            $("#solicitante").parent().children("img").css("display","none");

            $("#listaSolicitante")
            .empty()
            .append(data)
            .end();

            $("#listaSolicitante").parent().slideDown();
        },
        "text"
    );

    return false;
});

//********************** */

//seleccionar el tipo
$("#tipo").focus(function (e) { 
    e.preventDefault();
    
    $("#cod_tipo").val("");
    $(this).select();
    $(".seleccion").fadeOut();

    $(this).next(".seleccion").slideDown();
    

    return false;
});

//seleccion de la lista areas
$("#listaTipo").on("click","a", function (e) {
    e.preventDefault();

    $("#cod_tipo").val( $(this).attr("href") == "01" ? "B":"S" );
    $("#tipo").val($(this).text());

    $("#tipo").addClass("desactivado");

    $(this).parent().parent().parent().slideUp();        
    $("#saveItem span").addClass('parpadea');

    return false;
});

//agregar item al cuadro
$("#addItem").on("click", function (e) {
    e.preventDefault();

    if ( $("#tipo").val().length <= 0 ) {
        mostrarMensaje("msj_error","Seleccione el tipo de pedido");
        $("#tipo").focus();
        
        return false;
    }

    operacion = $("#tipo").val() == '01 BIENES' ? 1 : 2;

    $("#waitmodal").fadeIn();

    $.post(RUTA+"pedidos/pageInitials", {data:0},
        function (data, textStatus, jqXHR) {
            $(".pagination ul")
            .empty()
            .append(data);
        },
        "text"
    );

    var letrainicial = operacion == 1 ? "1":"S";

    $.post(RUTA+"pedidos/ItemsByLetter", {tipo:operacion,letra:letrainicial},
            function (data, textStatus, jqXHR) {
                $("#tableItems tbody")
                    .empty()
                    .append(data);
                
                $("#waitmodal").fadeOut();
                
            },
            "text"
    )
    
    $("#waitmodal").fadeOut();
    $("#modalItems").fadeIn();

    return false;
});

$("#imporItems").on("click", function (e) {
    e.preventDefault();

    if ( $("#tipo").val().length <= 0 ) {
        mostrarMensaje("msj_error","Seleccione el tipo");
        $("#tipo").focus();
        
        return false;
    }
    
    $("#modalImport").fadeIn();

    return false;
});

$("#inputSearchItems").on("keypress", function (e) {
    //e.preventDefault()
    if(e.which == 13 && $(this).val().length > 2) {
        $("#waitmodal").fadeIn();
        $.post(RUTA+"pedidos/ItemsByWord", {palabra:$(this).val(),tipo:operacion},
            function (data, textStatus, jqXHR) {
                $("#tableItems tbody")
                    .empty()
                    .append(data);
                $("#waitmodal").fadeOut();  
            },
            "text"
        );
    }
});

$(".pagination").on("click","a",function(e){
    e.preventDefault();

    $("#waitmodal").fadeIn();

    $.post(RUTA+"pedidos/ItemsByLetter", {tipo:operacion,letra:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                $("#tableItems tbody")
                    .empty()
                    .append(data);
                
                $("#waitmodal").fadeOut();
            },
            "text"
    )

    return false;
})

//selecciona item de la lista
$("#tableItems").on("click","tr", function (e) {
    e.preventDefault();

    var codigo = $(this).children('td:eq(0)').text();
    var indice = 0;
    var descripcion = $(this).children('td:eq(1)').text() + " " + $(this).children('td:eq(2)').text() + " " + $(this).children('td:eq(3)').text();
    var unidad = $(this).children('td:eq(5)').text();
    var factor = $(this).children('td:eq(6)').text();
    var ncodmed = $(this).children('td:eq(0)').data('unidad')
    
    tipo = operacion == 1 ? "BIEN" : "SERV";
    
    item = $("#detalle_pedido tbody tr").length + 1;

    let exist = checkExistTable($("#detalle_pedido tbody > tr"),codigo,3);

    if ( !exist ) {
        var row = '<tr class="lh1_2rem grabar">'+
                '<td class="con_borde centro"><a href="#" data-topcion="edit"><i class="far fa-edit"></i></a></td>'+
                '<td class="con_borde centro"><a href="#" data-topcion="delete"><i class="fas fa-eraser"></i></a></td>'+
                '<td class="con_borde drch pr20">'+ $.strPad(item,3) +'</td>'+
                '<td class="con_borde centro" data-indice="'+ indice +'" data-unidad="'+ ncodmed +'">'+ codigo +'</td>'+
                '<td class="con_borde pl10">'+ descripcion +'</td>'+
                '<td class="con_borde centro" data-factor="'+ factor +'">'+ unidad +'</td>'+
                '<td class="con_borde" contenteditable="true"><input type="number" class="drch"></td>'+
                '<td class="con_borde"></td>'+
                '<td class="con_borde"></td>'+
                '<td class="con_borde centro" contenteditable="true"><input type="checkbox" class="drch"></td>'+
                '<td class="con_borde centro">'+ tipo +'</td>'+
            '</tr>'
    
        $("#detalle_pedido tbody").append(row);
        $("#saveItem span").addClass('parpadea');

    }else{
        mostrarMensaje("msj_error","El Item ya fue seleccionado");
    }
    
    return false;
});

//acciones de la tabla de detalles
$("#detalle_pedido tbody").on("click",'a', function (e) {
    e.preventDefault();

    if ($(this).data("topcion") == "delete")
        $(this).parent().parent().remove();

    fill3Tables($("#detalle_pedido tbody > tr"),2);

    return false;
});

//añadir item catalogo
$("#newItem").on("click", function (e) {
    e.preventDefault();

    $(".addItemWindow").css('right',"0");
    return false;
});

//verificar las fechas de vencimiento
$("#fechaven").change(function (e) { 
    e.preventDefault();

    if (diferenciadefechas($(this).val(),$("#fecha").val()) < 3 ) {
        mostrarMensaje("msj_error","Verifique la fecha de limite del pedido");
        return false;
    }
    
    return false;
});

 //subir archivo excel
$("#importForm").on("submit", function (e) {
    e.preventDefault()

    operacion = $("#tipo").val() == '01 BIENES' ? 1 : 2;
    
    $("#operacion").val(operacion);

    $.ajax({
        // URL to move the uploaded image file to server
        url: RUTA + 'pedidos/getImportFile',
        // Request type
        type: "POST", 
        // To send the full form data
        data: new FormData( this ),
        contentType:false,      
        processData:false,     
        // UI response after the file upload  
        success: function(data)
        {
            $("#detalle_pedido tbody")
            .empty()
            .append(data);

            $("#modalImport").fadeOut();
        }
    });
    
    return false;
});

//aceptar para subir los datos del archivo
$("#btnConfirmImport").on("click", function (e) {
    e.preventDefault();

    $("#importForm").trigger("submit");

    return false;
});

$("#btnCancelImport").on("click", function (e) {
    e.preventDefault();

    $("#importForm")[0].reset();
    $("#modalImport").fadeOut();
   
    return false;
});

//grabar Pedido 
$("#saveItem").on("click", function (e) {
    e.preventDefault();

    if ($("#cod_proy").val().length <= 0) {
        mostrarMensaje("msj_error","Seleccione el proyecto");
        return false;
    }else if($("#cod_area").val().length <= 0){
        mostrarMensaje("msj_error","Seleccione el área");
        return false;
    }else if($("#cod_cost").val().length <= 0){
        mostrarMensaje("msj_error","Seleccione el centro de costos");
        return false;
    }else if($("#cod_transporte").val().length <= 0){
        mostrarMensaje("msj_error","Seleccione el tipo de transporte");
        return false;
    }else if($("#concepto").val().length <= 0){
        mostrarMensaje("msj_error","Rellene el concepto");
        return false;
    }else if($("#cod_solicitante").val().length <= 0){
        mostrarMensaje("msj_error","Seleccione el solicitante");
        return false;
    }else if($("#cod_tipo").val().length <= 0){
        mostrarMensaje("msj_error","Seleccione el tipo de pedido");
        return false;
    }else if($("#detalle_pedido tbody tr").length <= 0){
        mostrarMensaje("msj_error","Añada productos al pedido");
        return false;
    }else if(diferenciadefechas($("#fechaven").val(),$("#fecha").val()) < 3) {
        mostrarMensaje("msj_error","Verifique la fecha de venvimiento del pedido");
        return false;
    }else if(checkCantTables($("#detalle_pedido tbody > tr"),6)){
        mostrarMensaje("msj_error","Un Item en el pedido esta en 0, verificar");
        return false;
    }

    let result = {};
    
    $.each($("#formProcess").serializeArray(),function(){
        result[this.name] = this.value;
    })

    $.post(RUTA+"pedidos/registro", {act:accion,
                                     cabecera:result,
                                     detalles:JSON.stringify(registerItemsTable())},
        function (data, textStatus, jqXHR) {
            if(data){
                $("#fileAtachs").trigger("submit");
                $("#saveItem span").removeClass('parpadea');
                mostrarMensaje("msj_correcto","Pedido registrado");
            }else{
                mostrarMensaje("msj_error","No se registro el pedido");
            }
        },
        "text"
    );
    
    return false;
});

 //enviar emails
$("#sendItem").on("click", function (e) {
    e.preventDefault();

    $("#dialogSend").fadeIn();
    
    return false;
});

//llamar lista de correos
$("#callEmailList").on("click", function (e) {
    e.preventDefault();

    $(".listEmails").css('right',"0");

    $.post(RUTA+"pedidos/correos", {data:0},
        function (data, textStatus, jqXHR) {
            $("#corporativos tbody")
            .empty()
            .append(data);
        },
        "text"
    );

    return false;
});

//grabar los pedidos
$("#btnSendConfirm").on("click", function (e) {
    e.preventDefault();
    
    if ($("#saveItem span").hasClass('parpadea')){
        mostrarMensaje("msj_error","Por favor grabe el pedido");
        return false;
    }else if($("#listMailToSend tbody tr").length <= 0){
        mostrarMensaje("msj_error","No ha seleccionado un correo válido");
        return false;
    }else if ($("#mail_mssg").val() <= 0 ){
        mostrarMensaje("msj_error","Escriba el mensaje para el envio");
        return false;
    }

    $.post(RUTA+"pedidos/mailProcess", {msg:$("#mail_mssg").val(),
                                        correos:JSON.stringify(getMails()),
                                        pedido:$('#cod_pedido').val(),
                                        tipo:$("#cod_tipo").val(),
                                        titulo:tituloCorreo,
                                        estado:2},
        function (data, textStatus, jqXHR) {
            if (data == '1') {
                mostrarMensaje("msj_info","Mensajes enviados correctamente");
            }else {
                mostrarMensaje("msj_info","No se enviaro los mensajes.. ");
            }
        },
        "text"
    );
    
    return false;
});

//cancelar los pedidos
$("#btnSendCancel").on("click", function (e) {
    e.preventDefault();
    $("#dialogSend").fadeOut();
    return false;
});

//PROCESO PARA ADJUTAR ARCHVOS
//subir archivos
$("#upAttach").on("click", function (e) {
    e.preventDefault();
    
    $("#reference").val( $("#cod_pedido").val() );
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

    $("#modalAtach").fadeOut();
    return false;
});

$("#btnCancelAtach").on("click", function (e) {
    e.preventDefault();

    $("#modalAtach").fadeOut();
    $("#fileAtachs")[0].reset();
    $("#tableAdjuntos tbody").empty();

});

$("#btnConfirmAtach").on("click", function (e) {
    e.preventDefault();

    atachs = $("#tableAdjuntos tbody tr").length;
    
    $("#modalAtach").fadeOut();

    return false;
});

//añadir registro de adjuntos
$("#fileAtachs").on("submit", function (e) {
    e.preventDefault()

    $.ajax({
        // URL to move the uploaded image file to server
        url: RUTA + 'pedidos/uploadCotAtachs',
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
            //Procedimiento para subir los adjuntos
            FILES = [];

            $.each (data,function(i){
                FILES.push(data[i]);
            });

            $.post(RUTA + "pedidos/saveAtachs", {data:JSON.stringify(FILES)},
                function (data, textStatus, jqXHR) {
                    $("#modalAtach").fadeOut();
                    $("#fileAtachs")[0].reset();
                    $("#tableAdjuntos tbody").empty();
                },
                "text"
            );
        }
    });
    
    return false;
});

$("#fecha, #concepto, #espec_items").on("change", function (e) {
    e.preventDefault();

    $("#saveItem span").addClass('parpadea');

    return false;
});

$("#detalle_pedido tbody").on("change","input", function (e) {
    e.preventDefault();

    $(this).parent().parent().css("background","#c2c2c2");
    $(this).parent().parent().addClass("grabar");
    $("#saveItem span").addClass('parpadea');
    
    return false;
});

$("#requestAprob").click(function (e) { 
    e.preventDefault();
    
    $.post(RUTA+"pedidos/correosPedidos",{codigoProyecto:$("#cod_proy").val(),accion:4},
        function (data, textStatus, jqXHR) {
            $("#listMailToSend tbody").empty().append(data);
            $("#dialogSend h4").text("Solicitar Aprobación");
            $("#dialogSend").fadeIn();
            tituloCorreo = "Aprobación de Pedido";
            estadoPedido = 3;
        },
        "text"
    );
    
    return false;
});

$("#sendItem").click(function (e) { 
    e.preventDefault();
    
    $.post(RUTA+"pedidos/correosPedidos",{codigoProyecto:$("#cod_proy").val(),accion:5},
        function (data, textStatus, jqXHR) {
                $("#listMailToSend tbody").empty().append(data);
                $("#dialogSend h4").text("Verificar Almacen");
                $("#dialogSend").fadeIn();
                tituloCorreo = "Verificar Almacen";
                estadoPedido = 2;
            },
            "text"
        );
        
        return false;
    });
})



function dataPreview(){
    DATA = [];
    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        var ITEM        = $(this).find('td').eq(2).text(),
            CODITEM     = $(this).find('td').eq(3).text(),
            DESCRIPCION = $(this).find('td').eq(4).text(),
            CANTIDAD    = $(this).find('td').eq(6).children().val(),
            ESTADO      = $(this).find('td').eq(7).text(),
            UNIDAD      = $(this).find('td').eq(5).text(),
            NUMERO      = $("#numero").val(),
            FECHA       = $("#fecha").val(),
            USUARIO     = $("#usuario").val(),
            PROYECTO    = $("#proyecto").val(),
            AREA        = $("#area").val(),
            COSTOS      = $("#costos").val(),
            TRANSPORTE  = $("#transporte").val(),
            CONCEPTO    = $("#concepto").val(),
            SOLICITANTE = $("#solicitante").val(),
            ESTREG      = $("#registro").val(),
            ESTDOC      = $("#documento").val(),
            TIPO        = $("#tipo").val(),
            ESPEC       = $("#espec_items").val(),
            DOCTIP      = "VISTA PREVIA";

        item = {};

        if ( ITEM !== '' ){
            item["item"]        = ITEM;
            item["coditem"]     = CODITEM;
            item["cantidad"]    = CANTIDAD;
            item["descripcion"] = DESCRIPCION;
            item["estado"]      = ESTADO;
            item["unidad"]      = UNIDAD;
            item["numero"]      = NUMERO;
            item["fecha"]       = FECHA;
            item["usuario"]     = USUARIO;
            item["proyecto"]    = PROYECTO;
            item["area"]        = AREA;
            item["costos"]      = COSTOS;
            item["transporte"]  = TRANSPORTE;
            item["concepto"]    = CONCEPTO;
            item["solicitante"] = SOLICITANTE;
            item["registro"]    = ESTREG;
            item["documento"]   = ESTDOC;
            item["tipo"]        = TIPO;
            item["espec_items"] = ESPEC;
            item["doctip"]      = DOCTIP;

            //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
            DATA.push(item);
        }
    });

    INFO    = new FormData();
    aInfo   = JSON.stringify(DATA);

    INFO.append('data', aInfo);

    $.ajax({
        data: INFO,
        type: 'POST',
        url : RUTA + 'pedidos/genPreview',
        processData: false, 
        contentType: false,
        dataType:"text",
        success : function(response) {
            $(".insidePreview object")
            .attr("data","")
            .attr("data",response)
        },
        error : function(xhr, status) {
            alert('Disculpe, existió un problema');
        },
    });
}

function registerItemsTable(){
if ( $("#detalle_pedido tbody tr").length > 0) {
    ITEMS = [];
    VERIFICA = false;

    var TABLA = $("#detalle_pedido tbody > tr");

    TABLA.each(function(){
        var INDICE  = $(this).find('td').eq(3).data("indice"), 
            CODITEM = $(this).find('td').eq(3).text(),
            CANTIDAD= $(this).find('td').eq(6).children().val(),
            UNIDAD  = $(this).find('td').eq(3).data("unidad"),
            VERIFICA= $(this).find('td').eq(9).children().prop("checked"),
            GRABAR  = $(this).hasClass("grabar");

        
        item = {};

        if ( GRABAR ){
            item["indice"]   = INDICE;
            item["coditem"]  = CODITEM;
            item["cantidad"] = CANTIDAD;
            item["unidad"]   = UNIDAD;
            item["verifica"] = VERIFICA;
            item["grabar"]   = GRABAR;

            //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
            ITEMS.push(item);
        }
    });

    return ITEMS;
}
}

function getListRequest(){
$.post(RUTA+"pedidos/mainList", data=0,
    function (data, textStatus, jqXHR) {
        $("#tabla_pedidos tbody")
            .empty()
            .append(data);
    },
    "text"
);
}

function getMails(){
if ( $("#listMailToSend tbody tr").length > 0){
    DATA = [];
    

    var TABLA = $("#listMailToSend tbody > tr");

    TABLA.each(function(){
        item = {};

        var CHECKED = $(this).find('td').eq(0).children().prop('checked');
            MAIL    = $(this).find('td').eq(2).text();
        
        if (CHECKED) {
            item["mail"]    = MAIL;
            item['checked'] = CHECKED;
        }

        DATA.push(item);
    })
}

return DATA;
}

