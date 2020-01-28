$('#filtro').on('change', function(){
        //console.log($(this).val());
        var idopcion = $(this).val();
        if(idopcion==1){
        	document.getElementById("contramaquila").readOnly=false;

       		document.getElementById("nomproducto").readOnly=false;
       		document.getElementById("nomproducto").setAttribute("required", "");
       		document.getElementById("tipoproducto").readOnly=false;
       	}
       	else if(idopcion==2)
       	{
       		document.getElementById("contramaquila").readOnly=true;

       		document.getElementById("nomproducto").readOnly=false;
       		document.getElementById("nomproducto").removeAttribute("required", "");
       		document.getElementById("tipoproducto").readOnly=true;

       	}
        

    });