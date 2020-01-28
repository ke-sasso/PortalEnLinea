
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title> </title>
    <style type="text/css">
   
      body{
        font-size: 14px;

      }

       #wrap {
      float: center;
      position: relative;
      left: 35%;
    }

    #content {
        float: center;
        position: relative;
      }
      div#header{
        width: 74%;
        display: inline-block;
        margin: 0 auto; 
        border:1px solid black;
      }
      div#header img#escudo{
        height: 60px;
        width: auto;
        max-width: 20%;
        display: inline-block;
        margin: 0.5em;
      }
      div#header img#logo{
        height: 40px;
        width: auto;
        max-width: 20%;
        display: inline-block;
        margin: 0.5em;
      }
      div#header div#mainTitle{
        width: 65%;
        display: inline-block;
        margin: 0.5em;  
        margin-right: 1em;
        text-align: center; 
      }
      #footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        padding: 0;
        text-align: center;
      }
      div#subTitle{
        width: 300px;
        height: 50px; 
        margin: 0 auto;
        bottom: 200px;
        border: 1px solid black;  
      }

      table#comprobante{
        width: auto;
        min-width: 95%;
        max-width: 95%;
        margin: 0 auto;
        border-collapse: collapse !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;
        font-style: 'times new roman' !important;
        width: auto !important;
        max-width: 70% !important;
        margin: 0 auto;

      }
      table#comprobante tr td{
      border-left: .5px solid black;
      border-right: .5px solid black;
      border-top: .5px solid black;
      border-bottom: .5px solid black !important;
      padding-bottom: 0px  !important;
      text-align: center !important;
      max-width: 70% !important;
      margin: 0 auto;
      
    }
     
      #firma{
        height: auto; 
        width: auto; 
        max-width: 400px; 
        max-height: 800px;
      
      }
    </style>
  </head>
  <body>


    <header>
    <table  style="width:100%;">
      <tr>
        <td style="width:15%;">
          <center>
            <img id="escudo" src="{{ url('img/escudo.png') }}" />
          </center> 
        </td>
        <td style="width:70%;">
          <center>
            <h2 style="margin:0;padding:0;">
              &nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Dirección Nacional de Medicamentos &nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <p>&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;República de El Salvador, América Central&nbsp;&nbsp;
              &nbsp;&nbsp;&nbsp;
              &nbsp;&nbsp;</p>
              <strong>Expediente electrónico de Establecimiento</strong>
              ___________________________________________________________
            </h2>
          </center>
        </td>
        <td>
          <center>
            <img id="logo" src="{{ url('img/dnm.png') }}" />
          </center> 
        </td>
    </table>
    </header>
    <br/>
    <br/>
    <br/>

   <div align="center">
     <main>
             

        <h3>DATOS GENERALES DEL ESTABLECIMIENTO</h3>
       
        <table id="comprobante" style="width:100%;">
          <tbody>            
            <tr>

               <td width="30" height="40">Nombre del establecimiento:</td>
               <td height="40">{{$general->nombreComercial}} &nbsp;&nbsp;&nbsp;&nbsp;</td>
        
            </tr>
              <tr>
               <td width="30" height="40">Dirección:</td>
               <td height="40">{{$general->direccion}} ,&nbsp;&nbsp;&nbsp;{{$general->departamento}} {{$general->pais}}</td>
             
            </tr>
             <tr>
               <td width="30" height="40">Teléfonos:</td>
               <td height="40">{{json_decode($general->telefonosContacto)[0]}}&nbsp;&nbsp;&nbsp;&nbsp;{{json_decode($general->telefonosContacto)[1]}}</td>
            </tr>
            <tr>
               <td width="30" height="40">Correo Electrónico:</td>
               <td height="40">{{$general->emailContacto}}</td>
            </tr>
            <tr>
               <td width="30" height="40">Vigente hasta:</td>
               <td height="30">{{$general->vigenteHasta}}</td>
               
        
            </tr>
            <tr>
               <td width="20" height="20">Estado:</td>
               <td>{{$general->nombreEstado}}</td>
            </tr>

          
            @if(!empty($pro))
            <tr>
               <td width="30" height="40">Propietario:</td>
               <td height="40"> @foreach($pro as $p) 
                      {{$p->nombre}}@endforeach</td>
             </tr>
            @endif
            
         
          </tbody>
        </table>
      
      
     </main>
    </div>
    @if(!empty($reg))
    <div style="page-break-after:always;"></div>
    <br/>

    <div align="center">
     <main>
            <h3>REGENTES DEL ESTABLECIMIENTO</h3>
            <br/>
        @foreach($reg as $g)
        <table id="comprobante" style="width:100%;">
          <tbody> 
           
            <tr>
               <td width="30" height="40">Regentes:</td>
               <td height="40">{{$g->nombre}}
              </td>
            </tr>
         
          </tbody>
        </table>
        <br>
          @if(!empty($g->horarioRegente))
        <h3>HORARIO DE REGENTE</h3>
        <table id="comprobante" style="width:100%;">
          <tbody> 
           

            <tr>
               <td width="30" height="40">LUNES</td>
               <td width="30" height="40">MARTES</td>
               <td width="30" height="40">MIÉRCOLES</td>
               <td width="30" height="40">JUEVES</td>
               <td width="30" height="40">VIERNES</td>
               <td width="30" height="40">SÁBADO</td>
               <td width="30" height="40">DOMINGO</td>  
            </tr>
        
         @foreach(json_decode($g->horarioRegente) as $h)
           
              <tr>
               <td width="30" height="40">{{$h->L1}} {{$h->L2}}</td>
               <td width="30" height="40">{{$h->M1}} {{$h->M2}}</td>
               <td width="30" height="40">{{$h->MI1}} {{$h->MI2}}</td>
               <td width="30" height="40">{{$h->J1}} {{$h->J2}}</td>
               <td width="30" height="40">{{$h->V1}} {{$h->V2}}</td>
               <td width="30" height="40">{{$h->S1}} {{$h->S2}}</td>
               <td width="30" height="40">{{$h->D1}} {{$h->D2}}</td>  
            </tr>
       @endforeach

       @else
        <h3>HORARIO DE REGENTE</h3>
        <table id="comprobante" style="width:100%;">
          <tbody> 
            <tr>
               <td width="30" height="40">LUNES</td>
               <td width="30" height="40">MARTES</td>
               <td width="30" height="40">MIÉRCOLES</td>
               <td width="30" height="40">JUEVES</td>
               <td width="30" height="40">VIERNES</td>
               <td width="30" height="40">SÁBADO</td>
               <td width="30" height="40">DOMINGO</td>  
            </tr>
             <tr>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>  
            </tr>
             <tr>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>  
            </tr>
             <tr>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>  
            </tr>
             <tr>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>
               <td width="30" height="40"></td>  
            </tr>


       @endif
         
          </tbody>
        </table>
        

         @endforeach

        <br>
        <br>
        <br>

      
     </main>
    </div>
    @endif

    <footer id="footer">
   ______________________________________________________________________________________________________
   Blv. Merliot y Av. Jayaque, Edif. DNM, Urb. Jardines del Volcán, Santa Tecla, La Libertad, El Salvador, América Central.
   &nbsp;&nbsp;
   PBX 2522-5000 / Correo: notificaciones.registro@medicamentos.gob.sv / web: www.medicamentos.gob.sv
    </footer>

  </body>

</html>