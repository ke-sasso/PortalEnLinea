var intro;
function  salir() {
    intro.finish();
}
function pagosvarios(){
    $("#starTourPaso" ).click(function(){
        intro = $.hemiIntro({
             steps: [
               {
                 selector: "#paso1",
                 placement: "top",
                 content: "El mandamiento de pago se genera con el siguiente nombre. (Nota: el campo se llena automáticamente)",
                 offsetTop: 300,
               },
                  {
                 selector: "#paso2",
                 placement: "top",
                 content: "Campo no  obligatorio. (A cargo de o bajo la responsabilidad de la persona o entidad que se desea detallar) ",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso3",
                 placement: "top",
                 content: "Este campo se llenará automáticamente luego de seleccionar el mandamiento de pago.",
                 offsetTop: 300,
               },
               {
                 selector: "#paso4",
                 placement: "top",
                 content: "Campo no obligatorio, comentario que se desea adicionar al mandamiento de pago.",
                 offsetTop: 300,
               },
                {
                 selector: "#paso5",
                 placement: "top",
                 content: "Buscar y seleccionar el arancel correspondiente a pagar. (Nota: verificar el monto y la descripción)",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso6",
                 placement: "top",
                 content: "Luego de haber seleccionar el arancel, se habilitara el botón. (Nota: verficar todos los datos antes de generar la boleta)",
                 offsetTop: 300,
               }
             ],buttons: {
                       holder: {
                        element: $("<div align=\"right\" ><a class=\"btn btn-warning\" id=\"salirButton\" onclick=\"salir();\">Finalizar<span class=\"glyphicon glyphicon-remove\"></span></a>"),
                         class: "hemi-intro-buttons-holder"
                       },
                       next: {
                         element: $("<a><span class=\"glyphicon glyphicon-chevron-right\" id=\"sigButton\"></span>Siguiente</a>"),
                         class: "btn btn-primary"
                       },
                       finish: {
                         element: $(""),
                         class: "btn btn-primary"
                       }
                     }
           });
           intro.start();
          
 });

}

function pagosgenerales(){
    $("#starTourPaso" ).click(function(){
        intro = $.hemiIntro({
             steps: [
               {
                 selector: "#paso1",
                 placement: "top",
                 content: "El mandamiento de pago se genera con el siguiente nombre. (Nota: el campo se llena automáticamente)",
                 offsetTop: 300,
               },
                  {
                 selector: "#paso2",
                 placement: "top",
                 content: "Campo no  obligatorio. (A cargo de o bajo la responsabilidad de la persona o entidad que se desea detallar) ",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso3",
                 placement: "top",
                 content: "Este campo se llenará automáticamente luego de seleccionar el mandamiento de pago.",
                 offsetTop: 300,
               },
               {
                 selector: "#paso4",
                 placement: "top",
                 content: "Campo no obligatorio, comentario que se desea adicionar al mandamiento de pago.",
                 offsetTop: 300,
               },
                {
                 selector: "#paso5",
                 placement: "top",
                 content: "Seleccionar el arancel correspondiente a pagar. (Nota: verificar el monto y la descripción)",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso6",
                 placement: "top",
                 content: "Luego de haber seleccionar el arancel, se habilitara el botón. (Nota: verficar todos los datos antes de generar la boleta)",
                 offsetTop: 300,
               }
             ],buttons: {
                      holder: {
                        element: $("<div align=\"right\" ><a class=\"btn btn-warning\" id=\"salirButton\" onclick=\"salir();\">Finalizar<span class=\"glyphicon glyphicon-remove\"></span></a>"),
                        class: "hemi-intro-buttons-holder"
                      },
                      next: {
                        element: $("<a><span class=\"glyphicon glyphicon-chevron-right\" id=\"sigButton\"></span>Siguiente</a>"),
                        class: "btn btn-primary"
                      },
                       finish: {
                         element: $(""),
                         class: "btn btn-primary"
                       }
                     }
           });
           intro.start();
           
 });

}