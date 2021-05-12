$(function(){
    var accion = "";
    var cont = 0;
    var coddpto = '', codprov = '' , coddist = '';

    activar_opcion();

    //agregar un registro
    $("#newreg").on("click", function (e) {
        e.preventDefault();

        activar_controles();
        $(".formulario table tbody").empty();

        $("form")[0].reset();
        $("form")[1].reset();
        $("form")[2].reset();

        accion = "n";

        return false;
    });

    //ediatr registro
    $("#editreg").on("click", function (e) {
        e.preventDefault();

        activar_controles()

        accion = "u";

        return false;
    });

    //cancelar el registro
    $("#cancelreg").on("click", function (e) {
        e.preventDefault();
        desactivar_controles();
        return false;
    });

    //buscar proveedor
    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + 'proveedores/listEnt',function(data,textStatus,jqXHR){
            $("#tableSeek tbody")
            .empty()
            .append(data);
        })

        $("#modalSeek").fadeIn();
        
        return false;
    });

    $("#deletereg").on("click", function(e){
        e.preventDefault();

        $("#dialogConfirm").fadeIn();

        return false;
    })

    //selecciona item de la lista
    $("#tableSeek tbody").on("click","tr", function (e) {
        e.preventDefault();

        $.post(RUTA+"proveedores/seekById", {id: $(this).data("id")},
            function (data, textStatus, jqXHR) {
                $("#estado").val(data.nflgactivo);
                $("#index_proveedor").val(data.id_centi);
                $("#codigo_documento").val(data.ctipdoc);
                $("#codigo_persona").val(data.ctipper);
                $("#codigo_entidad").val(data.ctipenti);
                $("#codigo_pais").val(data.ncodpais);
                $("#codigo_pago").val(data.ncondpag);
                $("#ubigeo").val(data.ncubigeo);
                $("#razon_social").val(data.crazonsoc);
                $("#tip_doc").val(data.det_doc);
                $("#tip_ent").val(data.det_enti);
                $("#numero_documento").val(data.cnumdoc);
                $("#tip_persona").val(data.det_per);
                $("#correo").val(data.cemail);
                $("#ape_paterno").val(data.capepat);
                $("#ape_materno").val(data.capemat);
                $("#nombre_proveedor").val(data.cnombre1);
                $("#nombre_comercial").val(data.cnomcom);
                $("#telefono").val(data.ctelefono);
                $("#pais").val(data.det_pais);
                $("#direccion").val(data.cviadireccion);
                $("#numero").val(data.cvianro);
                $("#interior").val(data.cviainterior);
                $("#zona").val(data.cviazona);
                $("#dpto").val(data.det_dpto);
                $("#prov").val(data.det_prov);
                $("#dist").val(data.det_dist);
                $("#pago").val(data.det_pago);
                
                data.nagenper == 1 ? $("#agente_percepcion").prop("checked",true) : $("#agente_percepcion").prop("checked",false);
                data.nagenret == 1 ? $("#agente_recepcion").prop("checked",true) : $("#agente_recepcion").prop("checked",false);
                
                $("#porcentaje").val(data.nagepor);
                $("#monto_minimo").val(data.nagemin);

                data.ncondicion == 1 ? $("#habido").prop("checked",true) : $("#no_habido").prop("checked",true);
                data.ndigempadron == 1 ? $("#empadronado_si").prop("checked",true) : $("#empadronado_no").prop("checked",true);

                $("#nro_registro").val(data.ndigregis);
                $("#categoria").val(data.cdigcateg);
                $("#situacion").val(data.cdigsitua);
                
                if (data.ncalifica > 0 ){
                    for (let x = 0; x < data.ncalifica; x++) {
                        var c = ".calificacion div:eq("+x+")"; 
                        $(c).addClass("calificado");   
                    }
                }

                $.post(RUTA+"proveedores/getContactById", {id:$("#index_proveedor").val()},
                    function (data, textStatus, jqXHR) {
                        $("#tableContact tbody")
                            .empty()
                            .append(data);
                    },
                    "text"
                );

                $.post(RUTA+"proveedores/getBancktById", {id:$("#index_proveedor").val()},
                function (data, textStatus, jqXHR) {
                    $("#tableBanks tbody")
                        .empty()
                        .append(data);
                },
                "text"
            );

            },
            "json"
        );

        $("#editreg, #deletereg").removeClass("desactivado");
        $("#modalSeek").fadeOut();
        
        return false;
    });

    //cerrar la venta de busqueda
    $(".buttonClose").on("click", function (e) {
        e.preventDefault()

        $(".modal").fadeOut();
 
        return false;
    });

    //grabar registro
    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#codigo_documento").val().length <= 0 ) {
            mostrarMensaje("msj_error","Seleccione el tipo de documento");
            $("#tip_doc").focus();
            return false;
        }else if( $("#codigo_entidad").val().length <= 0 ){
            mostrarMensaje("msj_error","Seleccione el tipo entidad");
            $("#tip_ent").focus();
            return false;
        }else if( $("#numero_documento").val().length <= 0 ){
            mostrarMensaje("msj_error","Rellene el numero de documento");
            $("#numero_documento").focus();
            return false;
        }else if( $("#codigo_persona").val().length <= 0 ){
            mostrarMensaje("msj_error","Seleccione el tipo de persona");
            $("#tip_persona").focus();
            return false;
        }else if( $("#razon_social").val().length <= 0 ){
            mostrarMensaje("msj_error","Rellene la razón social");
            $("#razon_social").focus();
            return false;
        }else if( !emailreg.test($("#correo").val()) && $("#mail_contac").val() != '' ){
            mostrarMensaje("msj_error","Formato de correo inválido");
            $("#correo").focus();
            return false;
        }else if( $("#direccion").val().length <= 0 ){
            mostrarMensaje("msj_error","Rellene la dirección");
            $("#direccion").focus();
            return false;
        }

        //envia los datos para grabar
        $("#dataItem").trigger("submit");

        return false;
    });

    $("#exportexcel").on("click", function (e) {
        e.preventDefault();
        //registerBanks('11')
        return false;        
    });

    //enviar los dartos para grabar
    $("#dataItem").submit(function (e) { 
        e.preventDefault();
        
        var str = $("#dataItem").serialize();

        if ( accion == "n" ) {
            $.post(RUTA+"proveedores/checkExist", {'documento':$("#numero_documento").val(),'razon':$("#razon_social").val()},
                function (data, textStatus, jqXHR) {
                    if (!data){
                        $.post(RUTA+"proveedores/newProv", str,
                            function (data, textStatus, jqXHR) {
                                if (data){
                                    mostrarMensaje("msj_info","Datos grabados");
                                    $("#index_proveedor").val(data);
                                    registerContacts(data);
                                    registerBanks(data);
                                    desactivar_controles();
                                }else {
                                    mostrarMensaje("msj_error","Error al grabar los datos");
                                }
                            },
                            "text"
                        );    
                    }else {
                        mostrarMensaje("msj_error","La entidad ya esta registrada");
                    }
                },
                "text"
            );
        }else {
            var idprov = $("#index_proveedor").val();
            
            $.post(RUTA+"proveedores/editProv", str,
                function (data, textStatus, jqXHR) {
                    if(data){
                        mostrarMensaje("msj_info","Datos actualizados");
                        registerContacts(idprov);
                        registerBanks(idprov);
                        console.log(idprov);
                        desactivar_controles();
                    }else{
                        mostrarMensaje("msj_error","Error al actualizar los datos");
                    }
                },
                "text"
            );
        }
        return false
    });

    //agregar contacto
    $("#addContact").on("click", function (e) {
        e.preventDefault();

        if (cont <= 10) {
            console.log(cont)
            $("#dialogContact").fadeIn();
            cont++;
        }else {
            mostrarMensaje("msj_error","Máximo 10 registros");
        }

        return false;
    });

    //agregar tipo de cuenta
    $("#addBank").on("click", function (e) {
        e.preventDefault();

        if (cont <= 10) {
            console.log(cont)
            $("#dialogBank").fadeIn();
            cont++;
        }else {
            mostrarMensaje("msj_error","Máximo 10 registros");
        }

        return false;
    });

    //selecciona tabs
    $(".menu_horizontal a").on("click", function (e) {
        e.preventDefault();

        $("li").removeClass("selected");
        $(this).parent().addClass("selected");

        var activarForm = $(this).data("index");
        
        $(".datos").hide();

        switch (activarForm) {
            case 1:
                $("#form1").fadeIn();
                break
            case 2:
                $("#form2").fadeIn();
                break;
            case 3:
                $("#form3").fadeIn();
                break;
            case 4:
                $("#form4").fadeIn();
                break;   
        }

        return false;
    });

    //activar el cuadro de tipo de documento
    $("#tip_doc").focus(function (e) { 
        e.preventDefault();
        
        $(this).val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#td").on("click","a", function (e) {
        e.preventDefault();

        $("#codigo_documento").val($(this).attr("href"));
        $("#tip_doc").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //activar el cuadro de tipo de persona
    $("#tip_persona").focus(function (e) { 
        e.preventDefault();
        
        $(this).val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#tp").on("click","a", function (e) {
        e.preventDefault();

        $("#codigo_persona").val($(this).attr("href"));
        $("#tip_persona").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

     //activar el cuadro de tipo de entidad
     $("#tip_ent").focus(function (e) { 
        e.preventDefault();
        
        $(this).val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#te").on("click","a", function (e) {
        e.preventDefault();

        $("#codigo_entidad").val($(this).attr("href"));
        $("#tip_ent").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //activar el cuadro de paises
    $("#pais").focus(function (e) { 
        e.preventDefault();
        
        $(this).val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#listPais").on("click","a", function (e) {
        e.preventDefault();

        $("#codigo_pais").val($(this).attr("href"));
        $("#pais").val($(this).text());

        if ( $(this).attr("href") == 135 ) {
            $(".tree_items_selection_tree").removeClass("desactivado");

        }else {
            $(".tree_items_selection_tree").addClass("desactivado");
            $("#dpto, #prov, #dist, #ubigeo").val('');
        }

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //activar el tipo de pago
    $("#pago").focus(function (e) { 
        e.preventDefault();
        
        $(this).val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#listaPago").on("click","a", function (e) {
        e.preventDefault();

        $("#codigo_pago").val($(this).attr("href"));
        $("#pago").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //buscar por pais
    $("#pais").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#listPais ul li a").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //activar el cuadro de departamento
    $("#dpto").focus(function (e) { 
        e.preventDefault();
        
        $("#ubigeo").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#comboDpto").on("click","a", function (e) {
        e.preventDefault();

        $("#ubigeo").val($(this).attr("href"));
        $("#dpto").val($(this).text());

        $("#prov, #dist").val('');
        codprov = '';
        coddist = '';

        coddpto = $(this).attr("href");

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //activar el cuadro de provincia
    $("#prov").focus(function (e) { 
        e.preventDefault();
        
        $("#ubigeo").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        if (coddpto == "")
            return false; 
        
        $.each($("#comboProv ul li a"), function() {
            codprov = $(this).attr('href');
            if( codprov.substr(0,2) === coddpto)
                $(this).show();
            else
                $(this).hide();
        });

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#comboProv").on("click","a", function (e) {
        e.preventDefault();

        $("#ubigeo").val($(this).attr("href"));
        $("#prov").val($(this).text());
        $("#dist").val('');

        codprov = $(this).attr("href");

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //activar el cuadro de provincia
    $("#dist").focus(function (e) { 
        e.preventDefault();
        
        $("#ubigeo").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        if (codprov == "")
            return false; 

        $.each($("#comboDist ul li a"), function() {
            coddist = $(this).attr('href');
            if( coddist.substr(0,4) === codprov)
                $(this).show();
            else
                $(this).hide();
        });

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#comboDist").on("click","a", function (e) {
        e.preventDefault();

        $("#ubigeo").val($(this).attr("href"));
        $("#dist").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //agregar contactos
    $("#btnAceptarContac").on("click", function (e) {
        e.preventDefault();

        if($("#nom_contact").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el nombre del contacto");
            $("#nom_contact").focus();
            return false;
        }else if(!emailreg.test($("#mail_contac").val()) && $("#mail_contac").val() != '') {
            mostrarMensaje("msj_error","Correo eléctronico no válido");
            $("#mail_contac").focus();
            return false;
        }

        var row = '<tr id="newreg">'+
                        '<td>'+$("#nom_contact").val().toUpperCase()+'</td>'+
                        '<td>'+$("#mail_contac").val().toLowerCase()+'</td>'+
                        '<td>'+$("#dir_contac").val().toUpperCase()+'</td>'+
                        '<td>'+$("#telefono_contact").val()+"</td>"+
                        '<td class="centro"><a href="#"><i class="far fa-edit"></i></a></td>'+
                        '<td class="centro"><a href="#"><i class="far fa-trash-alt"></i></a></td>'+
                    "</tr>"

        $("#tableContact").append(row);

        $("form")[0].reset();

        $("#nom_contact").focus();

        return false;
    });

    $("#btnCancelarContac").on("click", function (e) {
        e.preventDefault();

        $("form")[0].reset();
        $("#dialogContact").fadeOut();

        return false;
    });

    //agregar bancos
    $("#btnAceptarBank").on("click", function (e) {
        e.preventDefault();

        if($("#numero_cuenta").val().length <= 0) {
            mostrarMensaje("msj_error","Rellene numero de cuenta");
            $("#numero_cuenta").focus();
            return false;
        }else if ($("#nombre_banco").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el nombre de la entidad");
            $("#nombre_banco").focus();
            return false;
        }else if ($("#moneda").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el tipo de cuenta");
            $("#moneda").focus();
            return false;
        }else if ($("#tipo_cuenta").val().length <= 0){
            mostrarMensaje("msj_error","Rellene el tipo de cuenta");
            $("#tipo_cuenta").focus();
            return false;
        }

        var row = '<tr id="newreg">'+
                        '<td>'+$("#numero_cuenta").val()+'</td>'+
                        '<td>'+$("#nombre_banco").val().toUpperCase()+'</td>'+
                        '<td>'+$("#moneda").val().toUpperCase()+'</td>'+
                        '<td>'+$("#tipo_cuenta").val().toUpperCase()+"</td>"+
                        '<td class="oculto" id="mon">'+$("#moneda_num").val()+"</td>"+
                        '<td class="oculto" id="ban">'+$("#banco_cod").val()+"</td>"+
                        '<td class="centro"><a href="#"><i class="far fa-edit"></i></a></td>'+
                        '<td class="centro"><a href="#"><i class="far fa-trash-alt"></i></a></td>'+
                    "</tr>"

        $("#tableBanks").append(row);
        $("form")[1].reset();
        $("#numero_cuenta").focus();

        return false;
    });

    $("#btnCancelarBank").on("click", function (e) {
        e.preventDefault();

        $("form")[1].reset();
        $("#dialogBank").fadeOut();

        return false;
    });

    //activar el cuadro de tipo de documento
    $("#moneda").focus(function (e) { 
        e.preventDefault();
        
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#monedas").on("click","a", function (e) {
        e.preventDefault();

        $("#moneda").val($(this).data("cmoneda"));
        $("#moneda_num").val($(this).attr("href"));

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //activar el cuadro entidad financiera
    $("#nombre_banco").focus(function (e) { 
        e.preventDefault();
        
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $("#bancos").on("click","a", function (e) {
        e.preventDefault();

        $("#nombre_banco").val($(this).text());
        $("#banco_cod").val($(this).attr("href"));

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    $("#btnConfirm").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();
        $("#editreg,#deletereg").addClass('desactivado');

        $.post(RUTA+"proveedores/remProv", {id:$("#index_proveedor").val()},
            function (data, textStatus, jqXHR) {

                if (data){
                    mostrarMensaje("msj_info","Registro eliminado");

                    $(".formulario table tbody").empty();
            
                    $("form")[0].reset();
                    $("form")[1].reset();
                    $("form")[2].reset();
                }else {
                    mostrarMensaje("msj_info","Error al eliminar registro");
                }
                
            },
            "text"
        );
        return false;
    });

    $("#btnCancel").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();
        $("#editreg,#deletereg").addClass('desactivado');
        
        return false;
    });


    $("#inputSearchItems").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tableSeek tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
})

function desactivar_controles(){
    $(this).addClass('desactivado');
    $(".floatingButton, .formbody, #savereg, #cancelreg").addClass('desactivado');
    $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
    $("#editreg, #deletereg").addClass("desactivado");
    $(".seleccion").fadeOut();
    $(".formulario table tbody").empty();

    $("form")[0].reset();
    $("form")[1].reset();
    $("form")[2].reset();
}

function activar_controles(){
    $(this).addClass('desactivado');
    $("#searchreg, #exportexcel, #home, #newreg").addClass('desactivado');
    $(".floatingButton, .formbody, #savereg, #cancelreg").removeClass('desactivado');
    $("#editreg, #deletereg").addClass("desactivado");
    $(".seleccion").fadeOut();
}

function registerContacts(codigo){
    if ( $("#tableContact tbody tr").length > 0) {
            
        var DATA = [];
        var TABLA = $("#tableContact tbody > #newreg");

        TABLA.each(function(){
            var NOM = $(this).find('td').eq(0).text(),
                COR = $(this).find('td').eq(1).text(),
                DIR = $(this).find('td').eq(2).text(),
                TEL = $(this).find('td').eq(3).text(),
                COD = codigo

            item = {};
                
            if( NOM !== ''){
                item["nom"] = NOM;
                item['cor'] = COR;
                item["dir"] = DIR;
                item["tel"] = TEL;
                item["cod"] = COD;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                DATA.push(item);
            }
        })

        INFO    = new FormData();
        aInfo   = JSON.stringify(DATA);
 
        INFO.append('data', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'proveedores/newContacts',
            processData: false, 
            contentType: false,
        });
        
    }else{
        console.log("No se registro ningun documento");
    }
}

function registerBanks(codigo){

    if ( $("#tableBanks tbody tr").length > 0) {
            
        var DATA = [];
        var TABLA = $("#tableBanks tbody > #newreg");

        TABLA.each(function(){
            var CTA = $(this).find('td').eq(0).text(),
                CBA = $(this).find('td').eq(5).text(),
                MON = $(this).find('td').eq(2).text(),
                TIC = $(this).find('td').eq(3).text(),
                MNN = $(this).find('td').eq(4).text(),
                COD = codigo

            item = {};
                
            if( CTA !== ''){
                item["cta"] = CTA;
                item['cba'] = CBA;
                item["mon"] = MON;
                item["tic"] = TIC;
                item["mnn"] = MNN;
                item["cod"] = COD;

                //una vez agregados los datos al array "item" declarado anteriormente hacemos un .push() para agregarlos a nuestro array principal "DATA".
                DATA.push(item);
            }
        })

        INFO    = new FormData();
        aInfo   = JSON.stringify(DATA);
 
        INFO.append('data', aInfo);

        $.ajax({
            data: INFO,
            type: 'POST',
            url : RUTA + 'proveedores/newBanks',
            processData: false, 
            contentType: false,
        });
    }else{
        console.log("No se registro ningun documento");
    }
}