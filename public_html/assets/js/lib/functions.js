'use strict';

function debounce(func, wait, immediate) {

    var timeout;
    return function (args) {
        const context = this;
        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };

        const callnow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callnow) func.apply(context, args);
    }
}

/**
 * Fixar o header no topo
 */
function fixar_menu() {

    if ($('body').find('.navbar').length > 0) {

        var offset = $('.navbar').offset().top;
        var menu = $('#ha-header');

        menu.addClass('ha-header-hide');

        $(document).on('scroll', function () {

            if ($(window).scrollTop() > 0)
                menu.removeClass('ha-header-hide');
            else
                menu.addClass('ha-header-hide');

        });

    }

}

/**
 * Função para remover a div preloader.
 */
function preloader(type, reload) {

    $('.material-tooltip').remove();

    $('form').each(function () {
        $(this).find('input[type="text"]').attr('autocapitalize', 'words');
    });

    var reload = typeof reload !== 'undefined' ? reload : true;

    switch (type) {

        case 'in':

            // if (reload) {
            //     animate($('#main'), 'fadeIn', function (e) {
            //         e.removeClass();
            //     });
            // }

            $('body').removeClass('loaded');

            break;

        case 'out':

            // if (reload) {
            //     // Movimenta a barra de rolagem para o topo da página
            //     $('html,body').animate(
            //     {
            //         scrollTop : 0
            //     },
            //     {
            //         duration : 400
            //     });
            // }

            $('body').addClass('loaded');

            break;

    }

}


/**
 * Atualiza o preenchimento do textarea com o editor CKEditor.
 */

function postForm() {

    var items = ['.editor', '.redactor'];

    $(items.toString()).each(function () {

        var html = $(this).children('.ql-editor').html();
        var textarea = '<textarea></textarea>';
        $(this).find('textarea').remove();
        $(this).append($(textarea).hide().attr('name', $(this).attr('name')).val(html));

    })

    // for (var instanceName in CKEDITOR.instances)
    // {
    // CKEDITOR.instances[instanceName].updateElement();
    // }
}


/**
 * Função para atualização de elementos via Ajax
 */
function execute_data(data, form) {

    var section = typeof data.section !== '' || typeof data.section !== 'undefined' ? $(data.section) : $('body');
    var acao = typeof data.acao !== '' || typeof data.acao !== 'undefined' ? data.acao : '';
    var id = typeof data.id !== '' || typeof data.id !== 'undefined' ? data.id : '';
    // var ultima_edicao   = moment().format('YYYY-MM-DD HH:mm:ss');
    var ultima_edicao = null;

    $(form).find('input:hidden[name="acao"]').val(acao);
    $(form).find('input:hidden[name="id"]').val(id);
    $(form).find('input:hidden[name="ultima_edicao"]').val(ultima_edicao);

    $.each(data.fields, function (a, attribute) {

        var element = attribute.element;
        var type = attribute.type;
        var name = attribute.name;
        var value = attribute.value;
        var id = attribute.id;
        var classes = attribute.classes;
        var remove_class = attribute.remove_class;
        var checked = attribute.checked;
        var disabled = attribute.disabled;
        var selected = attribute.selected;
        var label = attribute.label;
        var readonly = attribute.readonly;
        var title = attribute.title;

        /**
         * Manipulação dos elementos de {texto} e {select}
         */
        switch (element) {


            case 'input':

                if (type != 'file')
                    section.find(element + ':' + type + '[name=' + name + ']').val(value);
                else
                    section.find(element + ':' + type + '[name=' + name + ']').attr('data-value', value).Files();

                break;

            case 'textarea':
                section.find(element + '[name=' + name + ']').val(value);
                break;

            case 'select':

                if (section.find(element + '[name=' + name + ']').find('option').length) {
                    section.find(element + '[name=' + name + ']').find('option').each(function () {
                        if ($(this).val() !== '') {
                            if ($(this).val() == value) {
                                $(this).parent(element + '[name=' + name + ']').val(value).trigger('change');
                            }
                        }
                    });
                }
                else {

                    section.find(element + '[name="' + name + '[]"]').find('option').remove();
                    var opt = value.split(',');

                    for (var i in opt) {

                        var option = new Option(opt[i], opt[i], true, true);

                        section.find(element + '[name="' + name + '[]"]').append(option).trigger('change');

                    }

                }

                break;

            default:
                break;

        }

        /**
         * Manipulação dos inputs {checkbox} e {radio}
         */
        switch (type) {

            case 'checkbox':

                var $class = section.find(':checkbox').hasClass('icheckbox');
                var $switch = section.find(':checkbox[data-toggle]').data('toggle');

                if ($class) {
                    if (checked)
                        section.find(':checkbox[name=' + name + ']').attr('checked', true).iCheck('check');
                    else
                        section.find(':checkbox[name=' + name + ']').attr('checked', false).iCheck('uncheck');
                }
                else if (typeof $switch !== 'undefined' && $switch == 'switch') {
                    if (checked)
                        section.find(':checkbox[name=' + name + ']').attr('checked', true).prop('checked', true).change();
                    else
                        section.find(':checkbox[name=' + name + ']').attr('checked', false).prop('checked', false).change();
                }

                break;

            case 'radio':

                if (checked)
                    section.find(':radio[name=' + name + ']').attr('checked', true).iCheck('check');
                else
                    section.find(':radio[name=' + name + ']').attr('checked', false).iCheck('uncheck');

                break;

        }

        /**
         * Manipulação de elementos de {classes} e {id}
         */
        if (classes != null) {

            var classe = '';
            var classes_removidas = '';
            var str = classes.split(/\s+/);
            var remover = remove_class != null ? remove_class.split(/\s+/) : null;
            for (var i in str) {
                classe += '.' + str[i];
                if (remover != null) {
                    for (var j in remover) {
                        classes_removidas += remover[j] + ' ';
                        $(classe).removeClass(classes_removidas);
                    }
                }
            }
        }

        if (id != null && classe != null && value != null)
            $('#' + id + classe).val(value).find('span').text(value);
        else if (id != null && classe == null && value != null) {
            if (type != 'file') {
                $('#' + id).val(value)
                $('#' + id).text(value);
            }
        }
        else if (id == null && classe != null && value != null) {
            $(classe).val(value).find('span').text(value);
            $(classe).text(value).removeClass('muted');
        }
        else
            $(classe).val(value).find('span').addClass(classes_removidas).text(label);

        if (disabled)
            section.find(element + '[name=' + name + ']').attr('disabled', true).parent().addClass('disabled');
        else
            section.find(element + '[name=' + name + ']').attr('disabled', false).parent().removeClass('disabled');

    });

}

/**
 * Função redirector(); Esta função modifica a página após atualização de dados.
 *
 * @param {type} [OBRIGATÓRIO]
 * 			Referencia a forma como a página será atualizada
 * @param {url} [OPCIONAL]
 * 			Informa para qual url irá redirecionar
 */
