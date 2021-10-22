$(function(){
    activar_opcion();
    var  fila = [{
        lugar:"",
        pedido:"",
        entidad: "",
        detalle:""
    }];

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

                $.post(RUTA +"adjudicacion/proformas", {cod:$("#cod_pedido").val()},
                    function (data, textStatus, jqXHR) {
                        $("#detalle_pedido")
                        .empty()
                        .append(data);
                    },
                    "text"
                );
            },
            "json"
        );

    $("#detalle_pedido").on("click",".chkVerificado", function (e) {
        item={};

        let posicion = $(this).parent().parent().data("fila");
        let iddet = $(this).parent().data("detalle");
        let indice = fila.findIndex(criterio => criterio.detalle === iddet);
        let entidad = $(this).parent().data("entidad");

        if( $(this).prop('checked') ) {
            if (indice == "-1"){
                item["lugar"] = posicion;
                item["pedido"] = $(this).parent().data("pedido");
                item['entidad'] = entidad;
                item["detalle"] = $(this).parent().data("detalle");
                item["unitario"] = $(this).parent().data("precio");
            
                fila.push(item);
            }else{
                fila[posicion]["entidad"] = entidad; 
            }       
        }
    });

    return false;
    });

    $("#closeModalProcess").on("click", function (e) {
        e.preventDefault()
       
        $("#modalProcess").fadeOut()

        return false;
    });

    $("#verProforma").on("click", function (e) {
        e.preventDefault();

        $.post(RUTA+"adjudicacion/proformasPdf", {cod:$("#cod_pedido").val()},
            function (data, textStatus, jqXHR) {
                $("#atachList")
                    .empty()
                    .append(data);
                $("#modalPreviewCot").fadeIn();
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


    $(".buttonClose").click(function (e) { 
        e.preventDefault();

        $(".preview object").attr("data","");
        $(this).parent().fadeOut();

        return false;
    });

    $("#detalle_pedido").on("click","a", function (e) {
        e.preventDefault();
        $(".insidePreview iframe").attr("src",$(this).attr("href"));
        $("#modalPreviewMan").fadeIn();

        return false;
    });

    $("#closeItem").click(function (e) { 
        e.preventDefault();

        let items = JSON.stringify(fila);
        
        $("*").css("cursor", "wait");

        $.post(RUTA+"adjudicacion/pasaverificar", {detalles:items},
            function (data, textStatus, jqXHR) {
                if (data){
                    $("*").css("cursor", "pointer");
                }
            },
            "text"
        );

        return false;
    });
})


