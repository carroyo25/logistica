$(function(){
    abrirVentanaEspera();

    $(document).ready(function(){
        activar_opcion();

        cerrarVentanaEspera();
    });

    $("#tabla_ingresos tbody").on("click","a", function (e) {
        e.preventDefault();

        abrirVentanaEspera();

        $.post(RUTA+"cargoplan/detalleId", {id:$(this).attr("href")},
            function (data, textStatus, jqXHR) {

                let estado = "centro "+data[0]['estado'].toLowerCase();
                
                $("#codigo").val(data[0]['ccodprod']);
                $("#producto").val(data[0]['cdesprod']);
                $("#cantsolicitada").val(data[0]['pedida']);
                $("#cantaprobada").val(data[0]['aprobada']);
                $("#estado").val(data[0]['estado']);
                $("#nropedido").val(leftPad(data[0]['pedido'],6));
                $("#tipo_pedido").val(data[0]['ctipmov']);
                $("#emision_pedido").val(data[0]['fechapedido']);
                $("#aprobacion_pedido").val(data[0]['ffechaprueba']);
                $("#aprobado_por").val(data[0]['aprueba']);


                $("#nroorden").val(data[0]['orden']);
                $("#tipo_orden").val(data[0]['ctipmov']);
                $("#emision_orden").val(data[0]['fechaorden']);
                $("#aprobacion_logistica").val(data[0]['']);
                $("#aprobacion_finanzas").val(data[0]['']);
                $("#aprobacion_operaciones").val(data[0]['']);
                $("#ingreso").val(leftPad(data[0]['ingreso'],6));
                $("#fecha_ingreso").val(data[0]['fechaingreso']);
                $("#salida").val(leftPad(data[0]['salida'],6));
                $("#fecha_salida").val(data[0]['fechasalida']);
                $("#proveedor").val(data[0]['crazonsoc']);
                $("#ruc").val(data[0]['cnumdoc']);
                $("#observaciones").val(data[0]['mdetalle']);

                $("#estado").removeClass();
                $("#estado").addClass(estado);

                $("#pdfpedido").attr("href",data[0]['idpedido']);
                $("#pdforden").attr("href",data[0]['idorden']);
                $("#pdfingreso").attr("href",data[0]['idingreso']);
                $("#pdfsalida").attr("href",data[0]['idsalida']);

                $("#modalProcess").fadeIn();

            },
            "json"
        );
        
        return false;
    });

    $(".callpreview").click(function (e) { 
        e.preventDefault();

        $("#modalPreview").fadeIn();

        let doc  = $(this).attr("id");
        let prev = $(this).attr("href");
        let result = "";

        switch (doc) {
            case "pdfpedido":
                result = 'public/pedidos/aprobados/'+prev+".pdf";
                break;
            case "pdforden":
                result = 'public/ordenes/aprobadas/OC'+prev+".pdf";
                break;
            case "pdfingreso":
                result = 'public/ingresos/'+prev+".pdf";
                break;
            case "pdfsalida":
                result = 'public/salidas/'+prev+".pdf";
                break;
        }

        $(".insidePreview iframe").attr("src",result);

        return false;
    });

    $("#closeModalPreview").click(function (e) { 
        e.preventDefault();

        $("#modalPreview").fadeOut();

        return false;
    });

    $("#btnCerrarDetalle").click(function (e) { 
        e.preventDefault();
        
        $("#modalProcess").fadeOut();
        cerrarVentanaEspera();
        
        return false;
    });
})