function load_page(type, url) {

    var uri = new URI();

    switch (type) {
        // se redirect for true, apenas chame a função request_pag() para
        // recarregar o path
        case true:
            if (uri.Setup(is_ajax) && uri.Request(url, false))
                history.pushState(null, null, url);
            else
                location.href = url;
            return this;
            break;
        // se redirect for false, não fazer nada
        case false:
            // $('.modal').modal('hide');
            $('.modal').modal('close');
            // console.log('teste');
            return this;
            break;
        // esta condição, se existir uma URL, serve para atualizar as
        // DataTables. Se a URL não existir, atualiza a página
        case 'refresh':

            var $params = [];
            var id = $('table').parents('.tab-pane').attr('id');

            if (typeof id !== 'undefined')
                $params = { 'tab': id };

            if ($('table.datatable').length)
                make_datatable(url, $params);
            // else
            // execute_data(data);

            // verifica se existe algum parâmetro para ocultar a modal
            var hide_modal = $('[data-modal-hide="true"]').length;

            //if( hide_modal )
            $('.modal').modal('close');

            // request_pag(location.pathname);
            break;
        // esta condição redireciona para outra página após receber um
        // retorno caso exista uma URL válida.
        case 'redirect':
            console.log(url);
            $('.modal').modal('close');
            location.href = url;
            break;
        // caso a variável redirect seja um valor indefinido ou reload,
        // atualiza a página
        case 'reload':
            $('.modal').modal('close');
            location.reload();
            break;
        case 'reset':
            if (typeof url !== undefined && (url !== '' || url !== null))
                $('form').reset(url);
        default:
            return null;
            break;
    } // finaliza switch

    // Reiniciar a barra de pesquisas sempre que enviar o formulário
    Dashboard.searchs();

}

/**
 * função para bloquear elementos (terminar de carregar)
 */
function blockUI(el) {
    $(el).block(
        {
            message: '<div class="loading-animator"></div>',
            css:
            {
                border: 'none',
                padding: '2px',
                backgroundColor: 'none'
            },
            overlayCSS:
            {
                backgroundColor: '#fff',
                opacity: 0.3,
                cursor: 'wait'
            }
        });
}

/**
 * função para desbloquear elementos (terminar de carregar)
 */
function unblockUI(el) {
    setTimeout(function () {
        $(el).unblock();
    }, 1000);
}

/**
 * Adiciona uma animação em uma barra de progresso
 */
function animation_progress() {
    $('.animate-number').each(function () {
        $(this).html(0).animateNumbers($(this).attr("data-value"), true, parseInt($(this).attr("data-animation-duration"), 10));
    });
    $('.animate-progress-bar').each(function () {
        $(this).css('width', $(this).attr("data-percentage"));
    });
}

/**
 * Função para exibir as mensagens de notificações no sistema:
 */
var notificacao = function (data, form) {

    countCheckState('none');
    var self = typeof form !== 'undefined' ? form : $('form');

    self.find('.input-field-container')
        .removeClass('error')
        .find('.helper-text').html('')
        .parents('.input-field-container')
        .find('.sufix').remove();

    if (typeof data !== 'undefined' && typeof data !== '') {

        // var array = JSON.parse(JSON.stringify(data));
        var array = data;
        var type = typeof array.type !== 'undefined' ? array.type : '';
        var msg = typeof array.msg !== 'undefined' ? array.msg : '';
        var style = typeof array.style !== 'undefined' ? array.style : true;
        var url = typeof array.url !== 'undefined' ? array.url : false;
        var redirect = typeof array.redirect !== 'undefined' && url ? array.redirect : null;

        if (typeof msg === 'string') {
            if (msg !== '' && msg !== null) {

                $('#toast-container').find('.toast').remove();

                M.toast(
                    {
                        html: msg + '<button class="btn btn-floating btn-small transparent toast-action waves-effect waves-light"><i class="material-icons blue-text text-lighten-1">close</i></button>',
                        displayLength: 10000
                    });

                $('.toast-action').on('click', function (e) {
                    e.preventDefault();
                    M.Toast.dismissAll();
                });

            }
        }
        else if (typeof msg === 'array') {


            for (var i in msg) {
                jQuery.each(msg[i], function (name, info) {

                    var error = '<span>' + info + '</span>';

                    self.find('[name="' + name + '"]')
                        .parents('.input-field-container')
                        .addClass('error ' + name)
                        .find('.helper-text').html(error);

                });
            }

        }
        else if (typeof msg === 'object') {
            jQuery.each(msg, function (name, info) {

                var error = '<span>' + info + '</span>';

                self.find('[name="' + name + '"]')
                    .parent().prepend($('<i/>').addClass('material-icons sufix').text(type))
                    .parents('.input-field-container')
                    .addClass('error ' + name)
                    .find('.helper-text').html(error);

            });
        }

        if (type === 'success')
            load_page(redirect, url);

    }

    var tab = /^\#tab\_$/;

    self.find('div*[id]').each(function () {
        var reg = new RegExp(/^tab\_[a-z]+/);
        var id = typeof $(this).attr('id') !== 'undefined' ? $(this).attr('id') : null;

        if (id !== null && reg.test(id)) {
            $(this).parents(self).find('a[href="#' + id + '"]')
                .removeClass('error pink lighten-4').find('i').remove();
            if ($(this).find('.error').length) {
                var width = $('a[href="#' + id + '"]').parent().innerWidth();
                $(this).parents(self).find('a[href="#' + id + '"]').css(
                    {
                        'position': 'relative',
                        'overflow': 'hidden'
                    })
                    .addClass('error pink lighten-4').append($('<i/>').addClass('material-icons right').css(
                        {
                            'color': 'rgb(255, 64, 129)',
                            'margin-top': '10px',
                            'position': 'absolute',
                            'right': 'calc(100% - ' + width + 'px)',
                            'top': '0',
                        }).text('error'));
            } else {
                $(this).parents(self).find('a[href="#' + id + '"]').removeClass('error pink lighten-4').find('i').remove();
            }

        }
    })



};

/**
 * Função para substituir o alerta padrão do navegador.
 * @return {}
 */
var Alerta = function (data, action, form) {

    var title = data.title;
    var type = data.type;
    var text = data.msg;
    var icon = data.type;
    var url = data.url;
    var fields = data.fields;

    swal({
        title: title,
        text: text,
        icon: icon,
        buttons: {
            cancel: 'Não',
            ok: 'Sim'
        },
        dangerMode: true,
        catch: {
            url: url,
            action: action,
            fields: fields,
        }
    }).then((willDelete) => {

        if (action == 'excluir' && willDelete) {

            $.ajax(
                {
                    type: 'post',
                    dataType: 'json',
                    url: url,
                    data:
                    {
                        id: fields,
                        acao: action
                    },
                    success: function (data) {

                        if (data.type === 'success') {

                            var count = 0;
                            var i;

                            for (i = 0; i < data.fields.length; i++) {
                                $('tr#' + data.fields[i]).remove();
                            }

                            countCheckState('none');

                            setTimeout(function () {
                                load_page(data);
                                swal('Pedido cancelado!', {
                                    icon: "success",
                                });
                            }, 200);

                        }

                    },

                    error: function (request, status, error) {
                        alert((request.status !== 200 ? 'Houve um erro ao tentar prosseguir: [' + request.status + '] - ' + request.statusText : '') + ' Não é possível excluir um ou mais registros.');
                        swal(data.msg, {
                            icon: "success",
                        });
                        countCheckState('none');
                    }

                });

        } else {
            // swal("Nenhuma alteração realizada!");
            countCheckState('none');
        }
    });

}

