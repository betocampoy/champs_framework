// JQUERY INIT
$(function () {
    var ajaxResponseBaseTime = 5;
    var ajaxResponseRequestError = "<div class='message error icon-warning'>Desculpe mas não foi possível processar sua requisição...</div>";

    function getFormData($form) {

        var unindexed_array = $form.serializeArray();
        var indexed_array = {};

        $.map(unindexed_array, function (n, i) {
            indexed_array[n['name']] = n['value'];
        });

        return indexed_array;
    }

    function zipcodeSearch() {
        let zipcodeEl = $('.zip_code_search');
        let attrData = zipcodeEl.data();

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

        var zip_code = zipcodeEl.val().replace(/\D/g, '');
        var validate_zip_code = /^[0-9]{8}$/;

        if (zip_code != "" && validate_zip_code.test(zip_code)) {
            load.fadeIn(200).css("display", "flex");
            zipcodeEl.val(zip_code);

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
    }

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

        if (attrData.counter) {
            childen_counter = $(attrData.counter);
            counter = $("." + attrData.classe_filhos).length;
        }

        if ($(this).is(':checked')) {
            $("." + attrData.classe_filhos).prop("checked", true);
            if (attrData.counter) {
                childen_counter.html(counter);
            }
        } else {
            $("." + attrData.classe_filhos).prop("checked", false);
            if (attrData.counter) {
                childen_counter.html(0);
            }
        }
    });

    $(".checkbox-selecionar-filha").click(function () {
        let clicked = $(this);
        var attrData = clicked.data();

        if (attrData.counter) {
            childen_counter = $(attrData.counter);
            counter = parseInt(childen_counter.html());

            if ($(this).is(':checked')) {
                childen_counter.html(counter + 1);
            } else {
                childen_counter.html(counter - 1);
            }
        }

    });

    /**
     * Preencher um select com uma pesquisa originada no select anterior.
     *
     * Para iniciar a consulta, coloque a classe filter_parent no select que irá realizar a consulta. Utilize o data-attributes para parametrizar a consulta
     * data-route: rota para realizar a pesquisa
     * data-model: modelo que será pesquisado (ex. User)
     * data-columns: colunas que serão pesquisadas separadas por virgulas. no minimo 2 colunas id é obrigatório (ex. id, name)
     * data-index: indice do elemento, se ele é o 1, o resulta será preenchido no 2
     *
     *
     * No elemento que receberá o resultado, inserir a classe filter_child e o data-index com o valor do pai + 1
     */
    $(document).on('change', '.filter_parent', function (e) {

        e.preventDefault();

        // $($(".cabecalho-alerta") ).empty();
        // $($(".ajax_response") ).empty();

        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        let selected_data = [];
        selected_data[clicked.attr("name")] = clicked.val();

        var nextIndex = data.index + 1;

        $.ajax({
            url: data.route,
            type: "POST",
            data: $.extend(data, selected_data),
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            success: function (response) {

                //redirect
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    load.fadeOut(200);
                }

                //message
                if(response.status === 'success') {

                    let targetEl = $('.filter_child[data-index="' + nextIndex + '"]');
                    let targetElType = targetEl.prop('nodeName');

                    // if the targer is an INPUT
                    if(targetElType === "INPUT"){
                        if(response.counter > 0 ){
                            $.each(response.data, function(key, value){
                                price = value;
                            });
                        }

                        targetEl.val(price);
                    }

                    // if the targer is a SELECT
                    if(targetElType === "SELECT"){
                        $('select[data-index]').each(function (index){
                            if($(this).data('index') >= nextIndex){
                                $(this).empty();
                            }
                        });

                        if(response.counter == 0 ){
                            $('select[data-index="' + nextIndex + '"]').attr("disabled", true);
                            $('select[data-index="' + nextIndex + '"]').append(
                                $('<option>', {
                                    text: 'Não retornou nenhum registro',
                                    disabled: true,
                                    selected: true
                                })
                            );
                        }
                        else{
                            $('select[data-index="' + nextIndex + '"]').attr("disabled", false);
                            $('select[data-index="' + nextIndex + '"]').append(
                                $('<option>', {
                                    text: 'Selecione uma opção',
                                    disabled: true,
                                    selected: true
                                })
                            );
                            $.each(response.data, function(key, value){
                                $('select[data-index="' + nextIndex + '"]').append(
                                    $('<option>', {
                                        value: key,
                                        text: value
                                    })
                                );
                            });
                        }

                        $.each($('select[class*="filter_child"]'), function(index, element){

                            if($(element).data('index') >= nextIndex + 1){
                                $(element).empty().append(
                                    $('<option>', {
                                        text: 'Selecione o filtro anterior',
                                        disabled: true
                                    })
                                );
                            }

                        });
                    }


                    if (typeof updatedFieldsProps === "function")
                    {
                        updatedFieldsProps();
                    }


                }

                if(response.status === 'fail') {

                    if($(element).data('index') >= nextIndex){
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

                    console.log(response.counter)

                    $('select[data-index]').each(function (index) {
                        if ($(this).data('index') >= nextIndex) {
                            $(this).empty();
                        }
                    });

                    if (response.counter == 0) {
                        $('select[data-index="' + nextIndex + '"]').attr("disabled", true);
                        $('select[data-index="' + nextIndex + '"]').append(
                            $('<option>', {
                                text: 'Não retornou nenhum registro',
                                disabled: true,
                                selected: true
                            })
                        );
                    } else {
                        $('select[data-index="' + nextIndex + '"]').attr("disabled", false);
                        $('select[data-index="' + nextIndex + '"]').append(
                            $('<option>', {
                                text: 'Selecione uma opção',
                                disabled: true,
                                selected: true
                            })
                        );
                        $.each(response.data, function (key, value) {

                            $('select[data-index="' + nextIndex + '"]').append(
                                $('<option>', {
                                    value: key,
                                    text: value
                                })
                            );
                        });
                    }
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
                    if (response.behavior === "fill_form") {
                        $.each(response.data, function (key, value) {
                            $("#" + key).val(value)
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

        if ($("#new_form").length) {
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
                    if (!$("input[name=" + key + "]").length) {
                        $("<input>").attr("type", "hidden").attr("name", key).attr("value", value).appendTo(newForm);
                    }
                });

                $('body').append(newForm);
                $("#new_form").submit();
                // setTimeout( function () {
                //
                // }, 1000);

                // clicked.closest("form").attr("target", "_blank");
            } else {
                clicked.closest("form").submit();
            }
        } else {
            if (disableButton) {
                clicked.attr("disabled", true);
            }
            ajaxOptParams = {};
            if (data.send_inputs === true) {

                let myform = $(clicked.closest('form'))[0];
                // let myform = $("#"+data.parent_form);

                $.map(data, function (value, key) {
                    if (data.parent_form === undefined) {
                        if (!$("input[name=" + key + "]").length) {
                            $("<input>").attr("type", "hidden").attr("name", key).attr("value", value).appendTo(myform);
                        }
                    } else {
                        if (!$("#" + data.parent_form + " > input[name=" + key + "]").length) {
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
                            } else if (jsonResponse.reload) {
                                console.log("reload")
                                window.location.reload();
                            } else if (jsonResponse.newPage) {
                                console.log("newPage")
                                if ($.isArray(jsonResponse.newPage)) {
                                    $.each(jsonResponse.newPage, function (idx, newPage) {
                                        window.open("", "_BLANK").document.write(newPage.page)
                                    })
                                } else {
                                    let target = jsonResponse.newPage.target !== undefined ? jsonResponse.newPage.target : '_self';
                                    let newPage = window.open("", target);
                                    newPage.document.write(jsonResponse.newPage.page);
                                }
                            }

                            //load form
                            else if (jsonResponse.modalForm) {
                                $('#modal-forms-body').html(jsonResponse.modalForm);
                                $('#modal-forms').modal('show');
                                load.fadeOut(200);
                            }
                            //load form
                            else if (jsonResponse.modalFormBS5) {
                                let modalId = jsonResponse.modalFormBS5.id ?? 'champsModalId'
                                let divModal = $('#champs-modal');
                                divModal.prepend(jsonResponse.modalFormBS5.form);
                                let chamspsModal = new bootstrap.Modal(document.getElementById(modalId), {
                                    keyboard: true, backdrop: true, focus: true
                                })
                                chamspsModal.show();
                                load.fadeOut(200);
                                // $('#modal-forms-body').html(jsonResponse.modalForm);
                                // $('#modal-forms').modal('show');
                                // load.fadeOut(200);
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
            if ($("#myDiv").length) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }
        }
    });

    $('.collapse').on('show.bs.collapse', function () {
        $(this).parent().removeClass("zeroPadding");
    });

    $('.collapse').on('hide.bs.collapse', function () {
        $(this).parent().addClass("zeroPadding");
    });


    // // SELECT2
    // $('.select2').select2({
    //
    //     language: "pt-BR",
    //     selectionCssClass: "teste",
    //     theme: "bootstrap"
    // });
    //
    // // DATATABLES
    // $(".datatable").each(function (index, element, set) {
    //     $(element).DataTable({
    //         responsive: true,
    //         "pageLength": 15,
    //         "language": {
    //             "sEmptyTable": "Nenhum registro encontrado",
    //             "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    //             "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
    //             "sInfoFiltered": "(Filtrados de _MAX_ registros)",
    //             "sInfoPostFix": "",
    //             "sInfoThousands": ".",
    //             "sLengthMenu": "_MENU_ resultados por página",
    //             "sLoadingRecords": "Carregando...",
    //             "sProcessing": "Processando...",
    //             "sZeroRecords": "Nenhum registro encontrado",
    //             "sSearch": "Pesquisar",
    //             "oPaginate": {
    //                 "sNext": "Próximo",
    //                 "sPrevious": "Anterior",
    //                 "sFirst": "Primeiro",
    //                 "sLast": "Último"
    //             },
    //             "oAria": {
    //                 "sSortAscending": ": Ordenar colunas de forma ascendente",
    //                 "sSortDescending": ": Ordenar colunas de forma descendente"
    //             }
    //         },
    //     });
    // });

});

// JS FUNCTION UTILS
//Verifica se CPF ou CGC e encaminha para a devida função, no caso do cpf/cgc estar digitado sem mascara
function verifica_cpf_cnpj(cpf_cnpj) {
    if (cpf_cnpj.length == 11) {
        return (verifica_cpf(cpf_cnpj));
    } else if (cpf_cnpj.length == 14) {
        return (verifica_cnpj(cpf_cnpj));
    } else {
        return false;
    }
    return true;
}

//Verifica se o número de CPF informado é válido
function verifica_cpf(sequencia) {
    if (Procura_Str(1, sequencia, '00000000000,11111111111,22222222222,33333333333,44444444444,55555555555,66666666666,77777777777,88888888888,99999999999,00000000191,19100000000') > 0) {
        return false;
    }
    seq = sequencia;
    soma = 0;
    multiplicador = 2;
    for (f = seq.length - 3; f >= 0; f--) {
        soma += seq.substring(f, f + 1) * multiplicador;
        multiplicador++;
    }
    resto = soma % 11;
    if (resto == 1 || resto == 0) {
        digito = 0;
    } else {
        digito = 11 - resto;
    }
    if (digito != seq.substring(seq.length - 2, seq.length - 1)) {
        return false;
    }
    soma = 0;
    multiplicador = 2;
    for (f = seq.length - 2; f >= 0; f--) {
        soma += seq.substring(f, f + 1) * multiplicador;
        multiplicador++;
    }
    resto = soma % 11;
    if (resto == 1 || resto == 0) {
        digito = 0;
    } else {
        digito = 11 - resto;
    }
    if (digito != seq.substring(seq.length - 1, seq.length)) {
        return false;
    }
    return true;
}

//Verifica se o número de CNPJ informado é válido
function verifica_cnpj(sequencia) {

    if (sequencia.length != 14) {
        return false;
    } else {

        seq = sequencia;
        soma = 0;
        multiplicador = 2;
        for (f = seq.length - 3; f >= 0; f--) {
            soma += seq.substring(f, f + 1) * multiplicador;
            if (multiplicador < 9) {
                multiplicador++;
            } else {
                multiplicador = 2;
            }
        }
        resto = soma % 11;
        if (resto == 1 || resto == 0) {
            digito = 0;
        } else {
            digito = 11 - resto;
        }
        if (digito != seq.substring(seq.length - 2, seq.length - 1)) {
            return false;
        }

        soma = 0;
        multiplicador = 2;
        for (f = seq.length - 2; f >= 0; f--) {
            soma += seq.substring(f, f + 1) * multiplicador;
            if (multiplicador < 9) {
                multiplicador++;
            } else {
                multiplicador = 2;
            }
        }
        resto = soma % 11;
        if (resto == 1 || resto == 0) {
            digito = 0;
        } else {
            digito = 11 - resto;
        }
        if (digito != seq.substring(seq.length - 1, seq.length)) {
            return false;
        }
        return true;
    }
}

// funcao para validar a inscrição estadual
function checkInscEstadual(pCampoIe, pCampoUf) {

    // console.log(pCampoIe);
    // console.log(pCampoUf.value);

    var theField = pCampoIe;
    var estado = pCampoUf.value;


    if (theField.value == "" || estado.value == "") {
        console.log("campos nao preenchidos");

    } else {
//	    var inscEst=retiraCaracteresInvalidos(theField.value);
        var inscEst = theField.value;

        if (inscEst != "") {
            var dig8 = "/BA*/RJ*";
            var dig9 = "/AL*/AP*/AM*/CE*/ES*/GO*/MA*/MS*/PA*/PB*/PI*/RN*/RR*/SC*/SE*/TO*";
            var dig10 = "/PR*/RS*";
            var dig11 = "/MT*";
            var dig12 = "/SP*";
            var dig13 = "/AC*/MG*/DF*";
            var dig14 = "/PE*/RO*";

            if (dig8.indexOf("/" + estado + "*") != -1) {
                inscEst = inscEst.substr(0, 8);
                tam = 8;
            } else if (dig9.indexOf("/" + estado + "*") != -1) {
                inscEst = inscEst.substr(0, 9);
                tam = 9;
            } else if (dig10.indexOf("/" + estado + "*") != -1) {
                inscEst = inscEst.substr(0, 10);
                tam = 10;
            } else if (dig11.indexOf("/" + estado + "*") != -1) {
                inscEst = inscEst.substr(0, 11);
                tam = 11;
            } else if (dig12.indexOf("/" + estado + "*") != -1) {
                inscEst = inscEst.substr(0, 12);
                tam = 12;
            } else if (dig13.indexOf("/" + estado + "*") != -1) {
                inscEst = inscEst.substr(0, 13);
                tam = 13;
            } else if (dig14.indexOf("/" + estado + "*") != -1) {
                inscEst = inscEst.substr(0, 14);
                tam = 14;
            } else {
                inscEst = "";
//	            campoIe.disabled = true;
                tam = 0;
            }
        }

        if (inscEst != "") {
//	    	console.log("campos preenchidos1");
            if (estado == "AC") {
                inscEst = leftPad(inscEst, 13);
                primDigito = 0;
                seguDigito = 0;
                pesos = "43298765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                pesos = "543298765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                seguDigito = 11 - (soma % 11);
                if (seguDigito > 9)
                    seguDigito = 0;

                if ((parseInt(inscEst.substr(11, 1)) != primDigito) || (parseInt(inscEst.substr(12, 1)) != seguDigito)) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true
            } else if (estado == "AL") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                soma = soma * 10;
                primDigito = soma % 11;
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "AP") {
                inscEst = leftPad(inscEst, 9);
                if (inscEst.substr(0, 2) != "03") {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else {
                    if (parseFloat(inscEst.substr(0, 8)) < 3017000) {
                        p = 5;
                        d = 0;
                    } else if (parseFloat(inscEst.substr(0, 8)) < 3019022) {
                        p = 9;
                        d = 1;
                    } else {
                        p = 0;
                        d = 0;
                    }
                    primDigito = 0;
                    pesos = "98765432";
                    soma = p;
                    for (i = 0; i < pesos.length; i++) {
                        soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                    }
                    primDigito = 11 - (soma % 11);
                    if (primDigito == 10)
                        primDigito = 0;
                    else if (primDigito == 11)
                        primDigito = d;
                    if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                        return false;
                    } else
//	                    theField.value=inscEst;
                        return true;
                }
            } else if (estado == "AM") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                if (soma < 11)
                    primDigito = 11 - soma;
                else {
                    primDigito = soma % 11;
                    if (primDigito < 2)
                        primDigito = 0;
                    else
                        primDigito = 11 - primDigito;
                }
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "BA") {
                inscEst = leftPad(inscEst, 8);
                primDigito = 0;
                seguDigito = 0;
                if ((parseInt(inscEst.substr(0, 1)) < 6) || (parseInt(inscEst.substr(0, 1)) == 8))
                    modulo = 10;
                else
                    modulo = 11;
                pesos = "765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                seguDigito = modulo - (soma % modulo);
                if (seguDigito > 9)
                    seguDigito = 0;
                var inscEstAux = inscEst;
                inscEst = inscEst.substr(0, 6) + "" + inscEst.substr(7, 1) + "" + inscEst.substr(6, 1);
                pesos = "8765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = modulo - (soma % modulo);
                if (primDigito > 9)
                    primDigito = 0;
                inscEst = inscEst.substr(0, 6) + "" + inscEst.substr(7, 1) + "" + inscEst.substr(6, 1);
                if ((parseInt(inscEst.substr(6, 1)) != primDigito) || (parseInt(inscEst.substr(7, 1)) != seguDigito)) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "CE") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "DF") {
                inscEst = leftPad(inscEst, 13);
                if (inscEst.substr(0, 2) != "07") {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else {
                    primDigito = 0;
                    seguDigito = 0;
                    pesos = "43298765432";
                    soma = 0;
                    for (i = 0; i < pesos.length; i++) {
                        soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                    }
                    primDigito = 11 - (soma % 11);
                    if (primDigito > 9)
                        primDigito = 0;
                    pesos = "543298765432";
                    soma = 0;
                    for (i = 0; i < pesos.length; i++) {
                        soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                    }
                    seguDigito = 11 - (soma % 11);
                    if (seguDigito > 9)
                        seguDigito = 0;

                    if ((parseInt(inscEst.substr(11, 1)) != primDigito) || (parseInt(inscEst.substr(12, 1)) != seguDigito)) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                        return false;
                    } else
//	                    theField.value=inscEst;
                        return true;
                }
            } else if (estado == "ES") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "GO") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (inscEst.substr(0, 8) == "11094402") {
                    if ((parseInt(inscEst.substr(8, 1)) != "0") && (parseInt(inscEst.substr(8, 1)) != "1")) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                        return false;
                    }
                } else {
                    if (primDigito == 11)
                        primDigito = 0;
                    else if (primDigito == 10) {
                        if ((parseFloat(inscEst.substr(0, 8)) >= 10103105) && (parseFloat(inscEst.substr(0, 8)) <= 10119997))
                            primDigito = 1;
                        else
                            primDigito = 0;
                    }
                    if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                        return false;
                    } else
//	                    theField.value=inscEst;
                        return true;
                }
            } else if (estado == "MA") {
                inscEst = leftPad(inscEst, 9);
                if (inscEst.substr(0, 2) != "12") {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else {
                    primDigito = 0;
                    pesos = "98765432";
                    soma = 0;
                    for (i = 0; i < pesos.length; i++) {
                        soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                    }
                    primDigito = 11 - (soma % 11);
                    if (primDigito > 9)
                        primDigito = 0;
                    if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                        return false;
                    } else
//	                    theField.value=inscEst;
                        return true;
                }
            } else if (estado == "MT") {
                inscEst = leftPad(inscEst, 11);
                primDigito = 0;
                pesos = "3298765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(10, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "MS") {
                inscEst = leftPad(inscEst, 9);
                if (inscEst.substr(0, 2) != "28") {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else {
                    primDigito = 0;
                    pesos = "98765432";
                    soma = 0;
                    for (i = 0; i < pesos.length; i++) {
                        soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                    }
                    primDigito = 11 - (soma % 11);
                    if (primDigito > 9)
                        primDigito = 0;
                    if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                        return false;
                    } else
//	                    theField.value=inscEst;
                        return true;
                }
            } else if (estado == "MG") {
                inscEst = leftPad(inscEst, 13);
                inscEst = inscEst.substr(0, 3) + "0" + inscEst.substr(3);
                primDigito = 0;
                seguDigito = 0;
                pesos = "121212121212";
                soma = 0;
                resultado = 0;
                for (i = 0; i < pesos.length; i++) {
                    resultado = parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1));
                    resultado = resultado + "";
                    for (j = 0; j < resultado.length; j++) {
                        soma = soma + (parseInt(resultado.substr(j, 1)));
                    }
                }
                somaAux = soma + "";
                primDigito = parseInt((parseInt(somaAux.substr(0, 1)) + 1) + "0") - soma;
                if (primDigito > 9)
                    primDigito = 0;
                inscEst = inscEst.substr(0, 3) + inscEst.substr(4);
                pesos = "321098765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    mult = parseInt(pesos.substr(i, 1));
                    if ((i > 1) && (i < 4))
                        mult = parseInt(pesos.substr(i, 1)) + 10;
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * mult);
                }
                seguDigito = 11 - (soma % 11);
                if (seguDigito > 9)
                    seguDigito = 0;

                if ((parseInt(inscEst.substr(11, 1)) != primDigito) || (parseInt(inscEst.substr(12, 1)) != seguDigito)) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "PA") {
                inscEst = leftPad(inscEst, 9);
                if (inscEst.substr(0, 2) != "15") {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else {
                    primDigito = 0;
                    pesos = "98765432";
                    soma = 0;
                    for (i = 0; i < pesos.length; i++) {
                        soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                    }
                    primDigito = 11 - (soma % 11);
                    if (primDigito > 9)
                        primDigito = 0;
                    if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                        return false
                    } else
//	                    theField.value=inscEst;
                        return true;
                }
            } else if (estado == "PB") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "PR") {
                inscEst = leftPad(inscEst, 10);
                primDigito = 0;
                seguDigito = 0;
                pesos = "32765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                pesos = "432765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                seguDigito = 11 - (soma % 11);
                if (seguDigito > 9)
                    seguDigito = 0;

                if ((parseInt(inscEst.substr(8, 1)) != primDigito) || (parseInt(inscEst.substr(9, 1)) != seguDigito)) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "PE") {
                inscEst = leftPad(inscEst, 14);
                primDigito = 0;
                pesos = "5432198765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = primDigito - 10;
                if (parseInt(inscEst.substr(13, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "PI") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "RJ") {
                inscEst = leftPad(inscEst, 8);
                primDigito = 0;
                pesos = "2765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(7, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "RN") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                soma = soma * 10;
                primDigito = soma % 11;
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "RS") {
                inscEst = leftPad(inscEst, 10);
                primDigito = 0;
                pesos = "298765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(9, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "RO") {
                inscEst = leftPad(inscEst, 14);
                primDigito = 0;
                pesos = "6543298765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito -= 10;
                if (parseInt(inscEst.substr(13, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "RR") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "12345678";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                soma = soma * 10;
                primDigito = soma % 9;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "SC") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                soma = soma * 10;
                primDigito = soma % 11;
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "SP") {

                console.log("validando");
                inscEst = leftPad(inscEst, 12);
                primDigito = 0;
                seguDigito = 0;
                pesos = "13456780";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    mult = parseInt(pesos.substr(i, 1));
                    if (i == 7)
                        mult = 10;
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * mult);
                }
                primDigito = soma % 11;
                if (primDigito > 9)
                    primDigito = 0;
                pesos = "32098765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    mult = parseInt(pesos.substr(i, 1));
                    if (i == 2)
                        mult = 10;
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * mult);
                }
                seguDigito = soma % 11;
                if (seguDigito > 9)
                    seguDigito = 0;

                if ((parseInt(inscEst.substr(8, 1)) != primDigito) || (parseInt(inscEst.substr(11, 1)) != seguDigito)) {
                    return false;
                } else
                    return true;
            } else if (estado == "SE") {
                inscEst = leftPad(inscEst, 9);
                primDigito = 0;
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                soma = soma * 10;
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                alert("Insc. Estadual inválida");
//	                theField.select();
//	                theField.focus();
                    return false;
                } else
