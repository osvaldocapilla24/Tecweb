// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// FUNCIÓN CALLBACK DE BOTÓN "Buscar Producto" (NUEVA)
function buscarProducto(e) {
    e.preventDefault();

    // SE OBTIENE EL TEXTO A BUSCAR
    var search = document.getElementById('search').value;

    console.log('Buscando: ' + search); // PARA DEBUG

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL ARRAY DE PRODUCTOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);
            
            // SE VERIFICA SI EL ARRAY TIENE DATOS
            if(Array.isArray(productos) && productos.length > 0) {
                // SE CREA UNA PLANTILLA PARA TODAS LAS FILAS
                let template = '';
                
                // SE RECORRE CADA PRODUCTO ENCONTRADO
                productos.forEach(function(producto) {
                    // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                    let descripcion = '';
                    descripcion += '<li>precio: '+producto.precio+'</li>';
                    descripcion += '<li>unidades: '+producto.unidades+'</li>';
                    descripcion += '<li>modelo: '+producto.modelo+'</li>';
                    descripcion += '<li>marca: '+producto.marca+'</li>';
                    descripcion += '<li>detalles: '+producto.detalles+'</li>';
                    
                    // SE AGREGA LA FILA DEL PRODUCTO AL TEMPLATE
                    template += `
                        <tr>
                            <td>${producto.id}</td>
                            <td>${producto.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;
                });

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            } else {
                console.log('No se encontraron productos');
                // SI NO HAY PRODUCTOS, MOSTRAR MENSAJE
                document.getElementById("productos").innerHTML = '<tr><td colspan="3">No se encontraron productos</td></tr>';
            }
        }
    };
    // SE ENVÍA EL PARÁMETRO "search" EN LUGAR DE "id"
    client.send("search="+search);
}

// FUNCIÓN CALLBACK DE BOTÓN "Buscar por ID" (ORIGINAL)
function buscarID(e) {
    e.preventDefault();

    // SE OBTIENE EL ID A BUSCAR
    var id = document.getElementById('search').value;

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/read.php', true);
    client.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log('[CLIENTE]\n'+client.responseText);
            
            // SE OBTIENE EL OBJETO DE DATOS A PARTIR DE UN STRING JSON
            let productos = JSON.parse(client.responseText);
            
            // SE VERIFICA SI EL OBJETO JSON TIENE DATOS
            if(Object.keys(productos).length > 0) {
                // SE CREA UNA LISTA HTML CON LA DESCRIPCIÓN DEL PRODUCTO
                let descripcion = '';
                    descripcion += '<li>precio: '+productos.precio+'</li>';
                    descripcion += '<li>unidades: '+productos.unidades+'</li>';
                    descripcion += '<li>modelo: '+productos.modelo+'</li>';
                    descripcion += '<li>marca: '+productos.marca+'</li>';
                    descripcion += '<li>detalles: '+productos.detalles+'</li>';
                
                // SE CREA UNA PLANTILLA PARA CREAR LA(S) FILA(S) A INSERTAR EN EL DOCUMENTO HTML
                let template = '';
                    template += `
                        <tr>
                            <td>${productos.id}</td>
                            <td>${productos.nombre}</td>
                            <td><ul>${descripcion}</ul></td>
                        </tr>
                    `;

                // SE INSERTA LA PLANTILLA EN EL ELEMENTO CON ID "productos"
                document.getElementById("productos").innerHTML = template;
            }
        }
    };
    client.send("id="+id);
}

// FUNCIÓN CALLBACK DE BOTÓN "Agregar Producto"
// FUNCIÓN CALLBACK DE BOTÓN "Agregar Producto"
function agregarProducto(e) {
    e.preventDefault();

    // SE OBTIENE EL NOMBRE DEL PRODUCTO
    var nombre = document.getElementById('name').value.trim();
    
    // SE OBTIENE EL JSON DEL PRODUCTO
    var productoJsonString = document.getElementById('description').value;
    var productoJson;
    
    // VALIDAR QUE EL JSON SEA VÁLIDO
    try {
        productoJson = JSON.parse(productoJsonString);
    } catch (error) {
        window.alert('Error: El JSON no es válido');
        return;
    }
    
    // a. VALIDAR NOMBRE: requerido y máximo 100 caracteres
    if (nombre === '') {
        window.alert('Error: El nombre es requerido');
        return;
    }
    if (nombre.length > 100) {
        window.alert('Error: El nombre debe tener 100 caracteres o menos');
        return;
    }
    
    // b. VALIDAR MARCA: requerida (debe ser seleccionada de lista)
    if (!productoJson.marca || productoJson.marca.trim() === '' || productoJson.marca === 'NA') {
        window.alert('Error: La marca es requerida');
        return;
    }
    
    // c. VALIDAR MODELO: requerido, alfanumérico, máximo 25 caracteres
    if (!productoJson.modelo || productoJson.modelo.trim() === '') {
        window.alert('Error: El modelo es requerido');
        return;
    }
    // Validar que sea alfanumérico (letras, números, guiones, espacios)
    var regexAlfanumerico = /^[a-zA-Z0-9\s\-]+$/;
    if (!regexAlfanumerico.test(productoJson.modelo)) {
        window.alert('Error: El modelo debe ser alfanumérico');
        return;
    }
    if (productoJson.modelo.length > 25) {
        window.alert('Error: El modelo debe tener 25 caracteres o menos');
        return;
    }
    
    // d. VALIDAR PRECIO: requerido y mayor a 99.99
    if (!productoJson.precio || productoJson.precio === '') {
        window.alert('Error: El precio es requerido');
        return;
    }
    var precio = parseFloat(productoJson.precio);
    if (isNaN(precio) || precio <= 99.99) {
        window.alert('Error: El precio debe ser mayor a 99.99');
        return;
    }
    
    // e. VALIDAR DETALLES: opcional, pero máximo 250 caracteres si se usa
    if (productoJson.detalles) {
        if (productoJson.detalles.length > 250) {
            window.alert('Error: Los detalles deben tener 250 caracteres o menos');
            return;
        }
    } else {
        // Si no vienen detalles, asignar valor por defecto
        productoJson.detalles = 'NA';
    }
    
    // f. VALIDAR UNIDADES: requeridas y mayor o igual a 0
    if (productoJson.unidades === undefined || productoJson.unidades === '') {
        window.alert('Error: Las unidades son requeridas');
        return;
    }
    var unidades = parseInt(productoJson.unidades);
    if (isNaN(unidades) || unidades < 0) {
        window.alert('Error: Las unidades deben ser mayor o igual a 0');
        return;
    }
    
    // g. VALIDAR IMAGEN: opcional, pero usar default si no se proporciona
    if (!productoJson.imagen || productoJson.imagen.trim() === '') {
        productoJson.imagen = 'img/default.png';
    }
    
    // SI TODAS LAS VALIDACIONES PASAN, SE AGREGA EL NOMBRE AL JSON
    productoJson.nombre = nombre;
    
    // SE CONVIERTE DE NUEVO A STRING
    productoJsonString = JSON.stringify(productoJson);

    // SE CREA EL OBJETO DE CONEXIÓN ASÍNCRONA AL SERVIDOR
    var client = getXMLHttpRequest();
    client.open('POST', './backend/create.php', true);
    client.setRequestHeader('Content-Type', "application/json;charset=UTF-8");
    client.onreadystatechange = function () {
        // SE VERIFICA SI LA RESPUESTA ESTÁ LISTA Y FUE SATISFACTORIA
        if (client.readyState == 4 && client.status == 200) {
            console.log(client.responseText);
            
            // SE PARSEA LA RESPUESTA
            let respuesta = JSON.parse(client.responseText);
            
            // SE MUESTRA EL MENSAJE EN UN ALERT
            window.alert(respuesta.message);
            
            // SI FUE EXITOSO, LIMPIAR FORMULARIO
            if (respuesta.status === 'success') {
                document.getElementById('name').value = '';
                document.getElementById('description').value = JSON.stringify(baseJSON, null, 2);
            }
        }
    };
    client.send(productoJsonString);
}

// SE CREA EL OBJETO DE CONEXIÓN COMPATIBLE CON EL NAVEGADOR
function getXMLHttpRequest() {
    var objetoAjax;

    try{
        objetoAjax = new XMLHttpRequest();
    }catch(err1){
        /**
         * NOTA: Las siguientes formas de crear el objeto ya son obsoletas
         *       pero se comparten por motivos historico-académicos.
         */
        try{
            // IE7 y IE8
            objetoAjax = new ActiveXObject("Msxml2.XMLHTTP");
        }catch(err2){
            try{
                // IE5 y IE6
                objetoAjax = new ActiveXObject("Microsoft.XMLHTTP");
            }catch(err3){
                objetoAjax = false;
            }
        }
    }
    return objetoAjax;
}

function init() {
    /**
     * Convierte el JSON a string para poder mostrarlo
     * ver: https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/JSON
     */
    var JsonString = JSON.stringify(baseJSON,null,2);
    document.getElementById("description").value = JsonString;
}