function remover($del) {

    // console.log($del);

    $.ajax(
        {
            type: 'post',
            dataType: 'json',
            url: params.url,
            data:
            {
                id: params.fields,
                acao: params.action
            },
            success: function (data) {

                if (data.type === 'success') {

                    var count = 0;
                    var i;

                    for (i = 0; i < data.fields.length; i++) {
                        $('tr#' + data.fields[i]).remove();
                    }


                }

                countCheckState('none');
                params.modal.find(params.informacao).slideUp(200);

                setTimeout(function () {
                    load_page(data);
                    notificacao(data);
                }, 200);

            },

            error: function (request, status, error) {
                alert((request.status !== 200 ? 'Houve um erro ao tentar prosseguir: [' + request.status + '] - ' + request.statusText : '') + ' Não é possível excluir um ou mais registros.');
                countCheckState('none');
                params.modal.find(params.informacao).slideUp(200);
            }

        });

}

function is_numeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 * Função para aplicar máscaras nos inputs do navegador.
 */
var App =
{
    aplicarMascaras: function () {

        var is_num = $('.is_num');
        var is_cpf = $('.is_cpf');
        var is_date = $('.is_date');
        var is_cnpj = $('.is_cnpj');
        var is_cpf_cnpj = $('.is_cpf_cnpj');
        var is_phone = $('.is_phone');
        var is_celular = $('.is_celular');
        var is_decimal = $('.is_decimal');
        var is_time = $('.is_time');
        var is_cep = $('.is_cep');

        is_num.each(function () {
            var $class = typeof $(this).attr('data-align') !== 'undefined' && $(this).attr('data-align') != '' ? $(this).attr('data-align') : 'right';
            var $placeholder = typeof $(this).attr('placeholder') !== 'undefined' && $(this).attr('placeholder') != '' ? $(this).attr('placeholder') : '0';
            var $maxlength = typeof $(this).attr('maxlength') !== 'undefined' && $(this).attr('maxlength') != '' ? $(this).attr('maxlength') : 9;

            var input = this;

            $(window).load(function (e) {
                MascaraUtils.mascara(input, MascaraUtils.NUMERICO);
            });

            $(this).keyup(function () {
                MascaraUtils.mascara(this, MascaraUtils.NUMERICO);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.NUMERICO);
            }).attr('maxlength', $maxlength).attr('placeholder', $placeholder).addClass('text-' + $class);
            if ($(this).val() !== '')
                MascaraUtils.mascara(this, MascaraUtils.NUMERICO);
        });

        is_cpf.each(function () {
            var input = this;

            $(window).load(function (e) {
                MascaraUtils.mascara(input, MascaraUtils.CPF);
            });

            $(this).keyup(function () {
                MascaraUtils.mascara(this, MascaraUtils.CPF);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.CPF);
            }).attr('maxlength', 14).attr('placeholder', '000.000.000-00');


        });

        is_cnpj.each(function () {

            var input = this;

            //          $(window).load(function(e){
            MascaraUtils.mascara(input, MascaraUtils.CNPJ);
            //          });

            $(this).keyup(function () {
                MascaraUtils.mascara(this, MascaraUtils.CNPJ);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.CNPJ);
            }).attr('maxlength', 18).attr('placeholder', '00.000.000/0000-00');
            if ($(this).val() !== '')
                MascaraUtils.mascara(this, MascaraUtils.CNPJ);
        });

        is_cpf_cnpj.each(function () {

            var input = this;

            //          $(window).load(function(e){
            MascaraUtils.mascara(input, MascaraUtils.CPF_CNPJ);
            //          });

            $(this).keyup(function () {
                MascaraUtils.mascara(this, MascaraUtils.CPF_CNPJ);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.CPF_CNPJ);
            }).attr('maxlength', 18).attr('placeholder', 'CPF ou CNPJ');
            if ($(this).val() !== '')
                MascaraUtils.mascara(this, MascaraUtils.CPF_CNPJ);
        });

        is_phone.each(function () {


            var input = this;
            var $placeholder = typeof $(this).attr('placeholder') !== 'undefined' ? $(this).attr('placeholder') : '(XX) XXXX.XXXX';

            //          $(window).load(function(e){
            MascaraUtils.mascara(input, MascaraUtils.TELEFONE);
            //          });

            $(this).keyup(function () {
                MascaraUtils.mascara(this, MascaraUtils.TELEFONE);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.TELEFONE);
            }).attr('maxlength', 15).attr('placeholder', $placeholder);

            if ($placeholder == '' && $(this).val() === '')
                $(this).removeAttr('placeholder').parent().find('label').removeClass('active');

            if ($(this).val() !== '')
                MascaraUtils.mascara(this, MascaraUtils.TELEFONE);
        });

        is_celular.each(function () {

            var input = this;

            //          $(window).load(function(e){
            MascaraUtils.mascara(input, MascaraUtils.CELULAR);
            //          });

            $(this).keyup(function () {
                MascaraUtils.mascara(this, MascaraUtils.CELULAR);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.CELULAR);
            }).attr('maxlength', 17).attr('placeholder', '(XX) X XXXX.XXXX');
            if ($(this).val() !== '')
                MascaraUtils.mascara(this, MascaraUtils.CELULAR);
        });


        is_decimal.each(function () {

            var exp = /^0/;
            var $val = typeof $(this).attr('data-value') !== 'undefined' && $(this).attr('data-value') != null ? $(this).attr('data-value') : '0,00';
            var $class = typeof $(this).attr('data-align') !== 'undefined' && $(this).attr('data-align') != '' ? $(this).attr('data-align') : 'right';

            $(this).val($val);

            var input = this;
            //
            //          $(window).load(function(e){
            MascaraUtils.mascara(input, MascaraUtils.DECIMAL);
            //          });

            $(this).on('keydown', function (e) {

                if ($(this).val() == '' || $(this).val() == '0,00' || $(this).val() == '0') {
                    if (e.keyCode == 8) {
                        $(this).val('0,00');
                        e.preventDefault();
                        return false;
                    }
                }

                if (is_numeric(e.key))
                    if (exp.test(this.value))
                        this.value = ('0' + this.value).slice(-2);

                MascaraUtils.mascara(this, MascaraUtils.DECIMAL);

            }).on('keyup', function (e) {

                if ($(this).val() == '' || $(this).val() == '0,00' || $(this).val() == '0')
                    if (e.keyCode == 8) {
                        $(this).val('0,00');
                        e.preventDefault();
                        return false;
                    }

            }).attr('maxlength', (typeof $(this).attr('maxlength') !== 'undefined' ? $(this).attr('maxlength') : 9)).attr('placeholder', '0,00').addClass('text-' + $class).focus(function () {
                // if ($(this).val().length == 0 || $(this).val() == 0)
                // $(this).val('0,00');
            }).on('blur', function () {
                if ($(this).val().length == 0 || $(this).val() == 0)
                    $(this).val('0,00');

            });

        });

        is_date.each(function () {

            var input = this;
            var placeholder = typeof $(this).attr('placeholder') !== 'undefined' ? $(this).attr('placeholder') : 'dd/mm/aaaa';

            // insere o placeholder caso haja algum
            if (placeholder != '')
                $(this).attr('placeholder', placeholder).parent('.input-field').find('label').addClass('active');
            else
                $(this).removeAttr('placeholder');

            $(document).ready(function (e) {
                MascaraUtils.mascara(input, MascaraUtils.DATA);
            });

            $(this).on('keyup', function () {
                MascaraUtils.mascara(this, MascaraUtils.DATA);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.DATA);
            }).attr('maxlength', 10).datepicker(
                {
                    format: 'dd/mm/yyyy',
                    startView: 2,
                    autoClose: true,
                    // todayHightlight : true,
                    // endYear      : 'today',
                    // language        : 'pt-BR',
                    // assumeNearbyYear: true,
                    // changeYear      : true,
                    minDate: ($(this).data('start') !== 'undefined' ? $(this).data('start') : null),
                    maxDate: ($(this).data('end') !== 'undefined' ? $(this).data('end') : null),
                    // yearRange       : ($(this).attr('data-range') !== 'undefined' ? $(this).attr('data-range') : 'c-50:c+50' ),
                    months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'], // Names of months for drop-down and formatting
                    monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'], // For formatting
                    weekdays: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'], // For formatting
                    weekdaysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'], // For formatting
                    weekdaysAbbrev: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S'], // Column headings for days starting at Sunday               
                });

            if ($(this).val() !== '')
                MascaraUtils.mascara(this, MascaraUtils.DATA);

        });

        is_time.each(function () {

            var mask = null;

            var input = this;

            if ($(this).hasClass('hora'))
                mask = MascaraUtils.HORA;
            else if ($(this).hasClass('minuto'))
                mask = MascaraUtils.MINUTO;
            else if ($(this).hasClass('segundo'))
                mask = MascaraUtils.SEGUNDO;
            else
                mask = MascaraUtils.TIME;

            //          $(window).load(function(e){
            MascaraUtils.mascara(input, mask);
            //          });

            $(this).on('focus', function () {

                if ($(this).val() == '')
                    $('00' + $(this).val()).slice(-1);

                MascaraUtils.mascara(this, mask);

                $(this).parent().addClass('focus');

            }).on('blur', function () {
                $(this).parent().removeClass('focus');
            });

            $(this).on('keyup', function (e) {

                MascaraUtils.mascara(this, mask);

            }).on('keypress', function () {

                MascaraUtils.mascara(this, mask);

            }).attr('maxlength', 3).attr('placeholder', '00').css({ 'color': '#000' }).on('keydown', function (e) {
                if (e.keyCode == 13 && $(this).val() != '') {
                    e.preventDefault();
                    $(this).blur();
                    $(this).next().next('input[type="text"]').focus();
                }
            });

            if ($(this).val() > $(this).attr('maxlength') || $(this).val() == '') {
                $(this).val('00');
                $('00' + $(this).val()).slice(-1);
            }

            if ($(this).val() !== '')
                MascaraUtils.mascara(this, mask);

        });

        is_cep.each(function () {

            var input = this;

            //          $(window).load(function(e){
            MascaraUtils.mascara(input, MascaraUtils.CEP);
            //          });

            $(this).keyup(function () {
                MascaraUtils.mascara(this, MascaraUtils.CEP);
            }).on('keypress', function () {
                MascaraUtils.mascara(this, MascaraUtils.CEP);
            }).attr('maxlength', 10).attr('placeholder', 'XXXXX-XXX');
            if ($(this).val() !== '')
                MascaraUtils.mascara(this, MascaraUtils.CEP);
        });

    }

};

