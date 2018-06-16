
                          ////////////////////////////LOGUEAR////////////////////////////////////
window.onload=function(){

var seccionLogin=document.getElementById('login');

if(localStorage.getItem('mitoken') !== null )
{
	seccionLogin.innerHTML="Estas Logueado con "+ JSON.parse(localStorage.getItem("mitoken")).datos['usuario']  + 
	
	"<br><input type='button' class='btn-primary'  value='Salir' onclick='CerrarCesion()'><br>";
}

TraerEmpleados();
	
}



function TraerToken()
{
	var token= JSON.parse(localStorage.getItem("mitoken")).token;
	return token;
}


function AjaxGet(direccion, funcionRetorno)
{
	Spinner();
		
		  var funcionAjax=$.ajax({
		url:direccion,
		type:"GET",
		data:false,
		cache: false, //no se borra es para subir archivos
    	contentType: false,//no se borra es para subir archivos
    	processData: false//no se borra es para subir archivos
	}).then(funcionRetorno,function(error){
Spinner();
			//lugarSpinner.style.display="none";
			//lugarError.innerHTML= "error";
			console.info("estamos en error", error);
            

	});
}

function AjaxPost(direccion, objJson, funcion)//////////////////////////       AjaxPost     ////////////////////////////////////////////////////////
{
	Spinner();
	var funcionAjax=$.ajax({
		url:direccion,
		type:"post",
		data:objJson
		/*cache: false, //no se borra es para subir archivos
    	contentType: false,//no se borra es para subir archivos
    	processData: false//no se borra es para subir archivos*/
	}).then(funcion,function(error){
		
		Spinner();
			console.info("error", error);
            

	});
}


function AjaxPut(direccion, objJson, funcion)
{
	Spinner();
		var funcionAjax=$.ajax({
		url:direccion,
		type:"put",
		data:objJson,
		//cache: false, //no se borra es para subir archivos
    	//contentType: false,//no se borra es para subir archivos
    	//processData: false,//no se borra es para subir archivos
		/*headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }*/
	}).then(funcion,function(error){

			console.info("estamos en error", error);
            

	});

}

function Spinner()
{
	var spinner = "<img src='imagenes/lg.palette-rotating-ring-loader.GIF' >";
	var lugarSpinner=document.getElementById('spinner');
	lugarSpinner.innerHTML=spinner;

	if(lugarSpinner.style.display=="none")
	{
		lugarSpinner.style.display="block";
	}
	else
	{
		lugarSpinner.style.display="none";
	}
}

function loguear()
{

    var usuario=document.getElementById('usuario').value;
    var clave= document.getElementById('clave').value;
    var seccionLogin= document.getElementById('login');
	var lugarError=document.getElementById('error');
	   lugarError.innerHTML="";
	var spinner = "<img src='imagenes/lg.palette-rotating-ring-loader.GIF' >";
	var lugarSpinner=document.getElementById('spinner');
     lugarSpinner.innerHTML = spinner;
	 lugarSpinner.style.display="block";
    

objJson=
{
    "usuario": usuario,
    "clave": clave

}
        var funcionAjax=$.ajax({
		url:"http://localhost:8080/TP_PROG3_1C_2018/backend/Sesion/",
		type:"post",
		data:objJson,
		/*cache: false, //no se borra es para subir archivos
    	contentType: false,//no se borra es para subir archivos
    	processData: false//no se borra es para subir archivos*/
	}).then(function(respuesta){

		lugarSpinner.style.display="none";
		
		console.log(respuesta);
		localStorage.setItem("mitoken",JSON.stringify(respuesta));
		seccionLogin.innerHTML="estas Logueado con "+ JSON.parse(localStorage.getItem("mitoken")).datos['usuario']  + 
		
		"<br><input type='button' class='btn-primary'  value='Salir' onclick='CerrarCesion()'>";
		
		console.log(seccionLogin.innerHTML);
		

	},function(error){

			lugarSpinner.style.display="none";
			lugarError.innerHTML= "error";
			console.info("error", error);
            

	});



    }


                                 ///////////////////////TraerEmpleados///////////////////////////////
