var intro;
function  salir() {
    intro.finish();
}
function stepEstablecimiento(){
    $("#starTourPaso" ).click(function(){
        intro = $.hemiIntro({
             steps: [
                {
                 selector: "#paso1",
                 placement: "top",
                 content: "Campo no  obligatorio. (A cargo de o bajo la responsabilidad de la persona o entidad que se desea detallar) ",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso2",
                 placement: "top",
                 content: "Buscar y seleccionar el tipo de establecimiento",
                 offsetTop: 300,
               },
               {
                 selector: "#paso3",
                 placement: "top",
                 content: "Campo obligatorio, ingresar el código del establecimiento completo. (Ejemplo EF00001)",
                 offsetTop: 300,
               },
                {
                 selector: "#paso4",
                 placement: "top",
                 content: "Los siguientes iconos detallaran el estado actual del establecimiento.",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso5",
                 placement: "top",
                 content: "Luego de ingresar los campos, presionar el botón \"Consultar\". Se mostrara una tabla con la información del establecimiento.",
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
function stepRegistro(){
    $("#starTourPaso" ).click(function(){
        intro = $.hemiIntro({
             steps: [
                {
                 selector: "#paso1",
                 placement: "top",
                 content: "Campo no  obligatorio. (A cargo de o bajo la responsabilidad de la persona o entidad que se desea detallar) ",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso2",
                 placement: "top",
                 content: "Campo obligatorio, seleccionar el propietario del producto.",
                 offsetTop: 300,
               },
               {
                 selector: "#paso3",
                 placement: "top",
                 content: "Seleccionar el origen del producto.",
                 offsetTop: 300,
               },
                {
                 selector: "#paso4",
                 placement: "top",
                 content: "Seleccionar la modalidad de venta del producto",
                 offsetTop: 300,
               },
               {
                selector: "#paso5",
                placement: "top",
                content: "Los siguientes iconos detallaran el estado actual de los productos.",
                offsetTop: 300,
              },
              
                 {
                 selector: "#paso6",
                 placement: "top",
                 content: "Luego de ingresar los campos, presionar el botón \"Consultar\". Se mostrara una tabla con la información correspondiente del propietario seleccionado.",
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
function stepCosmetico(){
    $("#starTourPaso" ).click(function(){
        intro = $.hemiIntro({
             steps: [
                {
                 selector: "#paso1",
                 placement: "top",
                 content: "Campo no  obligatorio. (A cargo de o bajo la responsabilidad de la persona o entidad que se desea detallar) ",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso2",
                 placement: "top",
                 content: "Campo obligatorio, seleccionar el propietario del producto.",
                 offsetTop: 300,
               },
               {
                 selector: "#paso3",
                 placement: "top",
                 content: "Seleccionar el tipo del producto.",
                 offsetTop: 300,
               },
                {
                 selector: "#paso4",
                 placement: "top",
                 content: "Seleccionar el origen del producto",
                 offsetTop: 300,
               },
               {
                selector: "#paso5",
                placement: "top",
                content: "Los siguientes campos se llenan automáticamente luego de seleccionar los productos.",
                offsetTop: 300,
              },
              {
                selector: "#paso6",
                placement: "top",
                content: "Los siguientes iconos detallaran el estado actual del los productos",
                offsetTop: 300,
              },
                 {
                 selector: "#paso7",
                 placement: "top",
                 content: "Luego de ingresar los campos, presionar el botón \"Consultar\". Se mostrara una tabla con la información correspondiente del propietario seleccionado.",
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
function stepInsumos(){
    $("#starTourPaso" ).click(function(){
        intro = $.hemiIntro({
             steps: [
                {
                 selector: "#paso1",
                 placement: "top",
                 content: "Campo no  obligatorio. (A cargo de o bajo la responsabilidad de la persona o entidad que se desea detallar) ",
                 offsetTop: 300,
               },
                 {
                 selector: "#paso2",
                 placement: "top",
                 content: "Campo obligatorio, seleccionar el propietario del producto.",
                 offsetTop: 300,
               },
               {
                selector: "#paso3",
                placement: "top",
                content: "Los siguientes iconos detallaran el estado actual del los productos",
                offsetTop: 300,
              },
               {
                selector: "#paso4",
                placement: "top",
                content: "Los siguientes campos se llenan automáticamente luego de seleccionar los productos.",
                offsetTop: 300,
              },
                 {
                 selector: "#paso5",
                 placement: "top",
                 content: "Luego de ingresar los campos, presionar el botón \"Consultar\". Se mostrara una tabla con la información correspondiente del propietario seleccionado.",
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