var obj,
    fn;

var MascaraUtils =
{
    NUMERICO: 1,
    CPF: 2,
    CNPJ: 3,
    CPF_CNPJ: 4,
    TELEFONE: 5,
    CELULAR: 6,
    DECIMAL: 7,
    DATA: 8,
    TIME: 9,
    HORA: 10,
    MINUTO: 11,
    SEGUNDO: 12,
    CEP: 13,
    fn: null,
    obj: null,
    mascara: function (o, f) {

        obj = o;

        switch (f) {
            case this.NUMERICO:
                fn = this.Numerico;
                break;
            case this.CPF:
                fn = this.Cpf;
                break;
            case this.CNPJ:
                fn = this.Cnpj;
                break;
            case this.CPF_CNPJ:
                fn = this.Cpf_cnpj;
                break;
            case this.TELEFONE:
                fn = this.Telefone;
                break;
            case this.CELULAR:
                fn = this.Celular;
                break;
            case this.DECIMAL:
                fn = this.Decimal;
                break;
            case this.DATA:
                fn = this.Data;
                break;
            case this.TIME:
                fn = this.Time;
                break;
            case this.HORA:
                fn = this.Hora;
                break;
            case this.MINUTO:
                fn = this.Minuto;
                break;
            case this.SEGUNDO:
                fn = this.Segundo;
                break;
            case this.CEP:
                fn = this.Cep;
                break;
        }
        setTimeout('MascaraUtils.exec()', 1);
    },
    exec: function () {
        obj.value = fn(obj.value);
    },
    Numerico: function (v) {
        return v.replace(/\D/g, '');
    },
    Telefone: function (v) {
        v = v.replace(/\D/g, '');
        v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
        v = v.replace(/(\d{3,4})(\d)/, '$1.$2');
        if (v.length > 14 && v.length <= 16) {
            v = v.replace(/\D/g, '');
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d{1})(\d{4})/, '$1 $2');
            v = v.replace(/(\d{3,4})(\d{4})/, '$1.$2');
        }
        return v;
    },
    Cpf: function (v) {
        v = v.replace(/\D/g, '');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d)/, '$1.$2');
        v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        return v;
    },
    Cpf_cnpj: function (v) {
        if (v.length <= 14) {
            v = v.replace(/\D/g, '');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        }
        else if (v.length > 14 && v.length < 19) {
            v = v.replace(/\D/g, '');
            v = v.replace(/^(\d{2})(\d)/, '$1.$2');
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
            v = v.replace(/(\d{4})(\d)/, '$1-$2');
        }
        return v;
    },
    Decimal: function (v) {
        var splitext = v.split('');
        var revertext = splitext.reverse();
        var v2 = revertext.join('');
        var v2 = v2.replace(/\D/g, '');

        // v2 = new String(Number(v2));
        // var len = v2.length;
        //
        // if ( len <= 1 )
        // v2 = v.replace(/(\d)/, '0,0$1');
        // else if (len > 1 && len == 2)
        // v2 = v.replace(/(\d)/, '0,$1');
        // else if (len >= 3
        // )
        // v2 = v.replace(/(\d{2})$/, ',$1');

        v2 = v2.replace(/(\d{2})(\d)/, '$1,$2');
        v2 = v2.replace(/(\d{3})(\d)/, '$1.$2');
        v2 = v2.replace(/(\d{3})(\d)/, '$1.$2');

        if (v2.length < 3) {
            v2 = v2.replace(/(\d{1})(\d)/, '$1$2,0');
            v2 = v2.replace(/(\d{2})(\d)/, '$10,0');
        }

        // else if ( v2.length < 3 )
        // {
        // v2.replace(/(\d{2})(\d)/, '0,$1');
        // v2.replace(/(\d{2})(\d)/, '0,0$1');
        // }
        // if( len > 4 )
        // {
        // var x = len - 5;
        // var n = new RegExp('(\\d{' + x + '})(\\d)');
        // v2 = v2.replace(n, '$1.$2');
        // }
        splitext = v2.split('');
        revertext = splitext.reverse();
        v2 = revertext.join('');
        return v2;
    },
    Data: function (v) {
        v = v.replace(/\D/g, '');
        v = v.replace(/(\d{2})(\d)/, '$1/$2');
        v = v.replace(/(\d{2})(\d)/, '$1/$2');
        return v;
    },
    Time: function (v) {
        v = v.replace(/\D/g, '');
        v = v.replace(/(\d{2})(\d)/, '$1:$2');
        v = v.replace(/(\d{2})(\d)/, '$1:$2');
        return v;
    },
    Hora: function (v) {

        var exp = /^([0-1][0-9])|([2][0-3])$/;
        var hora = true;

        if (v.length == 2) {
            if (!exp.test(v))
                return v = '00';
        }

        return ('00' + v).slice(-2);

    },
    Minuto: function (v) {
        var exp = /^([0-5][0-9])$/;
        var min = true;

        if (v.length == 2) {
            if (!exp.test(v))
                return v = '00';
        }

        return ('00' + v).slice(-2);

    },
    Segundo: function (v) {
        var exp = /^([0-5][0-9])$/;
        var sec = true;

        if (v.length == 2) {
            if (!exp.test(v))
                return v = '00';
        }

        return ('00' + v).slice(-2);

    },
    Cep: function (v) {
        v = v.replace(/\D/g, '');
        v = v.replace(/^(\d{5})(\d)/, '$1-$2');
        return v;
    },
    Cnpj: function (v) {
        v = v.replace(/\D/g, '');
        v = v.replace(/^(\d{2})(\d)/, '$1.$2');
        v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
        v = v.replace(/(\d{4})(\d)/, '$1-$2');
        return v;
    },
    Celular: function (v) {
        v = v.replace(/\D/g, '');
        v = v.replace(/^(\d\d)(\d)/g, '($1) $2');
        v = v.replace(/(\d)(\d{2})/, '$1 $2');
        v = v.replace(/(\d{4})(\d)/, '$1.$2');
        return v;
    }

};