function TraerEmpleados()
{

	AjaxGet("http://localhost:8080/TP_PROG3_1C_2018/backend/Empleados/",function(respuesta){

var tabla="<table class='table  table-light'>"+
			"<thead>"+
				"<th scope='col'>idEmpleado</th><th scope='col'>Usuario</th><th scope='col'>Sector</th><th scope='col'>Estado</th><th scope='col'>Foto</th><th scope='col'>Accion</th><tbody>";

				respuesta.map(function(empleados, i){

					 tabla = tabla + "<tr>"+
						"<td>"+ respuesta[i].id +"</td>"+"<td>" + respuesta[i].usuario +"</td><td>" + respuesta[i].sector +"</td><td>" + respuesta[i].estado +"</td>"+
							  "<td> <button onclick=SuspenderEmpleado("+ respuesta[i].id +",'"    +  respuesta[i].estado   +  "') value=Suspender class='btn btn-warning'>Suspender</button></td><td><button class='btn btn-danger' onclick=Eliminar("+ respuesta[i].id +") value=Eliminar>Eliminar</button></td><td><button class='btn btn-danger' onclick=ModificarEmpleado("+ JSON.stringify(respuesta[i]) +") value=Modificar>Modificar</button></td></tr>";

		});
		tabla=tabla+ "</tbody></table>"
		document.getElementById("tabla").innerHTML=tabla;	

		
		

	});
	

}

function CerrarCesion()
{
	var token=JSON.parse(localStorage.getItem("mitoken")).token;
	
	objJson={
		"token":token
	}
	

	   $.ajax({
		url:"http://localhost:8080/TP_PROG3_1C_2018/backend/Sesion/Salir",
		type:"put",
		data:objJson,
		//cache: false, //no se borra es para subir archivos
    	//contentType: false,//no se borra es para subir archivos
    	//processData: false,//no se borra es para subir archivos
		/*headers:{
                'Content-type': 'application/x-www-form-urlencoded'
		}*/
	}).then(function(respuesta){

	localStorage.removeItem('mitoken');
	document.getElementById('login').innerHTML= 

	      "<div id='formLogin'><form action='' class='form-inline'>  <input placeholder='Usuario' type='text' class='form-control' id='usuario'><br><input placeholder='clave' type='password' class='form-control' id='clave'><br><input type='button' class='btn-primary' id='logear' value='Login' onclick='loguear()'>  <div id='spinner'></div><br><div id='error'></div></form></div>";

		console.log(respuesta);

	},function(error){

			//lugarSpinner.style.display="none";
			//lugarError.innerHTML= "error";
			console.info("estamos en error", error);
            

	});
}

function Suspender(id)
{

}


function ModificarEmpleado(empleado)
{
	
	document.getElementById("txtUsuario").value=empleado.usuario;
	document.getElementById("txtSector").value=empleado.sector;
	document.getElementById("txtPerfil").value=empleado.perfil;
	document.getElementById("txtClave").value=empleado.clave;
	document.getElementById("idEmpleado").value=empleado.id;	

}



function GuardarEmpleado()
{
	var usuario=document.getElementById('txtUsuario').value;
	var sector=document.getElementById('txtSector').value;
	var perfil=document.getElementById('txtPerfil').value;
	var clave=document.getElementById('txtClave').value;
	var idEmpleado=document.getElementById("idEmpleado").value;
    var token= TraerToken();

	var datosDelForm=new FormData("formEmpleado");
	datosDelForm.append("usuario",usuario);
	datosDelForm.append("sector",sector);
	datosDelForm.append("perfil",perfil);
	datosDelForm.append("clave",clave);
	datosDelForm.append("id",idEmpleado);
	datosDelForm.append("token",token);
	

	if(idEmpleado=="")
	{

	  var funcionAjax=$.ajax({
		url:"http://localhost:8080/TP_PROG3_1C_2018/backend/Empleados/",
		type:"post",
		data:datosDelForm,
		cache: false, //no se borra es para subir archivos
    	contentType: false,//no se borra es para subir archivos
    	processData: false,//no se borra es para subir archivos
		/*		headers: {
                'Content-type': 'application/x-www-form-urlencoded'
				}*/
	}).then(function(respuesta){
		TraerEmpleados();

		console.log(respuesta);
		

	},function(error){

			//lugarSpinner.style.display="none";
			//lugarError.innerHTML= "error";
			console.info("estamos en error", error);
            

	});
	
	}
	else
	{

	 var funcionAjax=$.ajax({
		url:"http://localhost:8080/TP_PROG3_1C_2018/backend/Empleados/ModificarEmpleado",
		type:"post",
		data:datosDelForm,
		cache: false, //no se borra es para subir archivos
    	contentType: false,//no se borra es para subir archivos
    	processData: false,//no se borra es para subir archivos
		/*headers: {
                'Content-type': 'application/x-www-form-urlencoded'
            }*/
	}).then(function(respuesta){

	console.log(respuesta);

	},function(error){

	console.info("estamos en error", error);   

	});

		Spinner();
	}

		TraerEmpleados();
		document.getElementById('txtUsuario').value="";
		document.getElementById('txtSector').value="";;
		document.getElementById('txtPerfil').value="";;
		document.getElementById('txtClave').value="";;
		document.getElementById('idEmpleado').value="";

}





         
