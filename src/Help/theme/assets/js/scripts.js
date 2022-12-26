/*!
* Start Bootstrap - Bare v5.0.7 (https://startbootstrap.com/template/bare)
* Copyright 2013-2021 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-bare/blob/master/LICENSE)
*/
// This file is intentionally blank
// Use this file to add JavaScript to your project

$(function () {
    var ajaxResponseBaseTime = 3;
    var ajaxResponseRequestError = "<div class='message error icon-warning'>Desculpe mas não foi possível processar sua requisição...</div>";


    // selecionar e cancelar seleção de checkbox
    // para utilizar, crie um checkbox com a classe .checkbox-selecionar-pai e um data-classe_filhos com o nome da classe para filtrar os filhos
    // colocar a classe criada no data-classe_filhos em todos os checkboxes que deverão ser selecionados ao clicar no pai
    // $(".checkbox-selecionar-pai").click(function () {
    //
    //     let clicked = $(this);
    //     var attrData = clicked.data();
    //
    //     if ( $(this).is(':checked') ){
    //         $("."+attrData.classe_filhos).prop("checked", true);
    //     }else{
    //         $("."+attrData.classe_filhos).prop("checked", false);
    //     }
    // });

    $(document).on("click", ".change-element-props", function () {

        let clicked = $(this);
        let attrData = clicked.data();

        let affected_class = attrData.affected_class;
        let invert_affected_class = attrData.invert_affected_class;
        let changed_attr = attrData.changed_attr;
        let cur_value = attrData.cur_value;
        let new_value = attrData.new_value;
        let disable_this_after = attrData.disable_this_after;
        let enable_elements_after = attrData.enable_elements_after;

        /* alterar os atributos */
        $("." + affected_class).prop(changed_attr, new_value)
        $("." + invert_affected_class).prop(changed_attr, cur_value)

        /* habilitar os inputs que tiverem a classe afetada com o sufixo _enable */
        $("." + affected_class + "_enable").prop("disabled", false)

        /* desabilitar os inputs que tiverem a classe afetada com o sufixo _disable */
        $("." + affected_class + "_disable").prop("disabled", true)

        /* limpar os inputs que tiverem a classe afetada com o sufixo _empty */
        $("." + affected_class + "_empty").val("")

        /* focar o input com a classe afetada com o sufixo _focus */
        $("." + affected_class + "_focus").focus()

        /* atualizar os valores do data para a proxima execucao */
        clicked.data("cur_value", new_value)
        clicked.data("new_value", cur_value)

    });

    /**
     * Check/Uncheck all checkboxes in page
     *
     * selecionar e cancelar seleção de checkbox
     * para utilizar, crie um checkbox com a classe .checkbox-selecionar-pai e um data-classe_filhos com o nome da classe para filtrar os filhos
     * colocar a classe criada no data-classe_filhos em todos os checkboxes que deverão ser selecionados ao clicar no pai
     *
     * Para incluir um contador de elementos selecionados, inclua um elemento span (
     *      exemplo: <span id="childen_counter">0</span>
     * no elemento pai: inclua um data-counter="#nome_que_desejar"
     * e nos elementos filhos: a classe .checkbox-selecionar-filha e o data-counter="#nome_que_desejar
     */
    $(".checkbox-selecionar-pai").click(function () {

        let clicked = $(this);
        var attrData = clicked.data();

        if(attrData.counter){
            childen_counter = $(attrData.counter);
            counter = $("."+attrData.classe_filhos).length;
        }

        if ( $(this).is(':checked') ){
            $("."+attrData.classe_filhos).prop("checked", true);
            if(attrData.counter){
                childen_counter.html(counter);
            }
        }else{
            $("."+attrData.classe_filhos).prop("checked", false);
            if(attrData.counter){
                childen_counter.html(0);
            }
        }
    });

    $(".checkbox-selecionar-filha").click(function () {
        let clicked = $(this);
        var attrData = clicked.data();

        if(attrData.counter){
            childen_counter = $(attrData.counter);
            counter = parseInt(childen_counter.html());

            if ( $(this).is(':checked') ){
                childen_counter.html(counter + 1);
            }else{
                childen_counter.html(counter - 1);
            }
        }

    });

    // $(document).on('select2:selecting', '.filter', function (e) {
    //
    // });

    $(document).on('change', '.filter', function (e) {

        e.preventDefault();

        // $($(".cabecalho-alerta") ).empty();
        // $($(".ajax_response") ).empty();

        let changedEl = $(this);
        let data = changedEl.data();
        let load = $(".ajax_load");

        if (data.confirm) {
            var deleteConfirm = confirm(data.confirm);
            if (!deleteConfirm) {
                return;
            }
        }

        let selected_data = [];
        selected_data[changedEl.attr("name")] = changedEl.val();

        var nextIndex = data.index + 1;

        console.log(data, selected_data)
        $.ajax({
            url: data.post,
            type: "POST",
            data: $.extend(data, selected_data),
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {

                //message
                if (response.status === 'success') {

                    console.log(response)

                    // $('select[data-index]').each(function (index) {
                    //     if ($(this).data('index') >= nextIndex) {
                    //         $(this).empty();
                    //     }
                    // });
                    //
                    // if (response.counter == 0) {
                    //     $('select[data-index="' + nextIndex + '"]').attr("disabled", true);
                    //     $('select[data-index="' + nextIndex + '"]').append(
                    //         $('<option>', {
                    //             text: 'Não retornou nenhum registro',
                    //             disabled: true,
                    //             selected: true
                    //         })
                    //     );
                    // } else {
                    //     $('select[data-index="' + nextIndex + '"]').attr("disabled", false);
                    //     $('select[data-index="' + nextIndex + '"]').append(
                    //         $('<option>', {
                    //             text: 'Selecione uma opção',
                    //             disabled: true,
                    //             selected: true
                    //         })
                    //     );
                    //     $.each(response.data, function (key, value) {
                    //         $('select[data-index="' + nextIndex + '"]').append(
                    //             $('<option>', {
                    //                 value: key,
                    //                 text: value
                    //             })
                    //         );
                    //     });
                    // }
                    //
                    //
                    // $.each($('select[name*="filter_"]'), function (index, element) {
                    //
                    //     if ($(element).data('index') >= nextIndex + 1) {
                    //         $(element).empty().append(
                    //             $('<option>', {
                    //                 text: 'Selecione o filtro anterior',
                    //                 disabled: true
                    //             })
                    //         );
                    //     }
                    //
                    // });

                    if (typeof updatedFieldsProps === "function") {
                        updatedFieldsProps();
                    }


                }

                if (response.status === 'fail') {

                    if ($(element).data('index') >= nextIndex) {
                        $(element).empty().append(
                            $('<option>', {
                                text: 'Selecione o filtro anterior',
                                disabled: true
                            })
                        );
                    }
                }

                load.fadeOut();

            },
            error: function () {
                // window.location.reload();
                // ajaxMessage(ajaxResponseRequestError, 5);
                // load.fadeOut();
            }
        });

    });

    $(document).on('click', '.clickToFilter', function (e) {

        e.preventDefault();

        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        let elementToFilter = $(data.element_to_filter);

        if (empty(elementToFilter.val())) {
            alert("Para realizar a pesquisa é necessário informar um valor de pesquisa")
            return;
        }

        if (data.confirm) {
            var deleteConfirm = confirm(data.confirm);
            if (!deleteConfirm) {
                return;
            }
        }


        let selected_data = [];
        selected_data[elementToFilter.attr("name")] = elementToFilter.val();

        var nextIndex = data.index + 1;

        $.ajax({
            url: data.post,
            type: "POST",
            data: $.extend(data, selected_data),
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {

                //message
                if (response.status === 'success') {

                    // esse comportamento, ira procutar inputs com os nomes dos campos da tabela e preencher com o valor
                    if(response.behavior === "fill_form"){
                        $.each(response.data, function (key, value) {
                            $("#"+key).val(value)
                        })
                        load.fadeOut(200);
                        return;
                    }

                    // o comportamento padrao é pegar o valor do select atual e preencher o proximo

                    // $('select[data-index]').each(function (index) {
                    //     if ($(this).data('index') >= nextIndex) {
                    //         $(this).empty();
                    //     }
                    // });
                    //
                    // if (response.counter == 0) {
                    //     $('select[data-index="' + nextIndex + '"]').attr("disabled", true);
                    //     $('select[data-index="' + nextIndex + '"]').append(
                    //         $('<option>', {
                    //             text: 'Não retornou nenhum registro',
                    //             disabled: true,
                    //             selected: true
                    //         })
                    //     );
                    // } else {
                    //     $('select[data-index="' + nextIndex + '"]').attr("disabled", false);
                    //     $('select[data-index="' + nextIndex + '"]').append(
                    //         $('<option>', {
                    //             text: 'Selecione uma opção',
                    //             disabled: true,
                    //             selected: true
                    //         })
                    //     );
                    //     $.each(response.data, function (key, value) {
                    //         $('select[data-index="' + nextIndex + '"]').append(
                    //             $('<option>', {
                    //                 value: key,
                    //                 text: value
                    //             })
                    //         );
                    //     });
                    // }
                    //
                    //
                    // $.each($('select[name*="filter_"]'), function (index, element) {
                    //
                    //     if ($(element).data('index') >= nextIndex + 1) {
                    //         $(element).empty().append(
                    //             $('<option>', {
                    //                 text: 'Selecione o filtro anterior',
                    //                 disabled: true
                    //             })
                    //         );
                    //     }
                    //
                    // });

                    if (typeof updatedFieldsProps === "function") {
                        updatedFieldsProps();
                    }


                }

                if (response.status === 'fail') {

                    if ($(element).data('index') >= nextIndex) {
                        $(element).empty().append(
                            $('<option>', {
                                text: 'Selecione o filtro anterior',
                                disabled: true
                            })
                        );
                    }
                }

                load.fadeOut();

            },
            error: function () {
                // window.location.reload();
                // ajaxMessage(ajaxResponseRequestError, 5);
                // load.fadeOut();
            }
        });

    });

// envia formulario

    $(document).on('submit', '.sendFormUsingAjaxWithUpload', function (e) {
        e.preventDefault();

        $($(".cabecalho-alerta")).empty();
        $($(".ajax_response")).empty();

        let myform = $(this)[0];
        var formData = new FormData(myform);
        let load = $(".ajax_load");
        let data = $(this).data();

        $.ajax({
            url: data.post,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {
                let json = jQuery.parseJSON(response);

                //redirect
                if (json.redirect) {
                    window.location.href = json.redirect;
                }
                // else {
                //     load.fadeOut(200);
                // }

                //reload
                if (json.reload) {
                    window.location.reload();
                }
                // else {
                //     load.fadeOut(200);
                // }

                //message
                if (json.message) {
                    load.fadeOut(200);
                    ajaxMessage(json.message, ajaxResponseBaseTime);
                } else {
                    load.fadeOut(200);
                }
            },
            error: function () {
                // window.location.reload();
                // ajaxMessage(ajaxResponseRequestError, 5);
                // load.fadeOut();
            }
        });

    });

    $(document).on('submit', '.sendFormUsingAjax', function (e) {
        e.preventDefault();

        $($(".cabecalho-alerta")).empty();
        $($(".ajax_response")).empty();

        let myform = $(this);
        let data = myform.data();
        let load = $(".ajax_load");

        var myformObject = {};
        $.each(this,
            function (i, v) {
                myformObject[v.name] = v.value;
            });

        // let formData = getFormData(clicked.closest('form'));
        jsonData = {};
        $.extend(jsonData, myformObject, data);

        $.ajax({
            url: data.post,
            type: "POST",
            data: jsonData,
            dataType: 'json',
            // processData: false,
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {
                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
                // else {
                //     load.fadeOut(200);
                // }

                //reload
                if (response.reload) {
                    window.location.reload();
                }
                // else {
                //     load.fadeOut(200);
                // }

                //message
                if (response.message) {
                    load.fadeOut(200);
                    ajaxMessage(response.message, ajaxResponseBaseTime);
                } else {
                    load.fadeOut(200);
                }
            },
            error: function () {
                // window.location.reload();
                // ajaxMessage(ajaxResponseRequestError, 5);
                // load.fadeOut();
            }
        });

    });


    /**
     * Executado quando um botão ou um link possui a classe sendForm
     *
     * Para configurar a chamada desse metodo, é necessário incluir algum atributos data no elemento clicado
     *
     * Atributos obrigatórios:
     *    data-post="" -> pagina chamada
     *
     * Atributos opcionais
     *    data-parent_form="id-do-form-pai" -> id do formulario a que o elemento pertence. se não informado será utilizado o closest form para localizar o formulario
     *    data-send_inputs="true or false" -> caso o formulario tenha inputs para serem enviados, se falso serão submetidos somente os atributos data
     *    data-confirm="Mensagem de confirmação" -> utilizado para mostrar um alerta de confirmação
     *    data-send_method="submit" -> utilizado para fazer o submit padrão do formulario
     *    data-target="new_tab" -> para abrir em uma nova tab. Somente se o data-send_method="submit"
     *    data-disable_button="true" -> disabilita o botão para evitar o duplo click
     *
     * Validar o upload de arquivos
     *    data-upload_validate="true or false" caso seja true a função irá validar o elemento input com a classe .upload_validate
     *
     *    Para configurar o elemento input upload_validate, utilize os seguintes data attributes
     *      data-max_files="2" -> opcional e informa a qyantidade maxima de arquivos que podem ser selecionados por upload
     *
     *
     * Atributos variaveis
     *    data-field_name="valor" -> todos os campos data serão submetidos
     *
     *
     * Resposta esperada:
     * Formato: string json {"operação" : "valor"}
     *
     * Operacoes possíveis:
     *    redirect -> redireciinar para a url informada no valor
     *    reload -> recarrega a pagina
     *    newPage -> irá abrir uma nova pagina com a url retornada no target informado. Pode ser um array, nesse caso todas serão carregadas cada uma em uma nova aba do navegador
     *    modalForm -> irá carregar o retorno em uma janela modal
     *    message -> irá mostrar a mensagem na div com a classe ajax_response, caso essa div não exista nenhuma mensagem será apresentada
     *
     */
    $(document).on('click', '.sendForm', function (e) {
        e.preventDefault();

        $($(".cabecalho-alerta")).empty();
        $($(".ajax_response")).empty();

        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");
        let disableButton = data.disable_button !== undefined ? data.disable_button : true;

        if (clicked.attr("disabled") == 'disabled') {
            return;
        }


        if (data.upload_validate) {
            let upload_validate = $(".upload_validate");
            let upload_element = upload_validate[0];
            let upload_data = $(upload_element).data();

            if ($(upload_element)[0].files.length == 0) {
                alert("É necessário selecionar ao menos 1 arquivo.");
                return
            }

            if ($(upload_element)[0].files.length > upload_data.max_files) {
                alert("É possível importar até " + upload_data.max_files + " por vez");
                return
            }
        }

        if (data.confirm) {
            var deleteConfirm = confirm(data.confirm);
            if (!deleteConfirm) {
                return;
            }
        }

        if ( $("#new_form").length ) {
            $("#new_form").remove();
        }
        if (data.send_method === 'submit') {
            if (data.target !== undefined && data.target.toUpperCase() === "NEW_TAB") {

                var newForm = $('<form>', {
                    'action': data.post,
                    'method': 'POST',
                    'target': '_blank',
                    'id': 'new_form'
                });

                $.map(data, function (value, key) {
                    if ( !$( "input[name="+key+"]" ).length ) {
                        $("<input>").attr("type", "hidden").attr("name", key).attr("value", value).appendTo(newForm);
                    }
                });

                $('body').append(newForm);
                $("#new_form").submit();
                // setTimeout( function () {
                //
                // }, 1000);

                // clicked.closest("form").attr("target", "_blank");
            }else{
                clicked.closest("form").submit();
            }
        } else {
            if(disableButton){
                clicked.attr("disabled", true);
            }
            ajaxOptParams = {};
            if (data.send_inputs === true) {

                let myform = $(clicked.closest('form'))[0];
                // let myform = $("#"+data.parent_form);

                $.map(data, function (value, key) {
                    if(data.parent_form === undefined){
                        if ( !$( "input[name="+key+"]" ).length ) {
                            $("<input>").attr("type", "hidden").attr("name", key).attr("value", value).appendTo(myform);
                        }
                    }else{
                        if ( !$( "#"+data.parent_form + " > input[name="+key+"]" ).length ) {
                            $("<input>").attr("type", "hidden").attr("name", key).attr("value", value).appendTo(myform);
                        }
                    }
                });
                let formData = new FormData(myform);

                // data: data.send_inputs == true ? formData : data,
                //     processData: data.send_inputs == true ? false : true,
                //     contentType: data.send_inputs == true ? false : true,
                ajaxOptParams.data = new FormData(myform);
                ajaxOptParams.processData = false;
                ajaxOptParams.contentType = false;

                // let myform = $(clicked.closest('form')).serializeArray();
                // var myformObject = {};
                // $.each(myform,
                //     function(i, v) {
                //         myformObject[v.name] = v.value;
                //     });
                //
                // // let formData = getFormData(clicked.closest('form'));
                // jsonData = {};
                // $.extend(jsonData, myformObject, data);
            } else {
                ajaxOptParams.data = data;
                ajaxOptParams.dataType = "json";
                jsonData = data;
            }

            $.ajax(
                $.extend(
                    ajaxOptParams,
                    {
                        url: data.post,
                        type: "POST",

                        // data: jsonData,
                        // dataType: "json",


                        // dataType: "json",

                        beforeSend: function () {
                            load.fadeIn(200).css("display", "flex");
                        },
                        success: function (response) {
                            console.log("resposta", typeof response, response)
                            var jsonResponse = typeof response != 'object' ? jQuery.parseJSON(response) : response;
                            // if(typeof response != 'object')
                            // {
                            //     var jsonResponse = jQuery.parseJSON(response);
                            // }else{
                            //     var jsonResponse = response;
                            // }

                            //redirect
                            if (jsonResponse.redirect) {
                                console.log("entrou no redirect", jsonResponse.redirect)
                                window.location.href = jsonResponse.redirect;
                            }

                            else if (jsonResponse.reload) {
                                console.log("reload")
                                window.location.reload();
                            }

                            else if (jsonResponse.newPage) {
                                console.log("newPage")
                                if($.isArray(jsonResponse.newPage)){
                                    $.each(jsonResponse.newPage, function (idx, newPage) {
                                        window.open("", "_BLANK").document.write(newPage.page)
                                    })
                                }else{
                                    let target = jsonResponse.newPage.target !== undefined ? jsonResponse.newPage.target : '_self';
                                    let newPage = window.open("", target);
                                    newPage.document.write(jsonResponse.newPage.page);
                                }
                            }

                            //load form
                            else if (jsonResponse.modalForm) {
                                console.log("modalForm")
                                $('#modal-forms-body').html(jsonResponse.modalForm);
                                $('#modal-forms').modal('show');
                                load.fadeOut(200);
                            }

                            //message
                            else if (jsonResponse.message) {
                                clicked.attr("disabled", false);
                                ajaxMessage(jsonResponse.message, ajaxResponseBaseTime);
                                load.fadeOut(200);
                            } else {
                                console.log("else")
                                load.fadeOut(200);
                                window.open(jsonResponse.ReturnVal, '_blank');
                            }
                        },
                        error: function () {
                            console.log("erro", ajaxResponseRequestError)
                            // window.location.reload();
                            // ajaxMessage(ajaxResponseRequestError, 5);
                            // load.fadeOut();
                        }
                    })
            );
        }


    });

    $(document).on('click', '.modalOpenForm', function (e) {
        // $(".modalOpenForm").click(function (e) {
        e.preventDefault();

        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        $.ajax({
            url: data.post,
            method: "POST",
            data: data,
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (dataResult) {

                load.fadeOut(200);
                $('#modal-forms-body').html(dataResult);
                $('#modal-forms').modal('show');

            },
            error: function () {
                window.location.reload();
                ajaxMessage(ajaxResponseRequestError, 5);
                load.fadeOut();
            }
        });
    });

    $(document).on('click', '.modalOpenFormJson', function (e) {
        // $(".modalOpenForm").click(function (e) {
        e.preventDefault();

        $($(".ajax_response")).empty();

        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        if (data.send_inputs == true) {

            let myform = $(clicked.closest('form')).serializeArray();
            var myformObject = {};
            $.each(myform,
                function (i, v) {
                    myformObject[v.name] = v.value;
                });

            // let formData = getFormData(clicked.closest('form'));
            jsonData = {};
            $.extend(jsonData, myformObject, data);
        } else {
            jsonData = data;
        }

        $.ajax({
            url: data.post,
            method: "POST",
            data: jsonData,
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (dataResult) {

                //redirect
                if (dataResult.redirect) {
                    window.location.href = dataResult.redirect;
                }
                // else {
                //     load.fadeOut(200);
                // }

                //reload
                if (dataResult.reload) {
                    window.location.reload();
                }
                // else {
                //     load.fadeOut(200);
                // }

                //message
                //message
                if (dataResult.message) {
                    ajaxMessage(dataResult.message, ajaxResponseBaseTime);
                }

                //load form
                if (dataResult.modalForm) {
                    load.fadeOut(200);
                    $('#modal-forms-body').html(dataResult.modalForm);
                    $('#modal-forms').modal('show');
                }

                if (dataResult.newPage) {
                    console.log(dataResult.newPage.target, dataResult.newPage.page)
                    let newPage = window.open("", dataResult.newPage.target)
                    newPage.document.write(dataResult.newPage.page);
                }
                // else {
                //     window.location.reload();
                // }

                load.fadeOut(200);

            },
            error: function () {
                window.location.reload();
                ajaxMessage(ajaxResponseRequestError, 5);
                load.fadeOut();
            }
        });
    });

    $(document).on('click', '.gotoUrl', function (e) {
        // $('.gotoUrl').click(function (e) {
        e.preventDefault();

        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        gotoUrl(data.post, data, data.submit_method, data.open_window);

    });

    /**
     * Execute a zipcode search and populate addresses fields
     */
    $(document).on('change', '.zip_code_search', function () {

        var attrData = $(this).data();

        if (attrData.confirm) {
            var deleteConfirm = confirm(attrData.confirm);
            if (!deleteConfirm) {
                return;
            }
        }

        let load = $(".ajax_load");

        function emptyForm() {
            $(".street").val("");
            $(".neighborhood").val("");
            $(".city").val("");
            $(".state").val("").trigger('change');
        }

        var zip_code = $(this).val().replace(/\D/g, '');
        var validate_zip_code = /^[0-9]{8}$/;

        if (zip_code != "" && validate_zip_code.test(zip_code)) {
            load.fadeIn(200).css("display", "flex");
            $(".zip_code_search").val(zip_code);

            emptyForm();

            $.getJSON("https://viacep.com.br/ws/" + zip_code + "/json/?callback=?", function (data) {
                load.fadeOut();
                if (!("erro" in data)) {
                    $(".street").val(data.logradouro);
                    $(".neighborhood").val(data.bairro);
                    $(".city").val(data.localidade);
                    $(".state").val(data.uf).trigger('change');
                } else {
                    emptyForm();
                    alert("CEP não encontrado.");
                }
            });
        } else {
            emptyForm();
            alert("Formato de CEP inválido.");
        }


    });



// AJAX RESPONSE

    function ajaxMessage(message, time) {
        var ajaxMessage = $(message);

        ajaxMessage.append("<div class='message_time'></div>");
        ajaxMessage.find(".message_time").animate({"width": "100%"}, time * 1000, function () {
            $(this).parents(".message").fadeOut(200);
        });

        $(".ajax_response").append(ajaxMessage);
        // ajaxMessage.effect("bounce");
    }

    $('.redirectToUrlWithPostData').click(function (e) {
        e.preventDefault();

        let clicked = $(this);
        let data = clicked.data();
        let pag_voltar = window.location.pathname.split('/')[2];

        if (data.confirm) {
            var deleteConfirm = confirm(data.confirm);
            if (!deleteConfirm) {
                return;
            }
        }

        gotoUrl(data.post, data, data.prot_method, data.open_target);

    });

    // REMEMBER LAS TAB-PANE
    $(document).ready(function () {

        let myTab = $('#myTab');

        if (empty(myTab)) {
            localStorage.setItem('activeTab', "main");
        } else {
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });
        }


        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });

    $('.collapse').on('show.bs.collapse', function () {
        $(this).parent().removeClass("zeroPadding");
    });

    $('.collapse').on('hide.bs.collapse', function () {
        $(this).parent().addClass("zeroPadding");
    });

    // SELECT2
    // $('.select2').select2({
    //
    //     language: "pt-BR",
    //     selectionCssClass: "teste",
    //     theme: "bootstrap"
    // });

    // DATATABLES
    $(".datatable").each(function (index, element, set) {
        $(element).DataTable({
            responsive: true,
            "pageLength": 15,
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
        });
    });
});