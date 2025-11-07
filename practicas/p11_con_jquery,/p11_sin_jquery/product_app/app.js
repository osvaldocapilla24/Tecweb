// =========================================
// EJERCICIO 3, 4 y 5 - jQuery con Validaciones
// =========================================

// JSON BASE A MOSTRAR EN FORMULARIO
var baseJSON = {
    "precio": 100.00,
    "unidades": 0,
    "modelo": "XXX-000",
    "marca": "",
    "detalles": "",
    "imagen": "img/default.png"
};

// Variable para controlar validación del nombre
var nombreValido = false;

// FUNCIÓN PARA INICIALIZAR LOS CAMPOS DEL FORMULARIO
function init() {
    $('#precio').val(baseJSON.precio);
    $('#unidades').val(baseJSON.unidades);
    $('#modelo').val(baseJSON.modelo);
    $('#marca').val(baseJSON.marca);
    $('#detalles').val(baseJSON.detalles);
    $('#imagen').val(baseJSON.imagen);
    listarProductos();
}

// FUNCIÓN PARA MOSTRAR ESTADO DE VALIDACIÓN
function mostrarEstado(campo, mensaje, tipo) {
    var statusElement = $('#' + campo + '-status');
    statusElement.text(mensaje);
    
    if (tipo === 'error') {
        statusElement.removeClass('text-success').addClass('text-danger');
    } else if (tipo === 'success') {
        statusElement.removeClass('text-danger').addClass('text-success');
    } else {
        statusElement.removeClass('text-danger text-success').addClass('text-muted');
    }
}

