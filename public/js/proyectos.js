$(function(){
    var accion = "";

    activar_opcion();

    //nuevo registro
    $("#newreg").on("click", function (e) {
        e.preventDefault();

        accion = "n";
        activar_controles();
        $("form")[0].reset();

        $("#codigo_proyecto").focus();
        
        return false;
    });

    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#codigo_proyecto").val().length <= 0  ){
            $("#codigo_proyecto").focus();
            mostrarMensaje("msj_error","Rellene el codigo del proyecto");
            return false;
        }else if ( $("#descripcion_proyecto").val().length <= 0  ){
            $("#descripcion_proyecto").focus();
            mostrarMensaje("msj_error","Rellene la descripción del proyecto");
            return false;
        }

        $.post(RUTA+"proyectos/exist", {codigo: $("#codigo_proyecto").val(), descripcion: $("#descripcion_proyecto").val()},
            function (data, textStatus, jqXHR) {
                if(data){
                    mostrarMensaje("msj_error","El codigo o el nombre de proyecto, ya fue registrado");
                    return false
                }
            },
            "text"
        );

        $("#formItem").trigger("submit");
    });

    $("#editreg").on("click", function (e) {
        e.preventDefault();
        accion = "u";

        activar_controles();

        $("#codigo_proyecto").focus();

        return false;
    });

    $("#cancelreg").on("click", function (e) {
        e.preventDefault();

        desactivar_controles();

        return false;
    });

    $("#deletereg").on("click", function(e){
        e.preventDefault();

        $("#dialogConfirm").fadeIn();

        return false;
    })

    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + 'proyectos/listAll',function(data,textStatus,jqXHR){
            $("#tableSeek tbody")
            .empty()
            .append(data);
        })

        $("#modalSeek").fadeIn();
        
        return false;
    });

    $("#formItem").on("submit", function (e) {
        e.preventDefault();

        var data = $(this).serialize();

        if(accion == "n") {
            $.post(RUTA + "proyectos/newReg",data,
            function (data, textStatus, jqXHR) {
                if (data) {
                    mostrarMensaje("msj_info","Registro Creado");
                }else {
                    mostrarMensaje("msj_error","No se creo el registro");
                }
            },
            "text");
        }else {
            $.post(RUTA + "proyectos/updReg",data,
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

        $.post(RUTA + "proyectos/listById", {idx:$(this).data("idx")},
            function (data, textStatus, jqXHR) {
                $("#index_proyecto").val(data.ncodpry);
                $("#codigo_proyecto").val(data.ccodpry) ;
                $("#descripcion_proyecto").val(data.cdespry);
                $("#ubicacion_proyecto").val(data.cubica);
                $("#responsable_proyecto").val(data.cresponsa);
                $("#estado").val(data.nflgactivo);
            },
            "json"
        );

        $("#editreg, #deletereg").removeClass("desactivado");
        $("#modalSeek").fadeOut();
        
        return false;
    });

    $("#btnConfirm").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();
        $("#editreg,#deletereg").addClass('desactivado');

        $.post(RUTA+"proyectos/delReg", {idx:$("#index_proyecto").val()},
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