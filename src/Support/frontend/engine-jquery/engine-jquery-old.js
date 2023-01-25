$(function () {
    var ajaxResponseBaseTime = 5;
    var ajaxResponseRequestError = "<div class='message error icon-warning'>Desculpe mas não foi possível processar sua requisição...</div>";

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

    // /**
    //  * AJAX Submit if data-post attr exists
    //  */
    // $("[data-post]").click(function (e) {
    //     e.preventDefault();
    //
    //     var clicked = $(this);
    //     var data = clicked.data();
    //     var load = $(".ajax_load");
    //
    //     if (data.confirm) {
    //         var deleteConfirm = confirm(data.confirm);
    //         if (!deleteConfirm) {
    //             return;
    //         }
    //     }
    //
    //     let method = "POST"; // default form_method is POST
    //     if(data.method){
    //         if($.inArray(data.method.toUpperCase(), ["POST", "DELETE"])) {
    //             method = data.method.toUpperCase();
    //         }
    //     }
    //
    //     $.ajax({
    //         url: data.post,
    //         type: method,
    //         data: data,
    //         dataType: "json",
    //         beforeSend: function () {
    //             load.fadeIn(200).css("display", "flex");
    //         },
    //         success: function (response) {
    //             //redirect
    //             if (response.redirect) {
    //                 window.location.href = response.redirect;
    //             } else {
    //                 load.fadeOut(200);
    //             }
    //
    //             //reload
    //             if (response.reload) {
    //                 window.location.reload();
    //             } else {
    //                 load.fadeOut(200);
    //             }
    //
    //             //message
    //             if (response.message) {
    //                 ajaxMessage(response.message, ajaxResponseBaseTime);
    //             }
    //         },
    //         error: function () {
    //             ajaxMessage(ajaxResponseRequestError, 5);
    //             load.fadeOut();
    //         }
    //     });
    // });

    /**
     * AJAX Submit if the form hasn't class ajax_off
     */
    $("form:not('.ajax_off')").submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var load = $(".ajax_load");

        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.triggerSave();
        }

        let form_method = "POST"; // default form_method is POST
        if($.inArray(form.attr("method").toUpperCase(), ["POST", "DELETE"])) {
            form_method = form.attr("method").toUpperCase();
        }

        form.ajaxSubmit({
            url: form.attr("action"),
            type: form_method,
            dataType: "json",
            beforeSend: function () {
                load.fadeIn(200).css("display", "flex");
            },
            uploadProgress: function (event, position, total, completed) {
                var loaded = completed;
                var load_title = $(".ajax_load_box_title");
                load_title.text("Enviando (" + loaded + "%)");

                if (completed >= 100) {
                    load_title.text("Aguarde, carregando...");
                }
            },
            success: function (response) {
                //redirect

                if (response.redirect) {
                    window.location.href = response.redirect;
                } else {
                    form.find("input[type='file']").val(null);
                    load.fadeOut(200);
                }

                //reload
                if (response.reload) {
                    window.location.reload();
                } else {
                    load.fadeOut(200);
                }

                //message
                if (response.message) {
                    ajaxMessage(response.message, ajaxResponseBaseTime);
                }

                //image by fsphp mce upload
                if (response.mce_image) {
                    $('.mce_upload').fadeOut(200);
                    tinyMCE.activeEditor.insertContent(response.mce_image);
                }
            },
            complete: function () {
                if (form.data("reset") === true) {
                    form.trigger("reset");
                }
            },
            error: function () {
                ajaxMessage(ajaxResponseRequestError, 5);
                load.fadeOut();
            }
        });
    });

    $(document).on('click', '.sendOnClick', function (e) {
        e.preventDefault();

        $($(".ajax_response") ).empty();

        let clicked = $(this);
        let data = clicked.data();
        let load = $(".ajax_load");

        let method = "POST"; // default form_method is POST
        if(data.method){
            if($.inArray(data.method.toUpperCase(), ["POST", "DELETE"])) {
                method = data.method.toUpperCase();
            }
        }

        if(data.upload_validate){
            let upload_validate = $(".upload_validate");
            let upload_element = upload_validate[0];
            let upload_data = $(upload_element).data();

            if ($(upload_element)[0].files.length == 0) {
                alert("É necessário selecionar ao menos 1 arquivo." );
                return
            }

            if ($(upload_element)[0].files.length > upload_data.max_files) {
                alert("É possível importar até " + upload_data.max_files + " por vez" );
                return
            }
        }

        if (data.confirm) {
            var deleteConfirm = confirm(data.confirm);
            if (!deleteConfirm) {
                return;
            }
        }

        if (data.send_method == 'submit') {
            clicked.closest("form").submit();
        }else{
            if (data.send_inputs == true) {

                let myform = $(clicked.closest('form')).serializeArray();
                var myformObject = {};
                $.each(myform,
                    function(i, v) {
                        myformObject[v.name] = v.value;
                    });

                // let formData = getFormData(clicked.closest('form'));
                jsonData = {};
                $.extend(jsonData, myformObject, data);
            }else{
                jsonData = data;
            }

            $.ajax({
                url: data.post,
                type: method,
                data: jsonData,
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

                    //reload
                    if (response.reload) {
                        window.location.reload();
                    } else {
                        load.fadeOut(200);
                    }

                    //message
                    if (response.message) {
                        ajaxMessage(response.message, ajaxResponseBaseTime);
                    }
                },
                error: function () {
                    window.location.reload();
                    ajaxMessage(ajaxResponseRequestError, 5);
                    load.fadeOut();
                }
            });
        }


    });

    /**
     * if clicked element has sendAjaxPost class, do a AJAX post
     */
    $(".sendAjaxPost").click(function (e) {
        e.preventDefault();

        var clicked = $(this);
        var data = clicked.data();

        if (data.confirm) {
            var deleteConfirm = confirm(data.confirm);
            if (!deleteConfirm) {
                return;
            }
        }

        ajaxExecute(data.route, data);

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

    /**
     * ocultar e exibir senha
     *
     * no elemente que devera ser clicado para ocultar/exibir, colocar a classe show_password e o data-el_selector_id="id_do_input_com_a_senha"
     * no input="password" coloque o attr id="id_do_input_com_a_senha"
     *
     */
    $('.show_password').click(function(e) {
        e.preventDefault();
        let clicked = $(this);
        var attrData = clicked.data();
        let el = $("#"+attrData.el_selector_id);
        if ( el.attr('type') == 'password' ) {
            el.attr('type', 'text');
            clicked.attr('class', 'fa fa-eye');
        } else {
            el.attr('type', 'password');
            clicked.attr('class', 'fa fa-eye-slash');
        }
    });

    /**
     * Execute a zipcode search and populate addresses fields
     */
    $('.zip_code_search').change(function () {

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

    /**
     * jquery field masks
     */
    $(".mask-date").mask('00/00/0000');
    $(".mask-datetime").mask('00/00/0000 00:00');
    $(".mask-month").mask('00/0000', {reverse: true});
    $(".mask-doc").mask('000.000.000-00', {reverse: true});
    $(".mask-card").mask('0000  0000  0000  0000', {reverse: true});
    $(".mask-money").mask('000.000.000.000.000,00', {reverse: true, placeholder: "0,00"});

    /**
     * Function to performe an ajax comunication
     *
     * @param route
     * @param data
     */
    function ajaxExecute(route, data){
        var load = $(".ajax_load");
        selectMethod = 'POST';
        if(data.method){
            selectMethod = data.method;
        }

        $.ajax({
            url: route,
            type: selectMethod,
            data: data,
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

                //reload
                if (response.reload) {
                    window.location.reload();
                } else {
                    load.fadeOut(200);
                }

                //message
                if (response.message) {
                    ajaxMessage(response.message, ajaxResponseBaseTime);
                }
            },
            error: function () {
                ajaxMessage(ajaxResponseRequestError, 5);
                load.fadeOut();
            }
        });
    }

    /**
     * Function to view ajax response
     *
     * @param message
     * @param time
     */
    function ajaxMessage(message, time) {
        var ajaxMessage = $(message);

        ajaxMessage.append("<div class='message_time'></div>");
        ajaxMessage.find(".message_time").animate({"width": "100%"}, time * 1000, function () {
            $(this).parents(".message").fadeOut(200);
        });

        $(".ajax_response").append(ajaxMessage);
        ajaxMessage.effect("bounce");
    }

    /**
     * Get elements with ajax_response classes to view message results
     */
    $(".ajax_response .message").each(function (e, m) {
        ajaxMessage(m, ajaxResponseBaseTime += 1);
    });

    /**
     * AJAX message close on click
     */
    $(".ajax_response").on("click", ".message", function (e) {
        $(this).effect("bounce").fadeOut(1);
    });

    // permite o envio do formulario com o enter somente quando o foco esta no input submit
    $('form input:not([type="submit"],[class*="enterSubmit"])').keydown(function(e) {
        if (e.keyCode == 13) {
            var inputs = $(this).parents("form").eq(0).find(":input");
            if (inputs[inputs.index(this) + 1] != null) {
                inputs[inputs.index(this) + 1].focus();
            }
            e.preventDefault();
            return false;
        }
    });

    $('.print').click(function(e) {
        window.print();
    });
});

