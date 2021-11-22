$(function(){
    var accion = "";

    activar_opcion();

    //nuevo registro
    $("#newreg").on("click", function (e) {
        e.preventDefault();

        accion = "n";
        activar_controles();
        $("form")[0].reset();


        $("#codigo_centro").focus();
        
        return false;
    });

    $("#editreg").on("click", function (e) {
        e.preventDefault();
        accion = "u";

        activar_controles();
        $("#descripcion_area").focus();

        return false;
    });

    //cancelar el registro
    $("#cancelreg").on("click", function (e) {
        e.preventDefault();

        desactivar_controles();

        return false;
    });

    
    //grabar datos 
    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#codigo_centro").val().length <= 0  ){
            $("#codigo_centro").focus();
            mostrarMensaje("msj_error","Rellene el codigo");
            return false;
        }else if ( $("#descripcion_centro").val().length <= 0 ){
            $("#descripcion_centro").focus();
            mostrarMensaje("msj_error","Rellene la descripcion");
            return false;
        }

        $("#formItem").trigger("submit");
    });

    //envio de datos al servidor
    $("#formItem").on("submit", function (e) {
        e.preventDefault();

        var str = $(this).serialize();

        if(accion == "n") {
            $.post(RUTA + "costos/exist", str,
                function (data, textStatus, jqXHR) {
                    if (data){
                        mostrarMensaje("msj_error","Ya se encuentra registrado");
                    }else{
                        $.post(RUTA + "costos/newReg", str,
                        function (data, textStatus, jqXHR) {
                            if (data) {
                                mostrarMensaje("msj_info","Registro Creado");
                            }else {
                                mostrarMensaje("msj_error","No se creo el registro");
                            }
                        },
                        "text");
                    }
                },
                "text"
            );
        }else {
            $.post(RUTA + "costos/updReg",str,
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

    //busqueda de registros
    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA + 'costos/listAll',function(data,textStatus,jqXHR){
            $("#tableSeek tbody")
            .empty()
            .append(data);
        })

        $("#modalSeek").fadeIn();
        
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

        $.post(RUTA + "costos/listById", {idx:$(this).data("idx")},
            function (data, textStatus, jqXHR) {
                $("#index_costos").val(data.ncodcos);
                $("#codigo_centro").val(data.ccodcos);
                $("#descripcion_centro").val(data.cdescos);
                $("#estado").val(data.nflgactivo);
            },
            "json"
        );

        $("#editreg, #deletereg").removeClass("desactivado");
        $("#modalSeek").fadeOut();
        
        return false;
    });

    //borrar un registro
    $("#deletereg").on("click", function(e){
        e.preventDefault();

        $("#dialogConfirm").fadeIn();

        return false;
    })

    $("#btnConfirm").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();
        $("#editreg,#deletereg").addClass('desactivado');

        $.post(RUTA+"costos/delReg", {idx:$("#index_costos").val()},
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

    //listar Proyectos
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

        $(this).parent().parent().parent().slideUp();
        $("#saveItem span").addClass('parpadea');

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