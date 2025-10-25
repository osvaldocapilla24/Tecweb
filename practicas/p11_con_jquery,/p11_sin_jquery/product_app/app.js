// =========================================
// EJERCICIO 3 y 4 - jQuery con Edición
// =========================================

// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 0.0,
    "unidades": 1,
    "modelo": "XX-000",
    "marca": "NA",
    "detalles": "NA",
    "imagen": "img/default.png"
};

// FUNCIÓN PARA INICIALIZAR EL TEXTAREA
function init() {
    var JsonString = JSON.stringify(baseJSON, null, 2);
    $('#description').val(JsonString);
    listarProductos();
}

// ============================================
// CUANDO EL DOCUMENTO ESTÉ LISTO
// ============================================
$(document).ready(function() {
    
    // Llamar a init al cargar la página
    init();
    
    
    // ============================================
    // EVENTO: BÚSQUEDA
    // ============================================
    $('#search').keyup(function(e) {
        e.preventDefault();
        var search = $('#search').val();

        $.ajax({
            url: './backend/product-search.php?search=' + search,
            type: 'GET',
            contentType: 'application/x-www-form-urlencoded',
            success: function(response) {
                let productos = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (Object.keys(productos).length > 0) {
                    let template = '';
                    let template_bar = '';

                    productos.forEach(producto => {
                        let descripcion = '';
                        descripcion += '<li>precio: ' + producto.precio + '</li>';
                        descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                        descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                        descripcion += '<li>marca: ' + producto.marca + '</li>';
                        descripcion += '<li>detalles: ' + producto.detalles + '</li>';
                    
                        template += `
                            <tr productId="${producto.id}">
                                <td>${producto.id}</td>
                                <td><a href="#" class="product-item">${producto.nombre}</a></td>
                                <td><ul>${descripcion}</ul></td>
                                <td>
                                    <button class="product-delete btn btn-danger">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;

                        template_bar += `<li>${producto.nombre}</li>`;
                    });
                    
                    $('#product-result').attr('class', 'card my-4 d-block');
                    $('#container').html(template_bar);
                    $('#products').html(template);
                }
            }
        });
    });
    
    
    // ============================================
    // EVENTO: AGREGAR O EDITAR PRODUCTO
    // ============================================
    $('#product-form').submit(function(e) {
        e.preventDefault();

        var productoJsonString = $('#description').val();
        
        // VALIDACIÓN 1: Verificar que el JSON sea válido
        var finalJSON;
        try {
            finalJSON = JSON.parse(productoJsonString);
        } catch(error) {
            alert('ERROR: El formato JSON no es válido. Revisa que no falten comas, llaves o comillas.');
            return false;
        }
        
        // VALIDACIÓN 2: Verificar que el nombre no esté vacío
        var nombre = $('#name').val().trim();
        if (nombre === '') {
            alert('ERROR: El nombre del producto es obligatorio');
            return false;
        }
        
        // VALIDACIÓN 3: Verificar campos obligatorios del JSON
        if (!finalJSON.marca || finalJSON.marca.trim() === '') {
            alert('ERROR: La marca del producto es obligatoria');
            return false;
        }
        
        if (!finalJSON.modelo || finalJSON.modelo.trim() === '') {
            alert('ERROR: El modelo del producto es obligatorio');
            return false;
        }
        
        if (!finalJSON.precio || finalJSON.precio <= 0) {
            alert('ERROR: El precio debe ser mayor a 0');
            return false;
        }
        
        if (!finalJSON.unidades || finalJSON.unidades < 0) {
            alert('ERROR: Las unidades deben ser 0 o mayor');
            return false;
        }
        
        finalJSON['nombre'] = nombre;
        
        // Verificar si es edición o inserción
        var productId = $('#productId').val();
        var url = './backend/product-add.php';
        
        if (productId) {
            url = './backend/product-edit.php';
            finalJSON['id'] = productId;
        }
        
        productoJsonString = JSON.stringify(finalJSON, null, 2);

        $.ajax({
            url: url,
            type: 'POST',
            contentType: 'application/json;charset=UTF-8',
            data: productoJsonString,
            success: function(response) {
                let respuesta = typeof response === 'string' ? JSON.parse(response) : response;
                let template_bar = '';
                template_bar += `
                    <li style="list-style: none;">status: ${respuesta.status}</li>
                    <li style="list-style: none;">message: ${respuesta.message}</li>
                `;

                $('#product-result').attr('class', 'card my-4 d-block');
                $('#container').html(template_bar);

                // Limpiar el formulario
                $('#product-form').trigger('reset');
                $('#productId').val('');
                $('#product-form button[type="submit"]').text('Agregar Producto');
                init();

                listarProductos();
            }
        });
    });
    
    
    // ============================================
    // EVENTO: ELIMINAR PRODUCTO
    // ============================================
    $(document).on('click', '.product-delete', function() {
        if (confirm("De verdad deseas eliminar el Producto")) {
            var id = $(this).parent().parent().attr('productId');

            $.ajax({
                url: './backend/product-delete.php?id=' + id,
                type: 'GET',
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    let respuesta = typeof response === 'string' ? JSON.parse(response) : response;
                    let template_bar = '';
                    template_bar += `
                        <li style="list-style: none;">status: ${respuesta.status}</li>
                        <li style="list-style: none;">message: ${respuesta.message}</li>
                    `;

                    $('#product-result').attr('class', 'card my-4 d-block');
                    $('#container').html(template_bar);

                    listarProductos();
                }
            });
        }
    });
    
    
    // ============================================
    // EVENTO: EDITAR PRODUCTO (cargar datos)
    // ============================================
    $(document).on('click', '.product-item', function(e) {
        e.preventDefault();
        
        var id = $(this).closest('tr').attr('productId');
        
        $.ajax({
            url: './backend/product-single.php?id=' + id,
            type: 'GET',
            success: function(response) {
                let producto = typeof response === 'string' ? JSON.parse(response) : response;
                
                // Llenar el formulario
                $('#productId').val(producto.id);
                $('#name').val(producto.nombre);
                
                // Crear el JSON para el textarea
                let productoJSON = {
                    precio: producto.precio,
                    unidades: producto.unidades,
                    modelo: producto.modelo,
                    marca: producto.marca,
                    detalles: producto.detalles,
                    imagen: producto.imagen
                };
                
                $('#description').val(JSON.stringify(productoJSON, null, 2));
                
                // Cambiar el texto del botón
                $('#product-form button[type="submit"]').text('Actualizar Producto');
            },
            error: function() {
                alert('Error al obtener el producto');
            }
        });
    });
    
});


// ============================================
// FUNCIÓN PARA LISTAR TODOS LOS PRODUCTOS
// ============================================
function listarProductos() {
    $.ajax({
        url: './backend/product-list.php',
        type: 'GET',
        contentType: 'application/x-www-form-urlencoded',
        success: function(response) {
            let productos = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (Object.keys(productos).length > 0) {
                let template = '';

                productos.forEach(producto => {
                    let descripcion = '';
                    descripcion += '<li>precio: ' + producto.precio + '</li>';
                    descripcion += '<li>unidades: ' + producto.unidades + '</li>';
                    descripcion += '<li>modelo: ' + producto.modelo + '</li>';
                    descripcion += '<li>marca: ' + producto.marca + '</li>';
                    descripcion += '<li>detalles: ' + producto.detalles + '</li>';
                
                    template += `
                        <tr productId="${producto.id}">
                            <td>${producto.id}</td>
                            <td><a href="#" class="product-item">${producto.nombre}</a></td>
                            <td><ul>${descripcion}</ul></td>
                            <td>
                                <button class="product-delete btn btn-danger">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    `;
                });
                
                $('#products').html(template);
            }
        }
    });
}