var Events =
{

    Evento: function (e) {

        $(document).keyup(function (e) {
            key_event.tecla(e.keyCode);
        });

    }

};

/**
 * Eventos para teclado
 */
var key_event =
{

    BACK_NAV: 1,
    fn: null,
    obj: null,
    tecla: function (k) {

        obj = k;

        switch (k) {
            case 37:
                fn = this.Back_nav;
                break;

            case 39:
                fn = this.Next_nav;
                break;
        }

        setTimeout('key_event.exec()', 1);

    },
    exec: function () {
        return fn(obj);
    },
    Back_nav: function (event) {

    },
    Next_nav: function (e) {

    }

};






/**
 * Função para selecionar e desselecionar todos os checkboxes de uma tabela para
 * excluir/alterar.
 */
var countCheckState = function (param) {

    // Variável para contar total de checkboxes
    var countChk = $('.datatable tbody :input:checkbox.trash').length;

    // Variável para contar quantos checkbox existem selecionados
    var checkeds = $(".datatable tbody :input:checkbox.trash:checked").length;

    var indeterminateCheckbox = document.getElementById("check-all");

    console.log(checkeds);

    // Desbloquear botão de excluir (caso este exista) no formulário
    if (typeof param === 'undefined' && checkeds > 0) {
        console.log('===> params => ' + param);
        console.log('===> checks => ' + checkeds);

        if (!$('.btn-group').find('.btn-excluir').is(':visible')) {
            animate($('.btn-group').find('.btn-excluir'), 'flipInY');
            animate($('.btn-group').find('.btn-inserir'), 'flipOutY');
            setTimeout(function () {
                $('.btn-group').find('.btn-excluir').removeClass('hide');
                $('.btn-group').find('.btn-inserir').addClass('hide');
            }, 2);
        }

        if ($('.navbar-main').find('.nav-wrapper').is(':visible')) {
            animate($('.dataTables_length').find('label').show(), 'fadeIn fast', function (e) {
                e.show();
            });
            animate($('.navbar-main').find('.nav-wrapper'), 'fadeOut fast', function (e) {
                e.removeClass('fast').hide();
            });
        }

        $('.dataTables_length').find('label').html((checkeds === 1) ? checkeds + ' item selecionado' : checkeds + ' itens selecionados');

        $('button.btn-excluir').attr('disabled', false);

        if (checkeds === countChk) {
            // $('.datatable thead :input:checkbox').prop('checked', true);
            indeterminateCheckbox.indeterminate = false;
        }
        else if (checkeds < countChk) {
            // $('.datatable thead :input:checkbox').prop('checked', false);
            if (typeof indeterminateCheckbox !== 'undefined' && indeterminateCheckbox !== null)
                indeterminateCheckbox.indeterminate = true;
        }

        $('.datatable thead :input:checkbox.trash').prop('checked', true);

    }
    else {

        if (!$('.btn-group').find('.btn-inserir').is(':visible')) {
            animate($('.btn-group').find('.btn-inserir'), 'flipInY');
            animate($('.btn-group').find('.btn-excluir'), 'flipIntY');
            setTimeout(function () {
                $('.btn-group').find('.btn-inserir').removeClass('hide');
                $('.btn-group').find('.btn-excluir').addClass('hide');
            }, 2);
        }

        if (!$('.navbar-main').find('.nav-wrapper').is(':visible')) {
            animate($('.dataTables_length').find('label'), 'fadeOut fast', function (e) {
                e.hide();
            });
            animate($('.navbar-main').find('.nav-wrapper').show(), 'fadeIn fast', function (e) {
                e.removeClass('fast').show();
            });
        }

        $('.dataTables_length').find('label').html('');
        $('.datatable :input:checkbox.trash:checked').prop('checked', false).parents('tr').removeClass('selected');
        $('button.btn-excluir').attr('disabled', true);

        if (typeof indeterminateCheckbox !== 'undefined' && indeterminateCheckbox !== null)
            indeterminateCheckbox.indeterminate = false;

    }

};

