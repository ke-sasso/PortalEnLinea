$(document).ready(function(){
	$.get("http://ip-api.com/json/",function (response) {
		if(response.status == 'success')
		{
			console.log(response.query + ' - ' + response.regionName + ', ' + response.country);
			$('#ipRequest').text(response.query + ' - ' + response.regionName + ', ' + response.country);
			$('#ipRemote').val(response.query);
		}
		else{
			$.get("https://ipinfo.io", function(response) {
				console.log(response.ip, response.country);
				$('#ipRequest').text(response.ip + ' - ' + response.city + ', '+response.region);
				$('#ipRemote').val(response.ip);
			}, "jsonp");
		}
	});
	if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ) 
	{
		$("#navegador").append("<input type='hidden' name='navegador' value='Opera'/>");
	}
	else if(navigator.userAgent.indexOf("MSIE") != -1) //IF IE > 10
	{
		$("#navegador").append("<input type='hidden' name='navegador' value='IE'/>");
	}
	else if(navigator.userAgent.indexOf('Edge') >= 0) //IF IE > 10
	{
		$("#navegador").append("<input type='hidden' name='navegador' value='Edge'/>"); 
	}
	else if(navigator.userAgent.indexOf("Chrome") != -1 )
	{
		$("#navegador").append("<input type='hidden' name='navegador' value='Chrome'/>");
	}
	else if(navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)
	{
		$("#navegador").append("<input type='hidden' name='navegador' value='Safari'/>");
	}
	else if(navigator.userAgent.indexOf("Firefox") > -1 ) 
	{
		$("#navegador").append("<input type='hidden' name='navegador' value='Firefox'/>");
	}  
	else 
	{
		$("#navegador").append("<input type='hidden' name='navegador' value='Desconocido'/>");
	}
});

//Metodo para validar nit de el salvador
function validarNIT(cadena){
    return true;
    let calculo = 0;
    let digitos = parseInt(cadena.substring(12, 15));
    let resultado;
    if ( digitos <= 100 ) {
        for ( let posicion = 0; posicion <= 14; posicion++ ) {
            if ( !( posicion == 4 || posicion == 11 ) ){
                calculo += ( 14 *  parseInt( cadena.charAt( posicion ) ) );
                //calculo += ((15 - posicion) * parseInt(cadena.charAt(posicion)));
            }
            calculo = calculo % 11;
        }
    } else {
        let n = 1;
        for ( let posicion = 0; posicion <= 14; posicion++ ){
            if ( !( posicion == 4 || posicion == 11 ) ){
                calculo = parseInt( calculo + ( ( parseInt( cadena.charAt( posicion ) ) ) * ( ( 3 + 6 * Math.floor( Math.abs( ( n + 4) / 6 ) ) ) - n ) ) );
                n++;
            }
        }
        calculo = calculo % 11;
        if ( calculo > 1 ){
            calculo = 11 - calculo;
        } else {
            calculo = 0;
        }
    }
    resultado = (calculo == ( parseInt( cadena.charAt( 16 ) ) ) ); 
    return resultado;
}

function validarDUI(dui)
{

	let digitoVerificador = parseInt(dui.substring(9,10));
	let suma = 0;
	let cant = 1;
	//console.log(digitoVerificador);
	for (let i = 0; i <= 7; i++)
	{
		let digito = parseInt(dui.substring(i, cant));
		//console.log("cant="+cant+", i="+i+", digito="+digito);
		cant++;
		suma += ((9-i) * digito);
		//console.log((i+2) +"x"+ digito+", suma="+suma);
	}
	//console.log("cant " + cant);
	let residuoVerificador = 10 - (suma % 10);
	if (digitoVerificador == residuoVerificador || residuoVerificador == 0){
		return true;
	}
	else{
		return false;
	}
}