//	                theField.value=inscEst;
                    return true;
            } else if (estado == "TO") {
                inscEst = leftPad(inscEst, 9); // 11 Se tiver 2 algarismos
                //if ((inscEst.substr(2,2) != "01") && (inscEst.substr(2,2) != "02") && (inscEst.substr(2,2) != "03") && (inscEst.substr(2,2) != "99")) {
                //    alert("Insc. Estadual inválida");
                //    theField.select();
                //    theField.focus();
                //}
                //else {
                primDigito = 0;
                //pesos="9800765432";
                pesos = "98765432";
                soma = 0;
                for (i = 0; i < pesos.length; i++) {
                    soma = soma + (parseInt(inscEst.substr(i, 1)) * parseInt(pesos.substr(i, 1)));
                }
                primDigito = 11 - (soma % 11);
                if (primDigito > 9)
                    primDigito = 0;
                if (parseInt(inscEst.substr(8, 1)) != primDigito) {
//	                    alert("Insc. Estadual inválida");
//	                    theField.select();
//	                    theField.focus();
                    return false;
                } else
//	                    theField.value=inscEst;
                    return true;
                //}
            }
        }
    } // fim else
}

function empty(str) {
    // if (typeof str == 'undefined' || !str || str.length === 0 || str === "" || !/[^\s]/.test(str) || /^\s*$/.test(str) || str.replace(/\s/g,"") === "") {
    if (typeof str == 'undefined' || !str || str.length === 0 || str === "" || !/[^\s]/.test(str) || /^\s*$/.test(str)) {
        return true;
    } else {
        return false;
    }
}

function gotoUrl(path, params, method, target) {
    //Null check
    method = method || "post"; // Set method to post by default if not specified.
    target = target || "_self"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    form.setAttribute("target", target)

    //Fill the hidden form
    if (typeof params === 'string') {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", 'data');
        hiddenField.setAttribute("value", params);
        form.appendChild(hiddenField);
    } else {
        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", key);
                if (typeof params[key] === 'object') {
                    hiddenField.setAttribute("value", JSON.stringify(params[key]));
                } else {
                    hiddenField.setAttribute("value", params[key]);
                }
                form.appendChild(hiddenField);
            }
        }
    }

    document.body.appendChild(form);
    form.submit();
}