function validarDocumentos(){

						var innovador  = $('#innovador option:selected').val();
						var tipoMedicamento  = $('#tipoMedicamento option:selected').val();
						var origen = $('#origen option:selected').val();
						/*
						{tipoMed: 1, nomTipo: "SINTESIS QUIMICA "}
						{tipoMed: 2, nomTipo: "BIOLOGICO "}
						{tipoMed: 3, nomTipo: "BIOTECNOLOGICO"}
						{tipoMed: 4, nomTipo: "VACUNAS"}
						{tipoMed: 6, nomTipo: "SUPLEMENTO NUTRICIONALES"}
						{tipoMed: 7, nomTipo: "NATURALES "}
						{tipoMed: 8, nomTipo: "HOMEOPATICO "}
						{tipoMed: 9, nomTipo: "GENERICO"}
						{tipoMed: 12, nomTipo: "GASES MEDICINALES"}
						{tipoMed: 11, nomTipo: "RADIOFARMACOS"}
						{tipoMed: 9, nomTipo: "GENERICO"}
						{tipoMed: 13, nomTipo: "PROBIOTICO"}
                  */
						var files = true;
				   if(origen==4){
				   	    //SI ES RECONOCIMIENTO MUTUO, SOLO EL FORMULARIO ES OBLIGATORIO
				   	  	if(document.getElementById("file1")){
							if($('#file1').get(0).files.length==0){
								  files = false;
							}
						 }
					}else{
								    if(tipoMedicamento==1){
								    	 if(innovador==1){
									    	   	$.each($('input[type=file]'),function(){
								                            if($(this).data('innovador')==1)
								                                if($(this).get(0).files.length==0){
								                                    files = false;
								                                }
								                 });
									    	   	  	$.each($('input[type=file]'),function(){
							                            if($(this).data('sintesis')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                      });
										      console.log("sintesis");


								    	 }

								    }else if(tipoMedicamento==2){
								    	   $.each($('input[type=file]'),function(){
							                            if($(this).data('biologico')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                 });
								    	   console.log("biologico");
								    }else if(tipoMedicamento==3){
			                             	 $.each($('input[type=file]'),function(){
							                            if($(this).data('biotecnologico')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                 });
			                             	 console.log("biotecnologico");
								    }else if(tipoMedicamento==4){
			                             	 $.each($('input[type=file]'),function(){
							                            if($(this).data('vacuna')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                 });
			                             	 console.log("vacuna");
								    }else if(tipoMedicamento==6){
			                             	 $.each($('input[type=file]'),function(){
							                            if($(this).data('suplementos')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                 });
			                             	 console.log("suplementos");
								    }else if(tipoMedicamento==7){
			                             	 $.each($('input[type=file]'),function(){
							                            if($(this).data('naturales')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                 });
			                             	 console.log("naturales");
								    }else if(tipoMedicamento==8){
			                             	 $.each($('input[type=file]'),function(){
							                            if($(this).data('homeopatico')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                 });
			                             	 console.log("homeopatico");
								    }else if(tipoMedicamento==9){

							                  $.each($('input[type=file]'),function(){
							                            if($(this).data('generico')==1)
							                               if($(this).get(0).files.length==0){
							                                    files = false;
							                               }
							                 });
							                  console.log("generico");
								    }else if(tipoMedicamento==11){
			                             	 $.each($('input[type=file]'),function(){
							                            if($(this).data('radiofarmaco')==1)
							                                if($(this).get(0).files.length==0){
							                                    files = false;
							                                }
							                 });
			                             	 console.log("radiofarmaco");
								    }else if(tipoMedicamento==12){

							                  $.each($('input[type=file]'),function(){
							                            if($(this).data('gasesmedicinales')==1)
							                               if($(this).get(0).files.length==0){
							                                    files = false;
							                               }
							                 });
							                  console.log("gasesmedicinales");
								    }else if(tipoMedicamento==13){

							                  $.each($('input[type=file]'),function(){
							                            if($(this).data('probiotico')==1)
							                               if($(this).get(0).files.length==0){
							                                    files = false;
							                               }
							                 });
							                  console.log("probiotico");
								    }
								    if(origen==3){
								    		 $.each($('input[type=file]'),function(){
								    		 	        var na = $(this).attr('name');
							                            if($(this).data('recoextranjero')==1){
							                            	if(tipoMedicamento==4){
							                            		if(na!='file-es[9]'){
							                            			   if($(this).get(0).files.length==0){
							                                            files = false;
							                                          }
							                            		}
							                            	}else{
							                            	     if($(this).get(0).files.length==0){
							                                       files = false;
							                                     }

							                            	}

							                           }//cierre reciextranjero



							                 });
							                 console.log("reconocimientoextranjero");
								    }

								    $.each($('input[type=file]'),function(){
			                            if(tipoMedicamento==6 || tipoMedicamento==7 || tipoMedicamento==8){
			                              	    var na = $(this).attr('name');
			                              	    if(origen==4){
			                              	    	 //agregarmos los documentos  7 y 18 que no son obligatorios para Reconocimiento mutuo Centroamericano
			                              	    	 if(na!='file-es[10]' && na!='file-es[21]'  && na!='file-es[7]' && na!='file-es[18]'){
						                              		if($(this).data('obligatorio')==1)
						                                    if($(this).get(0).files.length==0){
						                                       files = false;
						                                    }
				                              			//console.log(na+" na");
				                              		}
			                              	    }else{
			                              	        if(na!='file-es[10]' && na!='file-es[21]'){
						                              		if($(this).data('obligatorio')==1)
						                                    if($(this).get(0).files.length==0){
						                                       files = false;
						                                    }
				                              			//console.log(na+" na");
				                              		}

			                              	    }
			                             }else if(tipoMedicamento==12){
			                              	    var na = $(this).attr('name');
			                              	    if(origen==4){
			                              	    	   //agregarmos los documentos  7 y 18 que no son obligatorios para Reconocimiento mutuo Centroamericano
			                              	    	  if(na!='file-es[10]'  && na!='file-es[7]' && na!='file-es[18]'){
						                              		if($(this).data('obligatorio')==1)
						                                    if($(this).get(0).files.length==0){
						                                       files = false;
						                                    }
			                              			//console.log(na+" na");
			                              		     }
			                              	    }else{
			                              	    	 if(na!='file-es[10]'){
					                              		if($(this).data('obligatorio')==1)
					                                    if($(this).get(0).files.length==0){
					                                       files = false;
					                                    }
			                              			//console.log(na+" na");
			                              		     }
			                              	    }


			                             }else if(tipoMedicamento==4){
			                             	    var na = $(this).attr('name');
			                             	      //agregarmos los documentos 5 y 18  que no son obligatorios para Reconocimiento mutuo Centroamericano
			                             	    if(origen==4){
			                             	        if(na!='file-es[10]' && na!='file-es[21]' && na!='file-es[7]' && na!='file-es[8]'  && na!='file-es[18]'){
					                              		if($(this).data('obligatorio')==1)
					                                    if($(this).get(0).files.length==0){
					                                       files = false;
					                                    }
			                              			//console.log(na+" na");
			                              		    }
			                             	    }else{
			                             	    	if(na!='file-es[10]' && na!='file-es[21]' && na!='file-es[7]' && na!='file-es[8]' && na!='file-es[18]'){
					                              		if($(this).data('obligatorio')==1)
					                                    if($(this).get(0).files.length==0){
					                                       files = false;
					                                    }
			                              			//console.log(na+" na");
			                              		    }

			                             	    }



			                             }else{
			                             	  var na = $(this).attr('name');
			                             	  if(origen==4){
			                             	  	  //agregarmos los documentos  7 y 18 que no son obligatorios para Reconocimiento mutuo Centroamericano
			                             	  	  if(na!='file-es[7]' && na!='file-es[18]'){
					                              		if($(this).data('obligatorio')==1)
					                                    if($(this).get(0).files.length==0){
					                                       files = false;
					                                    }
			                              			//console.log(na+" na");
			                              		    }

			                             	  }else{
				                             	  	 if($(this).data('obligatorio')==1)
					                                 if($(this).get(0).files.length==0){
					                                    files = false;
					                                 }
			                             	  }
			                             }
			                         });
                    }//cierre de if de origen==4
					   // console.log(tipoMedicamento);
					    return files;
		 				/*var files = true;
                        $.each($('input[type=file]'),function(){
                            if($(this).data('obligatorio')==1)
                                if($(this).get(0).files.length==0){
                                    files = false;
                                }
                        });

                        if(files){

                               return true;
                        }
                        else{
                            alertify.alert("Mensaje de sistema","¡Debe adjuntar un documento PDF en cada requisito que sea obligatorio!");
                             return false;
                        }*/
}