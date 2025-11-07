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

        // Construir el JSON desde los campos del formulario
        var finalJSON = {
            precio: parseFloat($('#precio').val()),
            unidades: parseInt($('#unidades').val()),
            modelo: $('#modelo').val().trim(),
            marca: $('#marca').val().trim(),
            detalles: $('#detalles').val().trim(),
            imagen: $('#imagen').val().trim()
        };
        
        // VALIDACIÓN: Verificar que el nombre no esté vacío
        var nombre = $('#name').val().trim();
        if (nombre === '') {
            alert('ERROR: El nombre del producto es obligatorio');
            return false;
        }
        
        // VALIDACIÓN: Verificar campos obligatorios
        if (!finalJSON.marca || finalJSON.marca === '') {
            alert('ERROR: La marca del producto es obligatoria');
            return false;
        }
        
        if (!finalJSON.modelo || finalJSON.modelo === '') {
            alert('ERROR: El modelo del producto es obligatorio');
            return false;
        }
        
        if (!finalJSON.precio || finalJSON.precio <= 0) {
            alert('ERROR: El precio debe ser mayor a 0');
            return false;
        }
        
        if (isNaN(finalJSON.unidades) || finalJSON.unidades < 0) {
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

                // Limpiar el formulario
                $('#product-form').trigger('reset');
                $('#productId').val('');
                
                // 3. Cambiar botón de vuelta a "Agregar Producto"
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
        
        var id = $(this).closest('tr').attr('productId');
        
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
                
                // 1 y 2. Cambiar el texto del botón a "Modificar Producto"
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