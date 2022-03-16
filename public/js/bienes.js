$(function(){
    var accion = "";

    activar_opcion();

    //agregar un registro
    $("#newreg").on("click", function (e) {
        e.preventDefault();

        $(this).addClass('desactivado');
        $("#searchreg, #exportexcel, #home").addClass('desactivado');
        $(".floatingButton, .formbody, #savereg, #cancelreg").removeClass('desactivado');
        $("#img_preview").attr("src",RUTA+"public/img/noimagen.jpg");
        $(".formulario table tbody").empty();
        
        $("#compra, #prodccion, #venta, #consumo, #activo_fijo, #gasto, #no_afect, #percepcion, #detraccion").attr('checked', false);

        $("#descrip").text("Nombre");

        $("form")[0].reset();
        $("form")[1].reset();

        $("#descripcion").focus();

        accion = "n";

        return false;
    });

    $("#editreg").on("click", function (e) {
        e.preventDefault();

        $(this).addClass('desactivado');
        $("#newreg, #searchreg, #exportexcel, #home").addClass('desactivado');
        $(".floatingButton, .formbody, #savereg, #cancelreg").removeClass('desactivado');
        
        $("#descripcion").focus();

        accion = "u";

        return false;
    });

    //cancelar el grabado
    $("#cancelreg").on("click", function (e) {
        e.preventDefault();

        $(this).addClass('desactivado');
        $(".floatingButton, .formbody, #savereg, #cancelreg").addClass('desactivado');
        $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
        $(".seleccion").fadeOut();
        $("#img_preview").attr("src",RUTA+"public/img/noimagen.jpg");    
        $("#compra, #prodccion, #venta, #consumo, #activo_fijo, #gasto, #no_afect, #percepcion, #detraccion").attr('checked', false);
        $(".formulario table tbody").empty();

        $("#descrip").text("Nombre");

        $("form")[0].reset();
        $("form")[1].reset();

        return false;
    });

    //grabar registro
    $("#savereg").on("click", function (e) {
        e.preventDefault();

        if ( $("#descripcion").val().length <= 0 ) {
            mostrarMensaje("msj_error","Rellene la descripcion");
            $("#descripcion").focus();
            return false;
        }else if( $("#nombre_comercial").val().length <= 0 ){
            mostrarMensaje("msj_error","Rellene el nombre comercial");
            $("#nombre_comercial").focus();
            return false;
        }else if ( $("#cod_unidad").val().length <= 0 ){
            mostrarMensaje("msj_error","Seleccione una unidad de medida");
            $("#unidad_medida").focus();
            return false;
        }else if( $("#grupo").val().length <= 0 ){
            mostrarMensaje("msj_error","Seleccione un grupo/clase/familia");
            return false;
        }

        $("#dataItem").trigger("submit");

        return false;
    });

    //buscar Items
    $("#searchreg").on("click", function (e) {
        e.preventDefault();

        $("#waitmodal").fadeIn();

        $.post(RUTA + 'bienes/getAllItems',function(data,textStatus,jqXHR){
            $("#tableSeekItems tbody")
            .empty()
            .append(data);

            $("#waitmodal").fadeOut();
        })

        $("#modalSeekItems").fadeIn();
        
        return false;
    });

    //exportar a excel
    $("#exportexcel").on("click", function (e) {
        e.preventDefault();
            //$("#modalWait").fadeIn();
        return false;        
    });
    
    //elimianr el registro
    $("#deletereg").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeIn();

        return false;
    });
    
    //añadir la lista de documentos
    $("#addDoc").on("click", function (e) {
        e.preventDefault();

        if( $("#codigo").val() != "" ){
            $("#dialogWindow").fadeIn();
        }else{
            mostrarMensaje("msj_error","No se genero o selecciono un producto");
        }

        return false;
    });

    //añadir registro de documentos
    $("#addDocument").on("submit", function (e) {
        e.preventDefault()

        $.ajax({
            // URL to move the uploaded image file to server
            url: RUTA + 'bienes/getDataFile',
            // Request type
            type: "POST", 
            // To send the full form data
            data: new FormData( this ),
            contentType:false,      
            processData:false,     
            // UI response after the file upload  
            success: function(data)
            {
                console.log(data);
            }
        });
        
        return false;
    });

    $("#btnAceptar").on("click", function (e) {
        e.preventDefault();

        if ( $("#docname").val() != "" && $("#docfilename").val() !="") {
            
            var aux = $("#docfilename").val().split('.');
            var extension = aux[aux.length -1].toLowerCase(); //obtener la extesion del archivo
            var fileId = generateUUID();
            var fichero = fileId.toString()+"."+extension;
            var row = '<tr><td class="pl20" id="descripcion">'+$("#docname").val()+'</td>'+
                            '<td class="pl20" id="nombre">'+$("#docfilename").val()+'</td>'+
                            '<td class="center" id="archivo"><a href="'+fichero+'" id="enlace"><i class="far fa-eye"></i></a></td></tr>';
            
            $("#codfile").val(fichero);
            $("#documentRegister tbody").append(row);
            $("#addDocument").trigger("submit");
            $("#dialogWindow").fadeOut();
            $("form")[0].reset();
            
        }else{
            mostrarMensaje("msj_error","Debe completar todos los datos");
        }

        return false;
    });
    
    $("#btnCancelar").on("click", function (e) {
        e.preventDefault();

        $("#dialogWindow").fadeOut();

        $("form")[0].reset();

        return false;
    });

    //llanar el cuadro de dialogo de documentos
    $("#docfilename").on("click", function (e) {
        e.preventDefault();

        $("#doc_file").trigger("click");
        
        return false;
    });

    $("#doc_file").on("change", function (e) {
        e.preventDefault();

        $("#docfilename").val(e.target.files[0].name);
        $("#docname").val( e.target.files[0].name.split(".",1));

        return false;
    });

    //modal codigo de grupos/clase/familia
    $("#seekGroups").on('click', function (e) {
        e.preventDefault();
        
        $("#modalWait").fadeIn()

        $.post(RUTA + 'bienes/listGroups',
            function (data, textStatus, jqXHR) {
                $("#modalWait").fadeOut();
                
                $(".tableSeekCodes tbody")
                    .empty()
                    .append(data);
                },
            "text"
        );

        $("#modalSeekCodes").fadeIn();

        return false;
    });

    //busqueda en la grupo/clase/familia
    $("#inputSearchCodes").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".tableSeekCodes tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    //dar click pasar los registros de la tabla grupo/clase/familia
    $(".tableSeekCodes").on("click",'a', function (e) {
        e.preventDefault();

        $("#grupo").val( $(this).data("codgrupo") ) ;
        $("#nombre_grupo").text($(this).data("nomgrupo"));
        $("#clase").val($(this).data("codclase"));
        $("#nombre_clase").text($(this).data("nomclase"));
        $("#familia").val($(this).data("codfamil"));
        $("#nombre_familia").text($(this).data("nomfamil"));

        var codigo_principal = $("#grupo").val()+ $("#clase").val() + $("#familia").val();

        $.post("bienes/genNewCode", {codigo:codigo_principal},
            function (data, textStatus, jqXHR) {
                $("#codigo").val(codigo_principal+data);
                $("#ccodprod").val(codigo_principal+data)
            },
            "text"
        );

        $(".modal").fadeOut();

        return false;
    });
    
    //dar click para pasar los datos de los items
    $("#tableSeekItems").on("click",'a', function (e) {
        e.preventDefault();

        $.post(RUTA + "bienes/searchById", {codigo:$(this).attr("href")},
            function (data, textStatus, jqXHR) {
                //pestaña 1

                $("#cod_unidad").val(data.id_cprod);
                $("#codigo").val(data.id_cprod);
                $("#grupo").val(data.id_cprod.substr(1,2));
                $("#clase").val(data.id_cprod.substr(3,2));
                $("#familia").val(data.id_cprod.substr(5,4));
                $("#nombre_grupo").text(data.grupo);
                $("#nombre_clase").text(data.clase);
                $("#nombre_familia").text(data.familia);

                $("#descripcion").val(data.cdesprod);
                $("#nombre_comercial").val(data.cdescomer);
                $("#nombre_corto").val(data.cdesabrev);
                $("#unidad_medida").val(data.cdesmed);
                $("#cod_unidad").val(data.ncodmed);

                //pestaña 2
                $("#detalles").val(data.mdetalle);
                $("#marca").val(data.cmarca);
                $("#modelo").val(data.cmodelo);
                $("#medida").val(data.cmedida);
                $("#color").val(data.ccolor);
                $("#peso").val(data.npeso);
                $("#volumen").val(data.nvolumen);
                $("#nro_parte").val(data.cnroparte);
                $("#cod_anexo").val(data.ccodanexo);

                var nombre = data.cdesprod;

                $(".banner p").text("Nombre: " + nombre.toUpperCase());

                //pestaña 3
                
                switch (data.ntipoorig) {
                    case 1:
                        $("#compra").attr('checked', 'checked');
                        break;
                    case 2:
                        $("#prodccion").attr('checked', 'checked');
                        break;
                }

                switch (data.ntipodest) {
                    case 1:
                        $("#venta").attr('checked', 'checked');
                        break;
                    case 2:
                        $("#consumo").attr('checked', 'checked');
                        break;
                    case 3:
                        $("#activo_fijo").attr('checked', 'checked');
                        break;
                    case 4:
                        $("#gasto").attr('checked', 'checked');
                        break;
                }

                switch (data.nregesp) {
                    case 1:
                        $("#no_afecto").attr('checked', 'checked');
                        break;
                    case 2:
                        $("#percepcion").attr('checked', 'checked');
                        break;
                    case 3:
                        $("#detraccion").attr('checked', 'checked');
                        break;
                }

                data.nunidsec   == 1 ? $("#unidades_secundarias").prop('checked', true) : $("#unidades_secundarias").prop('checked', false);
                data.nserieprod == 1 ? $("#series_unicas").prop('checked', true) : $("#series_unicas").prop('checked', false);
                data.ndigemid   == 1 ? $("#controlado_digemid").prop('checked', true) : $("#controlado_digemid").prop('checked', false);
                data.ngasrela   == 1 ? $("#producto_relacionado").prop('checked', true) : $("#producto_relacionado").prop('checked', false);
                data.nicbper    == 1 ? $("#afecto_icbper").prop('checked', true) : $("#afecto_icbper").prop('checked', false);

                data.cfoto !=  null ? $("#img_preview").attr("src",RUTA+"public/productos/"+data.cfoto) : $("#img_preview").attr("src",RUTA+"public/img/noimagen.jpg");

                $("#tipo_producto").val(data.ntipoprod);
                $("#ruta_foto").val(data.cfoto);
                $("#old_foto").val(data.cfoto);
                $("#estado").val(data.nflgactivo);
                $("#index_producto").val(data.id_cprod);

                $("#editreg,#deletereg").removeClass('desactivado');

            },
            "json"
        );

        $(".modal").fadeOut();

        return false;
    });

    //filtrar los items
    $("#inputSearchItems").on("keyup", function(e) {
        if(e.which == 13 && $(this).val().length > 2) {
            $("#waitmodal").fadeIn();
            $.post(RUTA+"bienes/ItemsByWord", {palabra:$(this).val(),tipo:1},
                function (data, textStatus, jqXHR) {
                    $("#tableSeekItems tbody")
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

        $.post(RUTA+"bienes/ItemsByLetter", {tipo:1,letra:$(this).attr("href")},
                function (data, textStatus, jqXHR) {
                    $("#tableSeekItems tbody")
                        .empty()
                        .append(data);
                    
                    $("#waitmodal").fadeOut();
                },
                "text"
        )

        return false;
    })

    //cerrar el modal de busqueda
    $(".buttonClose").on("click", function (e) {
        e.preventDefault()

        $(".modal").fadeOut();
        //codigo preliminar
 
        return false;
    });

    //subir photos
    $("#uploadPhoto").on("click", function (e) {
        e.preventDefault();

        $("#image_file").trigger('click');

        return false;
    });

    $("#image_file").on("change", function (event) {
		if(-1!=$.inArray($("#image_file")[0].files[0].type, ["image/jpeg","image/jpg","image/png"])){	
            var populateImg = new FileReader();
            populateImg.onload = previewImg;
			populateImg.readAsDataURL($("#image_file")[0].files[0]);
			$("#ruta_foto").val($("#image_file")[0].files[0].name);
        }else {
			mostrarMensaje("msj_error","Formato de imagen no soportado");
		}
    });
    
    //activar el cuadro de unidad de medida
    $("#unidad_medida").focus(function (e) { 
        e.preventDefault();
        
        $("#cod_unidad").val("");
        $(this).select();
        $(".seleccion").fadeOut();

        $(this).next(".seleccion").slideDown();

        return false;
    });

    $(".seleccion").on("click","a", function (e) {
        e.preventDefault();

        $("#cod_unidad").val($(this).attr("href"));
        $("#unidad_medida").val($(this).text());

        $(this).parent().parent().parent().slideUp();

        return false;
    });

    //termina unidad de medida

    //enviar datos y foto del producto
    $("#dataItem").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            // URL to move the uploaded image file to server
            url: RUTA + 'bienes/uploadImg',
            // Request type
            type: "POST", 
            // To send the full form data
            data: new FormData( this ),
            contentType:false,      
            processData:false,     
            // UI response after the file upload  
            success: function(data)
            {
                data.length > 0 ? $("#ruta_foto").val(data) : $("#ruta_foto").val( $("#old_foto").val() );

                var data = $("#dataItem").serialize();
               
                if (accion == 'n' ) {
                    $.post(RUTA+"bienes/newItem", data,
                        function (data, textStatus, jqXHR) {
                            if (data){
                                //grabar los datos de documentos
                                registerDosc(data);
                                mostrarMensaje("msj_correcto","Item registrado");
                            }
                            else
                                mostrarMensaje("msj_error","Item no registrado");
                            
                            desactivarCampos();
                        },
                        "text"
                    );
                }
                else {
                    var data = $("#dataItem").serialize();

                    $.post(RUTA+"bienes/updateItem", data,
                        function (data, textStatus, jqXHR) {
                            if (data){
                                //grabar los datos de documentos
                                registerDosc(data);
                                mostrarMensaje("msj_correcto","Item actualizado");
                            }
                            else
                                mostrarMensaje("msj_error","Item.. Error en la actualización");
                            
                            desactivarCampos()
                        },
                        "text"
                    );
                }    
            }
        });

        return false;
    });

    $("#sendPhoto").on("click", function (e) {
        e.preventDefault();

        $("#dataItem").trigger("submit");

        return false;
    });
    ///fin de envio de datos

    //seleccion de el menu horizontal de productos
    $(".menu_horizontal a").on("click", function (e) {
        e.preventDefault();

        $("li").removeClass("selected");
        $(this).parent().addClass("selected");

        var activarForm = $(this).data("index");
        
        $(".datos").hide();

        switch (activarForm) {
            case 1:
                $("#form1").fadeIn();
                //$(".banner p").text("Nombre: ");
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
            case 5:
                $("#form5").fadeIn();
                if ( $("#index_producto").val().length > 0 ) {
                    $.post(RUTA + "bienes/listDocs", { data:$("#index_producto").val() },
                        function (data, textStatus, jqXHR) {
                            $("#documentRegister tbody")
                                .empty()
                                .append(data);
                        },
                        "text"
                    );
                }
                break;
            case 6:
                $("#form6").fadeIn();
                break;
            case 7:
                $("#form7").fadeIn();
                break;
            case 8:
                $("#form8").fadeIn();
                if ( $("#index_producto").val().length > 0 ) {
                    $.post(RUTA + "bienes/listHistory", { data:$("#index_producto").val() },
                        function (data, textStatus, jqXHR) {
                            $("#tableHistory tbody")
                                .empty()
                                .append(data);
                        },
                        "text"
                    );
                }
                break    
        }

        return false;
    });

    //confirmar borrado de items
    $("#btnConfirm").on("click", function (e) {
        e.preventDefault();

        $("#dialogConfirm").fadeOut();
        $("#editreg,#deletereg").addClass('desactivado');

        $.post(RUTA+"bienes/deleteItem", {codigo:$("#index_producto").val()},
            function (data, textStatus, jqXHR) {
                mostrarMensaje("msj_correcto","registro desactivado");

                $(".formulario table tbody").empty();
        
                $("#compra, #prodccion, #venta, #consumo, #activo_fijo, #gasto, #no_afect, #percepcion, #detraccion").attr('checked', false);

                $("form")[0].reset();
                $("form")[1].reset();
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

//funcion para registrar los documentos
function registerDosc(codigo){
    if ( $("#documentRegister tbody tr").length > 0) {
            
        console.log("se va procesar los registros");

        var DATA = [];
        var TABLA = $("#documentRegister tbody > tr");

        TABLA.each(function(){
            var DES = $(this).find("td[id='descripcion']").text(),
                NOM = $(this).find("td[id='nombre']").text(),
                ADJ = $(this).find("a[id='enlace']").attr("href"),
                COD = codigo;

            item = {};
                
            if( DES !== ''){
                item ["des"] = DES;
                item ['nom'] = NOM;
                item ["adj"] = ADJ;
                item ["cod"] = COD;

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
            url : RUTA + 'bienes/history',
            processData: false, 
            contentType: false,
        });
        
    }else{
        console.log("No se registro ningun documento");
    }
}

function desactivarCampos(){
    $("form")[0].reset();
    $("form")[1].reset();
    $(this).addClass('desactivado');
    $(".floatingButton, .formbody, #savereg, #cancelreg").addClass('desactivado');
    $("#newreg, #searchreg, #exportexcel, #home").removeClass('desactivado');
    $(".seleccion").fadeOut();
    $("#img_preview").attr("src",RUTA+"public/img/noimagen.jpg");
    $(".formulario table tbody").empty();
}