var countChecked = function () {

    var selectAll = $('.datatable').hasClass('responsive-table') ? $('.datatable thead :input:checkbox.trash') : $('.datatable ').parents('.dataTables_scroll').find('.dataTables_scrollHead').find('table thead :input:checkbox.trash');

    var checkbox = $('.datatable tbody :input:checkbox.trash');
    var disabled = checkbox.is(':disabled');

    // Verifica se existem checkbox desabilitados nos registros
    // Se existirem, então remover o atributo 'disabled' do checkbox
    // do cabeçalho da tabela, do contrário, bloquear o checkbox
    if (checkbox.length && !disabled) {
        selectAll.attr('disabled', false);
        selectAll.parents().removeClass('disabled');
    }
    else {
        selectAll.attr('disabled', true);
        selectAll.parents().addClass('disabled');
    }

    // Adicionar o atributo 'checked' no checkbox
    checkbox.on('change', function () {

        // Adicionar a classe selected na tabela para grifar linha selecionada.
        $(this).prop('checked');

        if ($(this).is(':checked'))
            $(this).parents('tr').addClass('selected');
        else
            $(this).parents('tr').removeClass('selected');

        countCheckState();

    });

    // Se o usuário quiser selecionar todos...
    selectAll.on('change', function () {

        var parents = $('.datatable').hasClass('responsive-table') ? $(this).parents('table').find('tbody tr') : $(this).parents('.dataTables_scroll').find('.dataTables_scrollBody').find('tbody tr');

        if (selectAll.is(':checked')) {
            parents.addClass('selected').find(':input:checkbox.trash').prop('checked', true);
        }
        else {
            $(parents).removeClass('selected').find(':input:checkbox.trash:checked').removeClass('selected').prop('checked', false);
        }

        countCheckState();

    });

};


/**
 * Função para converter letras para maiúsculas automatimaticamente
 */
function toupper(str) {

    var valor = str.toUpperCase();

    return valor;

}

function str_to_upper() {

    $('input[type=text], textarea').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

}

/**
 * função alert(); substitui o alert padrão do navegador
 */
function DAR_NOME_A_ESTA_FUNCAO(request) {

    setTimeout(function () {
        $('.blockUI').show();
        $('.blockUI').find('.divcentro').show();
        $('.blockUI').find('.info').html('<i class="fa fa-exclamation-triangle"></i> &nbsp; Erro &nbsp;<i class="fa fa-exclamation-triangle"></i>');
        $('.blockUI').find('.msg').html((request.status !== 200 ? 'Houve um erro ao tentar prossegir: [' + request.status + '] - ' + request.statusText : ''));
    }, 600);

    $('.blockUI').find('input:button').click(function () {
        $('.blockUI').find('.divcentro').hide();
        setTimeout(function () {
            $('.blockUI').hide();
        }, 200);
    });

};

/**
 * Adiciona máscara ao input[type=file] para todo atributo [data-class="file"]
 *
 * @param obj
 */
function load_file(obj) {

    if ($('[data-toggle="da-files"]').length > 0) {

        $('[data-toggle="da-files"]').each(function () {
            $(this).Files();
        })

    }

}

/**
 * Abrir janela modal ao clicar em um link ou linha da tabela '.datatable_modal'
 * @param [Array] $modal
 * @param name      : O nome da janela para título
 * @param action    : Ação que será aplicada ao input[name="action"] que pode
 * ser, por padrão, "salvar" ou "editar"
 * @param href      : Url de destino. Caso seja necessário obter dados de um
 * arquivo json
 * @param id        : id de identificação da tabela ou elemento
 * @param datatype  : Caso o elemento html [tabela] tenha o atributo "data-type",
 * irá obter o seu valor e transformar os dados de acordo com este.
 * @return datatype : [ html, json, xml ]
 */
function modal_editor($modal, msg_bloqueio) {

    var name = $modal.name;
    var action = $modal.action;
    var href = $modal.href;
    var id = $modal.id;
    var title = $modal.title;
    var datatype = typeof $modal.datatype !== 'undefined' ? $modal.datatype : 'json';

    $('#modal-' + name).find('form').remove();

    notificacao();

    var Form = new Forms();

    if (name !== 'location')
        Dashboard.modal_form.open(title);

    if (msg_bloqueio)
        $('#msg_bloqueio').show();
    else
        $('#msg_bloqueio').hide();

    if (typeof href !== 'undefined') {

        var form = '#modal-' + name;
        var $button = $(form).find('button[type="submit"]');
        var $icon = $button.find('i').attr('class[^fa-*]');

        Form.Blocked($button, $icon, true);

        if ($($form).find('button[type="submit"]').is(':disabled'))
            $('.navbar-list').find('.submit_form').find('button[type="submit"]').attr('disabled', true);
        else
            $('.navbar-list').find('.submit_form').find('button[type="submit"]').removeAttr('disabled');

        if (datatype !== 'json') {

            var spinner = '<div class="preloader-wrapper small active left"><div class="spinner-layer spinner-green-only">	<div class="circle-clipper left">	<div class="circle"></div></div><div class="gap-patch">	<div class="circle"></div></div><div class="circle-clipper right">	<div class="circle"></div></div></div></div>';

            $('#modal-' + name).html($('<div/>').addClass('modal-content').css(
                {
                    'padding': '0',
                    'height': '100%'
                }).html($('<div/>').css(
                    {
                        'width': '210px',
                        'height': '60px',
                        'margin-left': '-105px',
                        'margin-top': '-30px',
                        'left': '50%',
                        'top': '50%',
                        'position': 'absolute',
                        'text-align': 'center'
                    }).html('<h5> ' + spinner + ' &nbsp; <span style="float: left; margin-left: 20px; line-height: 40px">Carregando...</span></5>')));
        }

        $.ajax(
            {
                type: 'get',
                url: href,
                dataType: datatype,
                success: function (data) {

                    if (datatype === 'json') {

                        execute_data(data, name);
                        Form.Blocked($button, $icon, false);

                    }
                    else {
                        $('#modal-' + name).html(data);
                        jQuery('#modal-' + name).find('form').each(function (e) {
                            var F = $(this);
                            var form = new Forms();
                            form.Submit(F);
                        });
                    }

                },
                error: function (data) {

                    if (datatype === 'json') {

                        execute_data(data.responseText, name);
                        setTimeout(function () {
                            Form.Blocked($button, $icon, false);
                        }, 300);

                    }
                    else {
                        setTimeout(function () {
                            $('#modal-' + name).html(data.responseText).append('<p class="center-align"><a href="javascript:void(0);" class="btn modal-close gradient-45deg-indigo-blue waves-effect waves-light">Fechar</a></p>');
                            jQuery('#modal-' + name).find('form').each(function (e) {
                                var F = $(this);
                                var form = new Forms();
                                form.Submit(F);
                            });
                        }, 500);
                    }

                }

            });

    }
    else {
        $('#modal-' + name + ' .dialog').find('input[type="text"]').val('');
        $('#modal-' + name + ' .dialog').find('[name="id"]').val('');
        $('#modal-' + name + ' .dialog').find('[name="action"]').val(action);
    }

}

function modal_edit() {

    $(':button[data-toggle="modal"],[data-type="btn-modal"],[type="reset"]').click(function (e) {

        e.preventDefault();

        var name = $(this).attr('data-target').split('-')[1];
        var action = $(this).attr('data-action');
        var title = $(this).attr('data-title') || $(this).attr('data-tooltip');
        var href = typeof $(this).data('href') !== 'undefined' ? $(this).data('href') : null;
        var datatype = typeof $(this).data('type') !== 'undefined' ? $(this).data('type') : 'json';
        var $params = { name, action, title, href, datatype };

        modal_editor($params);

    });


}


