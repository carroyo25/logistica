var HOST = $(location).attr('hostname');

if (HOST ==  "localhost") {
    var RUTA = "http://localhost/logistica/";
}else {
    var RUTA = "http://200.41.86.61:3000/logistica/";
}

var emailreg = /^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/;
var numeros = /^[0-9]*\.?[0-9]*$/;
var fecha = /^([0-9]{4}\-[0-9]{2}\-[0-9]{2})$/;
var ruc = /^[0-9]{11}$/;

$.strPad = function(i,l,s) {
    var o = i.toString();
    if (!s) { s = '0'; }
    while (o.length < l) {
        o = s + o;
    }
    return o;
};

function leftPad(value, length) { 
    return ('0'.repeat(length) + value).slice(-length); 
}

function mostrarMensaje(clase,mensaje) {
	$(".mensaje span")
		.empty()
		.text(mensaje);

	$(".mensaje")
		.removeClass('msj_error msj_correcto msj_info ')
		.addClass(clase)
		.css('right', '0');

	setTimeout(function() { 
		$(".mensaje")
		.css('right',"-100%");
	}, 2500);	
}

function previewImg(event) {
    $('.img').css("display", "block");
    $('#img_preview').attr('src', event.target.result)
    	.attr('width', '300px')
    	.attr('height', '250px');
};

function IsNullOrEmpty(str) {
    var returnValue = false;
    if (!str
        || str == null
        || str === 'null'
        || str === ''
        || str === '{}'
        || str === 'undefined'
        || str.length === 0) {
        if (str.trim().length > 0) {
            returnValue = true;
        } else {
            returnValue = false;
        }
    }
    return returnValue;
}


function generateUUID() {
    var d = new Date().getTime();
    var uuid = 'xxxxxxxxxxxx4xxxyxxxxxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
}

function activar_controles(){
    $(this).addClass('desactivado');
    $("#searchreg, #exportexcel, #home, #newreg").addClass('desactivado');
    $(".floatingButton, .formbody, #savereg, #cancelreg").removeClass('desactivado');
    $("#editreg, #deletereg").addClass("desactivado");
    $(".seleccion").fadeOut();
    $(".formulario table tbody").empty();
    $(".calificacion div").removeClass("calificado");
}

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


function checkExistTable(table,item,indice){
    var itenExist = false;

    table.each(function(){
        var itemTable = $(this).find('td').eq(indice).text();

        if (itemTable == item) {
            itenExist = true;
            return false;
        }
    })

    return itenExist;
}

function fillTables(table,idx){
    var item = 0;

    table.each(function(){
        item++;

        $(this).find('td').eq(idx).text($.strPad(item,2));
    });
}

function fill3Tables(table,idx){
    var item = 0;

    table.each(function(){
        item++;

        $(this).find('td').eq(idx).text($.strPad(item,3));
    });
}

function activar_opcion() {
    //obtiene el numero de la opcion
    var hash = window.location.hash.substr(1,1);
    var hasb = window.location.hash.substr(2,1);

    $(".link:eq("+hash+")").addClass('open opcion_seleccionada');
    $(".submenu:eq("+hash+")").css("display",'block');
    $(".submenu:eq("+hash+") li:eq("+hasb+")").addClass("opcion_seleccionada");

}

function fechaActual() {
    var d = new Date(); 
    var month = d.getMonth()+1; 
    var day = d.getDate(); 
    var output = d.getFullYear() + '-' + (month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;

    return output;
}


function abrirVentanaEspera(){
    $("#waitmodalCursorOffline").fadeIn();
    $("*").css("cursor", "wait");
}

function cerrarVentanaEspera(){
    $("#waitmodalCursorOffline").fadeOut();
    $("*").css("cursor", "pointer");
}