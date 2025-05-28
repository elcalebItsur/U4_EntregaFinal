function marcarInput(input, valido) {
    if (valido) {
        input.classList.remove('input-invalido');
        input.classList.add('input-valido');
    } else {
        input.classList.remove('input-valido');
        input.classList.add('input-invalido');
    }
}

// Validación en tiempo real de los campos del formulario de registro
const campos = [
    document.getElementById('nombre'),
    document.getElementById('email'),
    document.getElementById('password'),
    document.getElementById('telefono'),
    document.getElementById('fecha_nacimiento'),
    document.getElementById('tipo'),
    document.getElementById('nombre_tienda'),
    document.getElementById('rfc'),
    document.getElementById('direccion')
];

function validarCampo(campo) {
    let valido = true;
    switch (campo.id) {
        case 'nombre':
            valido = campo.value.trim().length > 0;
            break;
        case 'email':
            valido = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(campo.value.trim());
            break;
        case 'password':
            valido = campo.value.trim().length >= 6;
            break;
        case 'telefono':
            valido = /^[0-9]{10,15}$/.test(campo.value.trim());
            break;
        case 'fecha_nacimiento':
            valido = campo.value !== '';
            break;
        case 'tipo':
            valido = campo.value === 'Comprador' || campo.value === 'Vendedor';
            break;
        case 'nombre_tienda':
            if (document.getElementById('tipo').value === 'Vendedor') {
                valido = campo.value.trim().length > 0;
            } else {
                valido = true;
            }
            break;
        case 'rfc':
            if (document.getElementById('tipo').value === 'Vendedor') {
                valido = campo.value.trim().length > 0;
            } else {
                valido = true;
            }
            break;
        case 'direccion':
            if (document.getElementById('tipo').value === 'Comprador') {
                valido = campo.value.trim().length > 0;
            } else {
                valido = true;
            }
            break;
        default:
            valido = true;
    }
    campo.classList.remove('input-invalido', 'input-valido');
    if (campo.value.trim() === '' && campo.required) {
        campo.classList.remove('input-valido');
        campo.classList.remove('input-invalido');
    } else if (valido) {
        campo.classList.add('input-valido');
        campo.classList.remove('input-invalido');
    } else {
        campo.classList.add('input-invalido');
        campo.classList.remove('input-valido');
    }
    return valido;
}

campos.forEach(campo => {
    if (campo) {
        campo.addEventListener('input', function() {
            validarCampo(campo);
        });
        campo.addEventListener('blur', function() {
            validarCampo(campo);
        });
    }
});

function validarRegistro(e) {
    let valido = true;
    campos.forEach(campo => {
        if (campo && !validarCampo(campo)) {
            valido = false;
        }
    });
    if (!valido) {
        e.preventDefault();
        return false;
    }
    return true;
}

// Mostrar u ocultar campos según el tipo de usuario seleccionado
function mostrarCamposVendedor() {
    var tipo = document.getElementById('tipo').value;
    document.getElementById('campos-vendedor').style.display = tipo === 'Vendedor' ? 'block' : 'none';
    document.getElementById('campos-comprador').style.display = tipo === 'Comprador' ? 'block' : 'none';
}
document.getElementById('tipo').addEventListener('change', mostrarCamposVendedor);
window.onload = mostrarCamposVendedor;