/**
 * Função para alterar o status de um item
 * @param {Object} url
 * @param {object} acao
 */
var update_status = function () {

    $('.update.status').on('change', function () {

        var self = $(this);
        var url = self.data('href');
        var id = self.attr('id');
        var value = self.prop('checked') ? '1' : '0';

        if (self.parents('tr').find('.sts').hasClass('muted'))
            self.closest('tr').find('.sts').removeClass('muted');
        else
            self.parents('tr').find('.sts').addClass('muted');

        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': url,
            'data':
            {
                'acao': 'status',
                'id': id,
                'value': value
            },
            'success': function (data) {
                if (data.type === 'success')
                    if (value == '0')
                        $(self.parents('tr').find('.sts').addClass('muted'));
                    else
                        $(self.closest('tr').find('.sts').removeClass('muted'));
                else
                    load_page(data);

                notificacao(data);

            }
        });

    });

};



/** Estrelas **/
function stars() {
}

// Adicionar produtos numa promoção
function add_produtos() {

    var url = $('table.produtos tr#add input[name="composicao"]').attr('data-url');
    var id_table = $('table').attr('id') || false;

    $('table.produtos tr#add input[type=text]').keyup(function (event) {

        var i = 0;
        var option = $('table.produtos tbody tr#add select[name="composicao"]').find('option').val();
        var inputs = $('table.produtos tbody tr#add input[type=text],table.produtos tr#add select');
        var self = $(this);

        var inputs_length = inputs.length;

        // verificar se todos os campos estão preenchidos
        $(inputs).each(function () {

            var str = $('input[name=valor_porcao]').val();
            var num = str.replace('.', '').replace(',', '.');

            // se não estiver vazio, incrementar variável i
            if ((!$(this).attr('disabled') || !$(this).attr('readonly')) && ($(this).val() != '' && num > 0))
                i++;

            if (i < inputs_length) {

                if ($(this).val() == '') {
                    if (event.keyCode === 13) {
                        $(this).focus();
                        return false;
                    }
                }

                if (parseFloat(num) == 0.00) {

                    if (event.keyCode == 13) {
                        $('input[name=valor_porcao]').focus();
                        $('table.produtos tbody tr#add').find('input[type=text]').each(function (index) {

                            if (!$(this).parents().next().find('input').is(':disabled'))
                                self.blur();
                            $(this).parents().next().find('input[type="text"]').focus();

                            return false;
                        });
                    }
                }

            }

        });

        // Se i for maior ou igual, ativar o botão de inserir item
        // 2 é a quantidade de inputs listados que não têm o atributo "disabled"
        if (i === inputs_length) {

            $('table.produtos tr#add button.add-campo').attr('disabled', false);

            if (event.keyCode === 13) {
                $('button.add-campo').focus();
                $('button.add-campo').click();
            }

        }
        else {
            $('table.produtos tr#add button.add-campo').attr('disabled', true);
        }

    });

    if ($('input[name="valor"]').val() == '0,00') {
        var valor = 0;
        $('table.produtos tbody tr.items').find('input[name="valor_porcao[]"]').each(function () {
            valor += parseFloat($(this).val().replace('.', '').replace(',', '.'));
        });
        set_total(valor.toFixed(2), 'add');
    }

    $('table.produtos tbody tr#add button.add-campo').click(function (event) {

        event.preventDefault();

        var bloqueado = false;
        var inputs = $('table.produtos tbody tr#add input[type=text]');

        var isset = verificar($('table.produtos tbody tr#add input[name="composicao"]').attr('data-id'));

        // if ( isset)
        // {
        // bloqueado = true;
        // alert('Item já incluso');
        // }

        if (!bloqueado) {

            notificacao();
            var items = [];

            var $elem = $('table.produtos tbody tr#add input[name="composicao"]');

            var $val = $elem.attr('data-id') == '' ? $elem.val() : $elem.attr('data-id');

            var idproduto = $val;
            var produto = $('table.produtos tbody tr#add input[name="composicao"]').val();
            // var estoque = $('table.produtos tbody tr#add input[name=t_estoque]').val();
            var quantidade = $('table.produtos tbody tr#add input[name=quantidade]').val();
            // var precovenda = $('table.produtos tbody tr#add input[name=t_precovenda]').val();
            var valor_porcao = $('table.produtos tbody tr#add input[name=valor_porcao]').val();

            var linha = '   <tr class="items">';

            linha += '          <td>';
            linha += '              <label class="control-label" style="border: 1px solid #ebebeb; border-radius: 2px; padding: 8px; top: 0px; margin-top: 0px;">';
            linha += '                  <input type="hidden" name="composicao[]" value="' + idproduto + '">';
            linha += '              ' + produto;
            linha += '              </label>';
            linha += '          </td>';

            if (id_table) {
                linha += '          <td class="text-right">';
                linha += '              <label class="control-label" style="border: 1px solid #ebebeb; border-radius: 2px; padding: 8px; top: 0px; margin-top: 0px;">';
                linha += '                  <input type="hidden" name="quantidade[]" value="' + quantidade + '">';
                linha += '              ' + quantidade;
                linha += '              </label>';
                linha += '          </td>';
            }

            // linha += '<td class="text-right">';
            // linha += '<div class="quantidade input-with-icon">';
            // linha += '       <div class="direction-buttons left">';
            // linha += '       <button type="button" class="btn btn-white" data-action="add">';
            // linha += '           <i class="fa fa-angle-up"></i>';
            // linha += '       </button>';
            // linha += '       <button type="button" class="btn btn-white" data-action="sub">';
            // linha += '           <i class="fa fa-angle-down"></i>';
            // linha += '       </button>';
            // linha += '   </div>';
            // linha += '   <label class="control-label" style="border: 1px solid #ebebeb; border-radius: 2px; padding: 8px; top: 0px; margin-top: 0px; position: relative; padding: 0px; overfow: hidden;">';
            // linha += '       <input type="hidden" name="quantidade[]" value="' + quantidade + '">';
            // linha += '       <span style="display:block; padding: 8px">' + quantidade + '</span>';
            // linha += '   </label>';
            // linha += '</div>';
            // linha += '           </td>';

            linha += '          <td class="text-right">';
            linha += '              <label class="control-label" style="border: 1px solid #ebebeb; border-radius: 2px; padding: 8px; top: 0px; margin-top: 0px;">';
            linha += '                  <input type="hidden" name="valor_porcao[]" value="' + valor_porcao + '">';
            linha += '                  <i class="moeda"></i> ' + valor_porcao;
            linha += '              </label>';
            linha += '          </td>';

            linha += '          <td class="text-center">';
            linha += '              <a href="#" class="btn btn-danger remover">';
            linha += '                  <i class="fa fa-trash"></i>';
            linha += '              </a>';
            linha += '          </td>';

            if ($('table.produtos').hasClass('bulk_action')) {
                linha += '      <td>';
                linha += '          <div class="text-center"><input type="checkbox" name="pedidos[]" value="" id="checkbox" class="icheckbox"></div>';
                linha += '      </td>';
            }

            linha += '      </tr>';

            items.push($('table.produtos tbody').append(linha));

            // set_total(valor_porcao, quantidade, 'add');

        }

        $('table.produtos tbody tr#add input[type=text]').val('').blur();
        $('table.produtos tbody tr#add input[name="composicao"]').val('').attr('data-id', '').focus();
        $('table.produtos tbody tr#add input[name="quantidade"]').val($('input[name="quantidade"]').attr('data-default-value'));
        $('table.produtos tbody tr#add label.t_precovenda').html('<i class="moeda"></i> <div class="clearfix"></div> ');
        $('table.produtos tbody tr#add').find('.select2-container').focus().addClass('select2-container--focus');

        $(this).attr('disabled', true);

        var plugins = new Plugins();
        plugins.iCheckbox('input[name="pedidos[]"]');

        remover_linha();

        // set_total(valor, quantidade, 'add');
        // buttons_add();

    });

    remover_linha();

    $('table.produtos tbody tr#add input[type=text]').keypress(function (event) {

        if (event.keyCode === 13) {
            event.preventDefault();
            return false;
        }

    });

    function set_total(valor, quantidade, operacao) {

        if (!valor)
            return false;

        var val = 0;
        var qtd = 0;
        var total = 0;
        var total_final = 0;

        var input = $('input[name="valor"]');
        var valor_total = parseFloat(input.val().replace('.', '').replace(',', '.'));

        val = parseFloat(valor.replace('.', '').replace(',', '.')).toFixed(2);
        qtd = parseFloat(quantidade).toFixed(2);

        total = val * qtd;

        switch (operacao) {
            case 'add':
                total_final = total + total_final;
                break;
            case 'sub':
                total_final = total - total_final;
                break;
        }

        // input.val(total_final.toFixed(2).replace('.', ','));

    }

    function remover_linha() {
        $('table.produtos tbody tr.items a.remover').click(function (event) {
            event.preventDefault();

            var id = $(this).attr('id');
            var href = $(this).attr('href');

            if (href !== undefined && href !== '#') {
                $.ajax(
                    {
                        type: 'post',
                        dataType: 'json',
                        url: href,
                        data:
                        {
                            acao: 'removerpromocao',
                            id: id
                        },
                        success: function (data) {

                            exibir_alerta(data);

                            $('a#' + data.item).parents('tr').remove();
                            $('table.produtos tbody tr.items a.remover').removeAttr('disabled');

                        },
                        error: function () {

                        }
                    });

            }
            else {

                var linha = $(this).closest('tr');
                var valor = linha.find($('input[name="valor_porcao[]"]')).val();

                if (linha.find($('input[name="quantidade"]')).length) {
                    var quantidade = linha.find($('input[name="quantidade[]"]')).val();
                    set_total(valor.replace('.', '').replace(',', '.'), quantidade.replace('.', '').replace(',', '.'), 'sub');
                }

                linha.remove();

            }

        });

    }

    function buttons_add() {

        $('.direction-buttons').find('button[type="button"]').click(function () {

            var valor_total = 0;
            var quantidade = 0;
            var qtd = 0;
            var qtd_inicial = 0;

            var action = $(this).attr('data-action');
            var linha = $(this).closest('td');

            valor_total = linha.next().find('input').val();

            qtd_inicial = linha.find('input').val().replace('.', '').replace(',', '.');
            quantidade = 0;
            qtd = 0;

            quantidade = qtd_inicial == 0 ? 1 : qtd_inicial;

            if (action == 'add')
                quantidade = parseFloat(quantidade) + 1;
            else
                quantidade = parseFloat(quantidade).toFixed(2) - 1;

            qtd = (quantidade == 0) ? 1 : quantidade;

            linha.find('input').val(qtd);
            linha.find('input').next('span').text(qtd);

            set_total(valor_total, qtd, action);

        });

    }

    function verificar(val) {

        var valido;

        // verificar se não existe nenhum produto no corpo da tabela com o nome igual ao campo de texto
        $('table.produtos tbody tr.items').find('input[name="composicao[]"]').each(function (index) {

            if (val == $(this).val()) {
                valido = true;
                return valido;
            }

        });

        if (valido != undefined) return valido;

    }

    // remover_linha();
    // buttons_add();

}