// ============================================
// CUANDO EL DOCUMENTO ESTÉ LISTO
// ============================================
$(document).ready(function() {
    
    // Llamar a init al cargar la página
    init();
    
    
    // ============================================
    // 5.1 y 7. VALIDACIONES EN TIEMPO REAL (blur)
    // ============================================
    
    // Validar NOMBRE (mientras escribe - keyup)
    $('#name').keyup(function() {
        var nombre = $(this).val().trim();
        var productId = $('#productId').val();
        
        if (nombre.length > 0) {
            if (nombre.length < 3) {
                mostrarEstado('name', 'El nombre debe tener al menos 3 caracteres', 'error');
                nombreValido = false;
            } else if (nombre.length > 100) {
                mostrarEstado('name', 'El nombre no debe exceder 100 caracteres', 'error');
                nombreValido = false;
            } else {
                // 7. Validar que el nombre no exista en la BD
                $.ajax({
                    url: './backend/product-name-exists.php',
                    type: 'GET',
                    data: { nombre: nombre, id: productId },
                    success: function(response) {
                        var resultado = typeof response === 'string' ? JSON.parse(response) : response;
                        
                        if (resultado.exists) {
                            mostrarEstado('name', '⚠️ ' + resultado.message, 'error');
                            nombreValido = false;
                        } else {
                            mostrarEstado('name', '✓ ' + resultado.message, 'success');
                            nombreValido = true;
                        }
                    }
                });
            }
        } else {
            mostrarEstado('name', '', '');
            nombreValido = false;
        }
    });
    
    // Validar NOMBRE cuando pierde el foco
    $('#name').blur(function() {
        var nombre = $(this).val().trim();
        if (nombre === '') {
            mostrarEstado('name', '⚠️ El nombre es obligatorio', 'error');
            nombreValido = false;
        }
    });
    
    // Validar PRECIO
    $('#precio').blur(function() {
        var precio = parseFloat($(this).val());
        
        if (isNaN(precio) || precio <= 0) {
            mostrarEstado('precio', '⚠️ El precio debe ser mayor a 0', 'error');
        } else if (precio > 99999.99) {
            mostrarEstado('precio', '⚠️ El precio es demasiado alto', 'error');
        } else {
            mostrarEstado('precio', '✓ Precio válido', 'success');
        }
    });
    
    // Validar UNIDADES
    $('#unidades').blur(function() {
        var unidades = parseInt($(this).val());
        
        if (isNaN(unidades) || unidades < 0) {
            mostrarEstado('unidades', '⚠️ Las unidades deben ser 0 o mayor', 'error');
        } else if (unidades > 9999) {
            mostrarEstado('unidades', '⚠️ Cantidad de unidades demasiado alta', 'error');
        } else {
            mostrarEstado('unidades', '✓ Unidades válidas', 'success');
        }
    });
    
    // Validar MODELO
    $('#modelo').blur(function() {
        var modelo = $(this).val().trim();
        
        if (modelo === '') {
            mostrarEstado('modelo', '⚠️ El modelo es obligatorio', 'error');
        } else if (modelo.length < 3) {
            mostrarEstado('modelo', '⚠️ El modelo debe tener al menos 3 caracteres', 'error');
        } else if (modelo.length > 25) {
            mostrarEstado('modelo', '⚠️ El modelo no debe exceder 25 caracteres', 'error');
        } else {
            mostrarEstado('modelo', '✓ Modelo válido', 'success');
        }
    });
    
    // Validar MARCA
    $('#marca').blur(function() {
        var marca = $(this).val().trim();
        
        if (marca === '') {
            mostrarEstado('marca', '⚠️ La marca es obligatoria', 'error');
        } else if (marca.length < 2) {
            mostrarEstado('marca', '⚠️ La marca debe tener al menos 2 caracteres', 'error');
        } else if (marca.length > 25) {
            mostrarEstado('marca', '⚠️ La marca no debe exceder 25 caracteres', 'error');
        } else {
            mostrarEstado('marca', '✓ Marca válida', 'success');
        }
    });
    
    // Validar DETALLES
    $('#detalles').blur(function() {
        var detalles = $(this).val().trim();
        
        if (detalles.length > 250) {
            mostrarEstado('detalles', '⚠️ Los detalles no deben exceder 250 caracteres', 'error');
        } else if (detalles.length > 0) {
            mostrarEstado('detalles', '✓ Detalles válidos', 'success');
        } else {
            mostrarEstado('detalles', '', '');
        }
    });
    
    // Validar IMAGEN
    $('#imagen').blur(function() {
        var imagen = $(this).val().trim();
        
        if (imagen === '') {
            mostrarEstado('imagen', '', '');
        } else if (imagen.length > 0) {
            mostrarEstado('imagen', '✓ Ruta válida', 'success');
        }
    });
    
    
    // ============================================
    // EVENTO: BÚSQUEDA
    // ============================================
    $('#search').keyup(function(e) {
        e.preventDefault();
        var search = $(this).val();

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

        // 5.2. VALIDACIÓN FINAL antes de enviar
        var errores = [];
        
        // Validar nombre
        var nombre = $('#name').val().trim();
        if (nombre === '') {
            errores.push('El nombre del producto es obligatorio');
        } else if (!nombreValido && $('#productId').val() === '') {
            errores.push('El nombre del producto ya existe o no es válido');
        }
        
        // Validar precio
        var precio = parseFloat($('#precio').val());
        if (isNaN(precio) || precio <= 0) {
            errores.push('El precio debe ser mayor a 0');
        }
        
        // Validar unidades
        var unidades = parseInt($('#unidades').val());
        if (isNaN(unidades) || unidades < 0) {
            errores.push('Las unidades deben ser 0 o mayor');
        }
        
        // Validar modelo
        var modelo = $('#modelo').val().trim();
        if (modelo === '') {
            errores.push('El modelo es obligatorio');
        }
        
        // Validar marca
        var marca = $('#marca').val().trim();
        if (marca === '') {
            errores.push('La marca es obligatoria');
        }
        
        // Si hay errores, mostrarlos y detener
        if (errores.length > 0) {
            alert('Errores encontrados:\n\n- ' + errores.join('\n- '));
            return false;
        }
        
        // Construir el JSON
        var finalJSON = {
            precio: precio,
            unidades: unidades,
            modelo: modelo,
            marca: marca,
            detalles: $('#detalles').val().trim(),
            imagen: $('#imagen').val().trim(),
            nombre: nombre
        };
        
        // Verificar si es edición o inserción
        var productId = $('#productId').val();
        var url = './backend/product-add.php';
        
        if (productId) {
            url = './backend/product-edit.php';
            finalJSON['id'] = productId;
        }
        
        var productoJsonString = JSON.stringify(finalJSON, null, 2);

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

                // Limpiar el formulario y estados
                $('#product-form').trigger('reset');
                $('#productId').val('');
                $('small[id$="-status"]').text('').removeClass('text-danger text-success text-muted');
                nombreValido = false;
                
                // Cambiar botón de vuelta a "Agregar Producto"
                $('button.btn-primary').text("Agregar Producto");
                
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
    $(document).on('click', '.product-item', (e) => {
        e.preventDefault();
        
        var id = $(e.target).closest('tr').attr('productId');
        
        $.ajax({
            url: './backend/product-single.php?id=' + id,
            type: 'GET',
            success: function(response) {
                let producto = typeof response === 'string' ? JSON.parse(response) : response;
                
                // Llenar el formulario con los campos individuales
                $('#productId').val(producto.id);
                $('#name').val(producto.nombre);
                $('#precio').val(producto.precio);
                $('#unidades').val(producto.unidades);
                $('#modelo').val(producto.modelo);
                $('#marca').val(producto.marca);
                $('#detalles').val(producto.detalles);
                $('#imagen').val(producto.imagen);
                
                // Limpiar estados de validación
                $('small[id$="-status"]').text('').removeClass('text-danger text-success text-muted');
                
                // Marcar nombre como válido (porque ya existe)
                nombreValido = true;
                
                // Cambiar el texto del botón a "Modificar Producto"
                $('button.btn-primary').text("Modificar Producto");
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