$(function() { 
    var accion = "";
    var coddpto = '', codprov = '' , coddist = '';

    activar_opcion();

    //nuevo registro 
    $("#newreg").on("click", function (e) {
        e.preventDefault();
        accion = "n";

        activar_controles();
        $("form")[0].reset();

        $("#descripcion_almacen").focus();

        $.post(RUTA + "almacenes/createCode", {data:"0"},
            function (data, textStatus, jqXHR) {
                $("#codigo_almacen").val(data);
            },
            "text"
        );

        return false;
    });

    $("#editreg").on("click", function (e) {
        e.preventDefault();
        accion = "u";

        activar_controles();

        $("#descripcion_almacen").focus();

        return false;
    });

    $("#cancelreg").on("click", function (e) {
        e.preventDefault();

        desactivar_controles();

        return false;
    });

    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#descripcion_almacen").val().length <= 0  ){
            $("#descripcion_almacen").focus();
            mostrarMensaje("msj_error","Rellene la descripcion");
            return false;
        }

        $("#formItem").trigger("submit");
    });

    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + 'almacenes/listAll',function(data,textStatus,jqXHR){
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

    //cerrar el modal de busqueda
    $("#closeModal").on("click", function (e) {
        e.preventDefault()

        $("#modalSeek").fadeOut();
        
        return false;
    });

    //filtardo cuando se escribe en la tabla de busqueda
    $("#inputSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#tableSeek tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //selecciona item de la lista
    $("#tableSeek").on("click","tr", function (e) {
        e.preventDefault();

        $.post(RUTA + "almacenes/listById", {idx:$(this).data("idx")},
            function (data, textStatus, jqXHR) {
                $("#index_almacen").val(data.ncodalm);
                $("#ubigeo").val(data.ncubigeo);
                $("#codigo_almacen").val(data.ccodalm);
                $("#descripcion_almacen").val(data.cdesalm);
                $("#via_tipo").val(data.ctipovia);
                $("#via_nombre").val(data.cdesvia);
                $("#numero").val(data.cnrovia);
                $("#zona").val(data.czonavia);
                $("#estado").val(data.nflgactivo);
                $("#dpto").val(data.cdesdpto);
                $("#prov").val(data.cdesprov);
                $("#dist").val(data.cdesdist);
            },
            "json"
        );

        $("#editreg, #deletereg").removeClass("desactivado");
        $("#modalSeek").fadeOut();
        
        return false;
    });

    //enviar los datos al servidor 
    $("#formItem").on("submit", function (e) {
        e.preventDefault();

        var data = $(this).serialize();

        if(accion == "n") {
            $.post(RUTA + "almacenes/newReg",data,
            function (data, textStatus, jqXHR) {
                if (data) {
                    mostrarMensaje("msj_info","Registro Creado");
                }else {
                    mostrarMensaje("msj_error","No se creo el registro");
                }
            },
            "text");
        }else {
            $.post(RUTA + "almacenes/updReg",data,
            function (data, textStatus, jqXHR) {
                if (data) {
                    mostrarMensaje("msj_info","Registro Actualizado");
                }else {
                    mostrarMensaje("msj_error","No se actualizo registro");
                }
            },
            "text");
        }

        desactivar_controles();

        return false;
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

    $("#btnConfirm").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();
        $("#editreg,#deletereg").addClass('desactivado');

        $.post(RUTA+"almacenes/delReg", {idx:$("#index_almacen").val()},
            function (data, textStatus, jqXHR) {

                if (data){
                    mostrarMensaje("msj_info","Registro eliminado");

                    $("form")[0].reset();
                }else {
                    mostrarMensaje("msj_error","Error al eliminar registro");
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
 })

function desactivar_controles(){
    $(this).addClass('desactivado');
    $(".floatingButton, .formbody, #savereg, #cancelreg, .formulario").addClass('desactivado');
    $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
    $("#editreg, #deletereg").addClass("desactivado");
    $(".seleccion").fadeOut();

    $("form")[0].reset();
}

function activar_controles(){
    $(this).addClass('desactivado');
    $("#searchreg, #exportexcel, #home, #newreg").addClass('desactivado');
    $(".floatingButton, .formbody, #savereg, #cancelreg, .formulario").removeClass('desactivado');
    $("#editreg, #deletereg").addClass("desactivado");
    $(".seleccion").fadeOut();
    $(".formulario table tbody").empty();
}