/* AUTOCOMPLETE */
function init_autocomplete() {

    add_produtos();

    if (!$.fn.autocomplete)
        return false;

    // initialize autocomplete with custom appendTo
    $('.autocomplete').each(function () {

        var self = $(this);
        var next_input = $(this).attr('data-next-input');
        $(this).autocomplete(
            {
                serviceUrl: $(this).attr('data-url'),
                type: 'post',
                dataType: 'json',
                transformResult: function (data) {
                    return {
                        suggestions: $.map(data, function (value) {
                            return { value: value.field, data: { id: value.id, valor: value.valor } }
                        })
                    };
                },
                onSelect: function (data) {

                    $.map(data, function (key, value) {
                        self.attr('data-id', key.id).next('input[type=hidden]').val(key.id);

                        if (key.valor != undefined) {
                            $('input[name="valor_porcao"]').val(key.valor);
                            $('table.produtos tr#add button.add-campo').attr('disabled', false);
                        }
                    });

                },
                // showNoSuggestionNotice : true,
                zIndex: 9999,
                maxHeight: 200,
                minChars: 1
            });
    });

    function autoFocus(input) {
        // console.log('Setar o foco do cursor para outro input');
        $(input).focus();
    }

}


function animate(component, animation, callback) {

    var object;
    var animations = ["animated", "bounce", "flash", "pulse", "rubberBand", "shake", "swing", "tada", "wobble", "jello", "heartBeat", "bounceIn", "bounceInDown", "bounceInLeft", "bounceInRight", "bounceInUp", "bounceOut", "bounceOutDown", "bounceOutLeft", "bounceOutRight", "bounceOutUp", "fadeIn", "fadeInDown", "fadeInDownBig", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig", "fadeOut", "fadeOutDown", "fadeOutDownBig", "fadeOutLeft", "fadeOutLeftBig", "fadeOutRight", "fadeOutRightBig", "fadeOutUp", "fadeOutUpBig", "flip", "flipInX", "flipInY", "flipOutX", "flipOutY", "lightSpeedIn", "lightSpeedOut", "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight", "rotateOut", "rotateOutDownLeft", "rotateOutDownRight", "rotateOutUpLeft", "rotateOutUpRight", "slideInUp", "slideInDown", "slideInLeft", "slideInRight", "slideOutUp", "slideOutDown", "slideOutLeft", "slideOutRight↵	", "zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp", "zoomOut", "zoomOutDown", "zoomOutLeft", "zoomOutRight", "zoomOutUp", "hinge", "jackInTheBox", "rollIn", "rollOut"]

    $(component).removeClass(animations).addClass(animation + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
        $(this).removeClass(animations);

        if (typeof callback === 'function')
            callback($(this));
    });

};

$(document).ready(function () {
    $('.js--triggerAnimation').click(function (e) {
        e.preventDefault();
        var anim = $('.js--animations').val();
        testAnim(anim);
    });

    $('.js--animations').change(function () {
        var anim = $(this).val();
        testAnim(anim);
    });
});

