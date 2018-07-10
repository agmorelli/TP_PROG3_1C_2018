
                          ////////////////////////////LOGUEAR////////////////////////////////////
window.onload=function()
{

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

function AjaxDelete(direccion, objJson, funcion)//////////////////////////       AjaxDelete     ////////////////////////////////////////////////////////
{
	Spinner();
	var funcionAjax=$.ajax({
		url:direccion,
		type:"delete",
		data:objJson
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

	AjaxGet("http://localhost:8080/TP_PROG3_1C_2018/backend/Empleados/ListaEmpleados",function(respuesta){

var tabla="<table class='table  table-light'>"+
			"<thead>"+
				"<th scope='col'>idEmpleado</th><th scope='col'>Usuario</th><th scope='col'>Sector</th><th scope='col'>Estado</th><th scope='col'>Foto</th><th scope='col'>Accion</th><tbody>";

				respuesta.map(function(empleados, i){

					 tabla = tabla + "<tr>"+
						"<td>"+ respuesta[i].id +"</td>"+"<td>" + respuesta[i].usuario +"</td><td>" + respuesta[i].sector +"</td><td>" + respuesta[i].estado +"</td>"+
							  "<td> <button onclick=SuspenderEmpleado("+ respuesta[i].id +",'"    +  respuesta[i].estado   +  "') value=Suspender class='btn btn-warning'>Suspender</button></td><td><button class='btn btn-danger' onclick=EliminarEmpleado("+ respuesta[i].id +") value=Eliminar>Eliminar</button></td><td><button class='btn btn-danger' onclick=ModificarEmpleado("+ JSON.stringify(respuesta[i]) +") value=Modificar>Modificar</button></td></tr>";

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

function SuspenderEmpleado(id,estado)
{
	let token=TraerToken();
	var objJson={
		"token": token,
		"estado": estado,
		"id":id
	}
	AjaxPut("http://localhost:8080/TP_PROG3_1C_2018/backend/Empleados/Suspender",objJson,function(respuesta){
	
	console.log(respuesta);
	});
	TraerEmpleados();
	Spinner();
}

function EliminarEmpleado(id)
{
	let objJson =
	{
		"token": TraerToken(),
		"id": id,
	}

	AjaxDelete("http://localhost:8080/TP_PROG3_1C_2018/backend/Empleados/",objJson, function(respuesta){

		TraerEmpleados();
	});

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

function AgregarProducto(producto)
{
	var listProd = document.getElementById('listaProductos').value;
	listProd= listProd + producto + ",";
	document.getElementById('listaProductos').value=listProd;

	AjaxGet("http://localhost:8080/TP_PROG3_1C_2018/backend/Productos/"+producto,function(respuesta){
		
		var tabla=document.getElementById('tabProductos').innerHTML;
		
		tabla=tabla+"<tr><td>"+respuesta.nombre+"</td><td>"+respuesta.precio+"</td></tr>";
		document.getElementById('tabProductos').innerHTML=tabla;
		Spinner();
	


	});

	
}

function IngresarPedido()
{

	var idMesa=document.getElementById('txtMesa').value;
	var pedido=document.getElementById('listaProductos').value;
	console.info(pedido);
	var inputFileImage = document.getElementById("foto");
	var foto = inputFileImage.files[0];
    var token= TraerToken();
	var datosDelForm=new FormData("formPedido");
	datosDelForm.append("token",token);
	datosDelForm.append("idMesa",idMesa);
	datosDelForm.append("pedido", pedido);
	datosDelForm.append("foto",foto);

	var funcionAjax=$.ajax({
		url:"http://localhost:8080/TP_PROG3_1C_2018/backend/Pedidos/",
		type:"post",
		data:datosDelForm,
		cache: false, //no se borra es para subir archivos
    	contentType: false,//no se borra es para subir archivos
    	processData: false,//no se borra es para subir archivos
		/*		headers: {
                'Content-type': 'application/x-www-form-urlencoded'
}*/
	}).then(function(respuesta){

		console.log(respuesta);
		document.getElementById('idPedido').innerHTML="<h1>"+respuesta.idPedido+" </h1>" ;
		

	},function(error){

			//lugarSpinner.style.display="none";
			//lugarError.innerHTML= "error";
			console.info("estamos en error", error);
            

	});
	document.getElementById('txtMesa').value="";
	document.getElementById('listaProductos').value="";
	document.getElementById('tabProductos').innerHTML="";
}

function ListaPendientes()
{
	document.getElementById('tablaPendientes').innerHTML="";
	var tabla =document.getElementById('tablaPendientes').innerHTML;
	var objJson={
		"token": TraerToken()
	}
	var tabla;

	AjaxPost("http://localhost:8080/TP_PROG3_1C_2018/backend/Pedidos/PendientesEmpleado",objJson,function(respuesta){

	respuesta.map(function(detalle, i){
		
		tabla=tabla + "<tr><td>"+detalle.idDetalle+"</td><td>"+detalle.idPedido+"</td><td>"+detalle.producto+"</td><td>"+detalle.estado+"</td><td>"+detalle.sector+
		"</td><td> <button class='btn btn-primary' onclick='PrepararPedido("+detalle.idDetalle+")'>Preparar </button> </td><td><input type='text' id='tiempoPreparacion'></td>"+
		"<td><button class='btn btn-primary' onclick='ServirPedido("+detalle.idDetalle+")'>Servir</button></td></tr>";


	});
	document.getElementById('tablaPendientes').innerHTML=tabla;
	});
	
	document.getElementById('tablaPendientes').innerHTML=tabla;
	Spinner();
}

function PrepararPedido(indice)
{
	var tiempo=document.getElementById('tiempoPreparacion').value;
	var objJson={
		"token": TraerToken(),
		"idDetalle":indice,
		"tiempoPreparacion": tiempo
	}
	console.log(tiempoPreparacion);
	AjaxPost("http://localhost:8080/TP_PROG3_1C_2018/backend/Pedidos/PrepararPedido",objJson,function(respuesta){
		console.log(respuesta);

	});
	Spinner();
	ListaPendientes();

}

function ServirPedido(indice)
{
	var objJson={
		"token": TraerToken(),
		"idDetalle":indice
	}
	AjaxPost("http://localhost:8080/TP_PROG3_1C_2018/backend/Pedidos/ServirPedido",objJson,function(respuesta){
		console.log(respuesta);

	});
	Spinner();
	ListaPendientes();

}





         
