// validación
function camposValidar(formulario) {
    var dataArray = {};
    var error     = false;

    var json = {};

    //console.log($('#formulario').find("[data-validate='true']"));

    $('#'+formulario).find("[data-validate='true']").each(function() {
        //var input = $(':input', this);
        var input = $(this);

        var idElemento = input.attr('id'),
            valorElemento = $.trim(input.val());

        if (typeof idElemento !== 'undefined') {
            if (input.data('required')) {
                if ( input.data('text') ) {
                    valorElemento.toLowerCase();
                }

                if (!validarCampo(input.data('validation'), valorElemento)) {
                    $(this).parents('.form-group').first().addClass('has-error');
                    //showError('Error', 'Ingrese todos los campos en rojo para guardar.', true);
                    error = true;
                } else {
                    $(this).parents('.form-group').first().removeClass('has-error');
                }
            } else {
                if (valorElemento.length > 0 && !input.is(':checkbox')) {
                    $(this).parents('.form-group').first().removeClass('has-error');
                    $('.error-space').empty();

                    if (!validarCampo(input.data('validation'), valorElemento)) {
                        $(this).parents('.form-group').first().addClass('has-error');
                        error = true;
                    }
                }

                if (input.is(':checkbox')) {
                    valorElemento = 0;
                    if (input.is(':checked')) {
                        valorElemento = 1;
                    }
                }
            }

            dataArray[idElemento] = valorElemento;
        }
    });

    json['error'] = error;
    json['datos'] = dataArray;

    return JSON.stringify(json);
}

function validarCampo(tipo, valor) {
    // primero validar que venga algun dato
    if (valor.length === 0) {
        return false;
    }

    switch (tipo) {
        case 'text':
            return true;
            break;
        case 'numbers':
            if(!/\D/.test(valor)) {
                return true;
            }
            break;
        case 'rut':
            if ( validarRut(valor) ) {
                return true;
            }
            break;
        case 'mail':
            if ( isValidEmailAddress(valor) ) {
                return true;
            }
            break;
        case 'phone': // pendiente
            var res = valor.replace(/_/g, '');

            if (res.length <= 9) {
                return true;
            }
            break;
        case 'confirm-password':
            var pass = $('#password').val();
            if ( pass === valor ) {
                return true;
            }
            break;
        case 'float': // numeros con decimales
            if ($.isNumeric(valor)) {
                return true;
            }
            break;
        case 'url':
            //regular expression for URL
            var pattern = /^(http|https)?:\/\/[a-zA-Z0-9-.]+\.[a-z]{2,4}/;
            return !!pattern.test(valor);
            break;
        case 'pass':
            if (checkStrength(valor)) {
                return true;
            }
            break;
        default:
            return false;

    }
}

function validarRut(rut) {
    rut = rut.replace(/-/g, '');
    rut = rut.replace(/\./g, '');
    rut = rut.replace(/,/g, '');

    if (rut.length < 8 ) {
        //showError('Error', 'Ingrese el rut completo', true);
        return false;
    }

    if ( !/^[0-9]+[0-9kK]{1}$/.test(rut) ) {
        return false;
    }

    var invertido = rut.split("").reverse().join("").toUpperCase();

    // separar el digito verificador en una nueva cadena
    var digitoVerificador = invertido.substr(0,1);
    var cadena            = invertido.substr(1);

    var suma       = 0,
        validacion = 2;

    for (var i = cadena.length -1; i >= 0; i--)
    {
        suma = suma + rut.charAt(i) * validacion;
        if (validacion == 7)
            validacion = 2;
        else
            validacion++;
    }

    var resto      = suma%11;
    var diferencia = 11-resto;
    var digito = diferencia;

    switch ( diferencia ) {
        case 10:
            digito = 'K';
            break;
        case 11:
            digito = 0;
    }

    if ( digito != digitoVerificador ) {
        return false;
    }

    return true;
}

function formatRut(rut, campo) {
    rut = rut.replace(/\-/g, '');
    rut = rut.replace(/\./g, '');
    rut = rut.replace(/\,/g, '');

    if ( rut != '' ) {

        var invertido = rut.split("").reverse().join("").toUpperCase(),
            rutInvertido = invertido.substr(0,1)+'-'+invertido.substr(1,3)+'.'+invertido.substr(4,3)+'.'+invertido.substr(7);

        rut = rutInvertido.split("").reverse().join("").toUpperCase();

        $('#'+campo).val(rut);
    }
}

function isValidEmailAddress(emailAddress) {
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;

    return pattern.test(emailAddress);
}

function numbersonly(myfield, e, dec) {
    var key, keychar;

    if (window.event) {
        key = window.event.keyCode;
    } else if (e) {
        key = e.which;
    } else {
        return true;
    }

    keychar = String.fromCharCode(key);

    if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27) || (key == 46)) { // control keys
        return true;
    } else if ((("0123456789").indexOf(keychar) > -1)) { // numbers
        return true;
    } else if (dec && (keychar == ".")) { // decimal point jump
        myfield.form.elements[dec].focus();
        return false;
    } else {
        return false;
    }
}

function checkStrength(password){
    //initial strength
    var strength = 0;

    //if the password length is less than 6, return message.
    if (password.length < 6) {
        return false;
    }

    //length is ok, lets continue.
    // if length is 8 characters or more, increase strength value
    if (password.length > 7) strength += 1;

    //if password contains both lower and uppercase characters, increase strength value
    if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) strength += 1;

    //if it has numbers and characters, increase strength value
    if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) strength += 1;

    //if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

    //if it has two special characters, increase strength value
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1;

    //now we have calculated strength value, we can return messages
    console.log(strength);
    //if value is less than 2
    if (strength <=3) {
        return false;
    } else {
        return true;
    }
}

