/****************************
 ***   SUPPORT FUNCTION   ***
 ****************************/

function champsParameters() {
    let request = new XMLHttpRequest();
    request.open("GET", `champs_parameters`, false);
    request.send(null)
    return JSON.parse(request.responseText);
}

/**
 * This function recept a list of elements and one value. So it loops all elements and put the value on each element
 *
 * If the list is empty, function returns false
 *
 * @param listEl
 * @param value
 * @returns {boolean}
 */
function fulfillElement(el, value) {
    if (el.tagName === 'SPAN' || el.tagName === 'DIV') {
        el.innerHTML = value;
        return
    }

    if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
        el.value = value;
        return
    }
}

function fulfillElements(listEl, value) {

    // if list is null, return
    if (!listEl) return false;

    // if list is empty, return
    if (listEl.length === 0) return false;

    if (listEl.length === undefined) {
        fulfillElement(listEl, value);
        return true;
    }

    listEl.forEach((el) => {
        fulfillElement(el, value);
    });

    return true;
}

function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * This function recept a string with the function name and an array of parameters, and if the function exists, claa it
 * @param fn
 * @param args
 */
function champsStringToFunction(fn, ...args) {
    let func = (typeof fn == "string") ? window[fn] : fn;
    if (typeof func == "function") func(...args);
    else throw new Error(`${fn} is Not a function!`);
}

/****************************************
 ***   DEFINING VARIABLES/CONSTANTS   ***
 ****************************************/

let parameters = champsParameters();
let notificationCenterOn = parameters.CHAMPS_NOTIFICATION_CENTER_ON ?? false;
let secondsToFadeout = parameters.CHAMPS_MESSAGE_TIMEOUT_SECONDS ?? 5;
let messageClass = parameters.CHAMPS_MESSAGE_CLASS ?? 'champs_message';
let messageErrorClass = parameters.CHAMPS_MESSAGE_ERROR ?? 'champs_error';
let messageTimeDiv = parameters.CHAMPS_MESSAGE_TIMEOUT_ON ? "<div class='champs_message_time'></div>" : "";
const messageTemplate = `<div class='${messageClass} ${messageErrorClass}'>[[message]]${messageTimeDiv}</div>`;


/*******************************
 ***   NOTIFICATION CENTER   ***
 *******************************/

/* Notification Center functions */

async function notificationsCount(el) {
    if (el.length > 0) {
        if (!el.dataset.route_count) {
            return;
        }

        var headers = {'X-Requested-With': 'XMLHttpRequest'};

        const connectionFetchApi = await fetch(el.dataset.route_count, {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(el.dataset)
        }).catch(err => {
            console.warn("erro", err);
            ajaxMessage(
                messageTemplate.replace('[[message]]', "Fail to send!")
                , secondsToFadeout);
            return false;
        });

        if (await connectionFetchApi === false) return false;

        let data = await connectionFetchApi.json();

        console.log(data);
        // $.post(center.data("count"), function (response) {
        //     if (response.count) {
        //         center.html(response.count);
        //     } else {
        //         center.html("0");
        //     }
        // }, "json");
    }
}

async function notifications(el) {
    let route = el.dataset.route_notify;
    if(route === undefined || route === null) {
        console.warn("The route_notify data attribute weren't set");
        return;
    }

    var headers = {'X-Requested-With': 'XMLHttpRequest'};

    const connectionFetchApi = await fetch(route, {
        method: 'POST',
        headers: headers,
        body: JSON.stringify(el.dataset)
    }).catch(err => {
        console.warn("erro", err);
        ajaxMessage(
            messageTemplate.replace('[[message]]', "Fail to send!")
            , secondsToFadeout);
        return false;
    });

    if (await connectionFetchApi === false) return false;

    let data = await connectionFetchApi.json();

    console.log(data);
}

function notificationHtml(link, image, notify, date, view) {

    unread = 'unread';
    if (view === true) {
        unread = '';
    }
    return '<div data-notificationlink="' + link + '" class="notification_center_item radius transition ' + unread + '">\n' +
        '    <div class="image">\n' +
        '        <img class="rounded" src="' + image + '"/>\n' +
        '    </div>\n' +
        '    <div class="info">\n' +
        '        <p class="title">' + notify + '</p>\n' +
        '        <p class="time icon-clock-o">' + date + '</p>\n' +
        '    </div>\n' +
        '</div>';
}

if(notificationCenterOn) {

    const notificationCenterOpen = document.querySelector('.champs_notification_center_open');
    if(!notificationCenterOpen){
        console.warn("The Notification Center feature is ON, but the .champs_notification_center_open element doesn't exists!");
    }else {

        /* create the notification center div */
        if(document.querySelector(".champs_notification_center") === null){
            let notifCenterDiv = document.createElement("div");
            notifCenterDiv.classList.add("champs_notification_center");
            document.body.insertBefore(notifCenterDiv, document.body.firstChild);
        }
        const notificationCenter = document.querySelector(".champs_notification_center");

        notificationsCount(notificationCenterOpen);

        setInterval(function () {
            notificationsCount(notificationCenterOpen);
        }, 1000 * 50);

        notificationCenterOpen.addEventListener("click", (e) => {
            e.preventDefault();

            el = e.target;

            notifications(el);

        });

// $(".notification_center_open").click(function (e) {
//
//
//
//     var notify = $(this).data("notify");
//     var center = $(".notification_center");
//
//     $.post(notify, function (response) {
//         if (response.message) {
//             ajaxMessage(response.message, ajaxResponseBaseTime);
//         }
//
//         var centerHtml = "";
//         if (response.notifications) {
//             $.each(response.notifications, function (e, notify) {
//                 centerHtml += notificationHtml(notify.link, notify.image, notify.title, notify.created_at, notify.view);
//             });
//
//             center.html(centerHtml);
//
//             center.css("display", "block").animate({right: 0}, 200, function (e) {
//                 $("body").css("overflow", "hidden");
//             });
//         }
//     }, "json");
//
//     center.one("mouseleave", function () {
//         $(this).animate({right: '-320'}, 200, function (e) {
//             $("body").css("overflow", "auto");
//             $(this).css("display", "none");
//         });
//     });
//
//     notificationsCount();
// });

// $(".notification_center").on("click", "[data-notificationlink]", function () {
//     window.location.href = $(this).data("notificationlink");
// });
    }


}

/***************************
 ***   BOX LOAD EFFECT   ***
 ***************************/

/**
 * function to show the loader
 */
function boxLoadShow() {
    const boxLoad = document.querySelector(".champs_load")
    boxLoad.style.display = "flex";
}

/**
 * function to hide the loader
 */
function boxLoadHide() {
    const boxLoad = document.querySelector(".champs_load")
    boxLoad.style.display = "none";
}

/**
 * Here is checked if the loader div exists, and if don't create
 *
 * <div class="champs_load" style="z-index: 999;">
 *     <div class="champs_load_box">
 *         <div class="champs_load_box_circle"></div>
 *         <p class="champs_load_box_title">Aguarde, carregando...</p>
 *     </div>
 * </div>
 */
if (document.querySelector(".champs_load") === null) {
    // creating champs_load
    const load = document.createElement("div");
    load.classList.add("champs_load");
    load.style.zIndex = 999;
    load.style.display = "none";
    // creating champs_load_box
    const loadBox = document.createElement("div");
    loadBox.classList.add("champs_load_box");
    // creating champs_load_box
    const loadBoxCircle = document.createElement("div");
    loadBoxCircle.classList.add("champs_load_box_circle");
    // creating champs_load_box
    const loadBoxTitle = document.createElement("p");
    loadBoxTitle.classList.add("champs_load_box_title");
    loadBoxTitle.innerText = "Aguarde, carregando...";
    // mounting the tree
    document.body.insertBefore(load, document.body.firstChild);
    load.appendChild(loadBox);
    loadBox.appendChild(loadBoxCircle);
    loadBox.appendChild(loadBoxTitle);
}

if (document.body.dataset.box_load_effect === undefined
    || document.body.dataset.box_load_effect.toLowerCase() === 'true') {

    document.querySelector(".champs_load").style.display = "flex"

    /**
     * Show the loader before navigate to another page
     */
    window.onbeforeunload = function () {
        boxLoadShow();
    };
    /**
     * Hide the loader after pega complete loaded
     */
    window.addEventListener('load', () => {
        boxLoadHide();
    });
}


/**************************************
 ***   SELECT/UNSELECT CHECKBOXES   ***
 **************************************/

function checkBoxParent(parentCheckbox) {
    var counter = 0;
    if (parentCheckbox.dataset.group === undefined || !parentCheckbox.dataset.group) {
        console.warn("The data attribute 'group' is mandatory in select all checkbox element!")
        return;
    }
    const childrenElements = document.querySelectorAll(`.champs_checkbox_child_select[data-group=${parentCheckbox.dataset.group}]`);
    const counterElementSelector = parentCheckbox.dataset.counter_element !== undefined
        ? parentCheckbox.dataset.counter_element
        : ".champs_counter_checkbox";

    const counterEl = document.querySelectorAll(counterElementSelector);
    counter = childrenElements.length ?? 0;

    if (parentCheckbox.checked) {
        childrenElements.forEach((el) => {
            el.checked = true;
        });
        fulfillElements(counterEl, counter);
    } else {
        childrenElements.forEach((el) => {
            el.checked = false;
        });
        fulfillElements(counterEl, 0);

    }
}

function checkBoxChildren(childCheckbox) {
    if (childCheckbox.dataset.group === undefined || !childCheckbox.dataset.group) {
        console.warn("The data attribute 'group' is mandatory in select all children checkbox elements!")
        return;
    }

    if (childCheckbox.dataset.counter_element === undefined) {
        return;
    }

    counterElementSelector = childCheckbox.dataset.counter_element;
    const counterEl = document.querySelectorAll(counterElementSelector);
    counter = counterEl[0].innerHTML ?? 0;

    if (childCheckbox.checked) {
        counter++;
    } else {
        counter--;
    }
    fulfillElements(counterEl, counter);
    parentCheckbox = document.querySelector(`.champs_checkbox_parent_select[data-group=${childCheckbox.dataset.group}]`);
    childCheckboxes = document.querySelectorAll(`.champs_checkbox_child_select[data-group=${childCheckbox.dataset.group}]`);
    parentCheckbox.checked = counter === childCheckboxes.length;
}


/**************************************
 ***   POPULATE CHILDREN ELEMENTS   ***
 **************************************/

/**
 * Populate children elements on parents update
 * [
 *    "data_post" => post_data_with_parent_information,
 *    "data_response" =>
 *    [
 *      "error" => null|error_message,
 *      "counter" => counter_number,
 *      "data" =>
 *      [
 *         "id" => "Value",
 *      ]
 *    ]
 * ]
 *
 * @param data
 */
function populateChildrenElements(data) {

    // selecting parent element
    if (!data.data_post.element_id) {
        console.error("The trigger element must have the id attribute set!")
        return;
    }
    let parentEl = document.getElementById(data.data_post.element_id)

    // select children elements
    if (!data.data_post.child_selector) {
        console.error("The data-child_selector attribute is missing. Without it is impossible find child element to update!")
        return;
    }
    let childEl = document.querySelector(data.data_post.child_selector);
    if (!childEl) {
        console.error("The child element hasn't found!")
        return;
    }

    // clear all input in group
    if (data.data_post.group) {
        parentEl.parentNode.querySelectorAll(`[data-group=${data.data_post.group}]`).forEach((item) => {
            if (parseInt(item.dataset.group_index) > parseInt(data.data_post.group_index)) {
                if (item.nodeName === 'SELECT') {
                    item.options.length = 0;
                    item.innerHTML = `<option value="" disabled selected>Selecione o menu anterior antes!</option>`;
                }
                if (item.nodeName === 'INPUT') item.value = '';
            }
        })
    }

    // identifying the child element type
    let childElType = childEl.nodeName;

    var dataValues = data.data_response;

    // if the child is an INPUT
    if (childElType === "INPUT") {
        if (dataValues.counter > 0) {
            Object.values(dataValues.data).forEach(function (value, index) {
                inputValue = value;
            });
        }

        childEl.value = inputValue;
    }

    // if the child is a SELECT
    if (childElType === "SELECT") {
        // clear current options
        childEl.options.length = 0;

        if (dataValues.counter === 0) {
            childEl.disabled = true;
            childEl.innerHTML = `<option value="" disabled selected>Não retornou nenhum registro</option>`;
        } else {
            childEl.disabled = false;
            let options = `<option value="" disabled selected>Selecione uma opção</option>`;

            Object.keys(dataValues.data).forEach(key => {
                options = `${options}<option value="${key}">${dataValues.data[key]}</option>`;
            });

            childEl.innerHTML = options;
        }
    }


    if (typeof updatedFieldsProps === "function") {
        updatedFieldsProps();
    }
}

/*****************
 ***   MODAL   ***
 *****************/

// create the champs_modal and champs_modal_fade in the DOM
const champsModalDiv = document.createElement("div");
champsModalDiv.id = "champs_modal";
champsModalDiv.classList.add("hide");
document.body.insertBefore(champsModalDiv, document.body.firstChild);
const champsFadeDiv = document.createElement("div");
champsFadeDiv.id = "champs_modal_fade";
champsFadeDiv.classList.add("hide");
document.body.insertBefore(champsFadeDiv, document.body.firstChild);

// create the constants of modal elements
const openModalButton = document.querySelectorAll(".champs_modal_open");
const closeModalButton = document.querySelectorAll(".champs_modal_close");
const champsModal = document.querySelector("#champs_modal");
const champsModalFade = document.querySelector("#champs_modal_fade");

// function to toogle the hide modal class
const toggleModal = (data = null) => {
    if (data) champsModal.innerHTML = data;
    champsModal.classList.toggle("hide");
    champsModalFade.classList.toggle("hide");
};

/*********************
 ***   fetchSend   ***
 *********************/

async function fetchSend(el) {

    if (!el.hasAttribute("id")) {
        console.error(`Set the id attribute in trigger element!`);
        return false;
    }
    el.dataset.element_id = el.id;

    var route = el.tagName === 'FORM' ? el.getAttribute('action') : el.dataset.route;

    if (route === undefined) {
        console.error(`The data-route attribute is missing in element!`);
        return false;
    }

    // if element has attr disabled, cancel submit
    var disable = el.hasAttribute("disabled") && el.getAttribute('disabled') !== 'false';
    if (disable) {
        return false;
    }

    // if data attr confirm, show the message to user and check if he wants continue
    if (el.hasAttribute("data-confirm") && el.dataset.confirm !== '') {
        if (!confirm(el.dataset.confirm)) {
            return false;
        }
    }

    // disable the element if data attr disable_element_after_click is true
    let disableButtonAfterSend = el.dataset.disable_element_after_click === undefined
        ? false
        : el.dataset.disable_element_after_click.toLowerCase() === 'true';
    if (disableButtonAfterSend) {
        el.setAttribute('disabled', true);
    }

    // disable the element if data attr disable_element_after_click is true
    let withInputs = el.tagName === 'FORM' || el.dataset.with_inputs === undefined
        ? true
        : el.dataset.with_inputs.toLowerCase() === 'true';

    // Confirm if the inputs must be send and if the parent form exists. If necessary, create a blank newForm
    const closestForm = el.closest("form");

    if (!withInputs || !closestForm) {

        /* if newForm already exists */
        let verifFormExists = document.getElementById('newForm');
        if(verifFormExists !== null){
            verifFormExists.remove();
        }
        /* create newForm */
        const newForm = document.createElement("form");
        newForm.setAttribute("id", 'newForm');
        document.body.appendChild(newForm)
    }
    const sendForm = withInputs && closestForm ? closestForm : document.getElementById("newForm");

    // upload validation
    const uploadValidationEl = sendForm.querySelectorAll("input[type=file]");
    var uploadOk = true;
    uploadValidationEl.forEach((uploadEl) => {
        if (uploadEl.hasAttribute('data-upload_required') && uploadEl.files.length === 0) {
            alert(`Necessario selecionar 1 arquivo no campo ${uploadEl.name}`);
            uploadOk = false;
            return false
        }

        if (uploadEl.hasAttribute('multiple') && uploadEl.hasAttribute('data-upload_max_files_limit')
            && uploadEl.files.length > uploadEl.dataset.upload_max_files_limit) {
            alert(`É possível enviar ${uploadEl.dataset.upload_max_files_limit} arquivos por vez no campo ${uploadEl.name}`);
            uploadOk = false;
            return false;
        }
    });
    if (!uploadOk) return false;

    // select the method
    var method = "POST"; // default form_method is POST
    var headers = {'X-Requested-With': 'XMLHttpRequest'};

    if (el.dataset.method && el.dataset.method.toUpperCase() === "DELETE") {
        method = "DELETE";
    }

    // delete all input data-input-runtime
    let inputChampsSelectors = sendForm.querySelectorAll(`[data-champs-input-runtime]`);
    inputChampsSelectors.forEach((inputChampsSelector) => {
        inputChampsSelector.remove();
    })

    // Create an input element for each data attribute. But delete this inputs if they already exists
    for (var d in el.dataset) {

        let searchFieldName = el.dataset.search_form_id_field_name === undefined ? el.name : el.dataset.search_form_id_field_name;
        let inputName = d === 'search_form' ? `search_form_field_${searchFieldName}` : d;
        let inputValue = () => {
            if(d === 'search_form'){
                return el.value;
            }

            if(el.dataset[d].substring(0, 13) === 'get_value_of_'){
                let elementInformed = document.querySelector(el.dataset[d].replace('get_value_of_', ''));
                if(!elementInformed){
                    return false;
                }
                return elementInformed.value
            }

            return el.dataset[d];
        };

        if(inputValue() === false) continue;

        if (sendForm.querySelector(`input[name='${d}']`)) {
            continue;
        }
        // create the new input
        let newInput = document.createElement("input");
        newInput.setAttribute("type", "hidden")
        newInput.setAttribute(`data-champs-input-runtime`, "")
        newInput.setAttribute("name", inputName)
        newInput.setAttribute("value", inputValue())
        sendForm.appendChild(newInput);
    }

    // create an object FormData with form inputs
    const bodyData = new FormData(sendForm);

    const connectionFetchApi = await fetch(route, {
        method: method,
        headers: headers,
        body: bodyData
    }).catch(err => {
        console.warn("erro", err);
        ajaxMessage(
            messageTemplate.replace('[[message]]', "Fail to send!")
            , secondsToFadeout);
        return false;
    });

    if(await connectionFetchApi === false) return false;

    let data = await connectionFetchApi.json();

    // show a message
    if (data.message) {
        el.setAttribute('disabled', false);
        ajaxMessage(data.message, secondsToFadeout);
        return false;
    }

    // redirect
    if (data.redirect) {
        console.warn(data.redirect);
        window.location.href = data.redirect;
        return false;
    }

    // reload
    if (data.reload) {
        window.location.reload();
        return false;
    }

    // new page or array of new pages
    if (data.newPage) {

        if (Array.isArray(data.newPage)) {
            data.newPage.forEach(function (item, idx) {
                let blankPage = window.open("", "_BLANK");
                blankPage.document.write(item.page);
                blankPage = null;
            })
        } else {
            let target = data.newPage.target !== undefined ? data.newPage.target : '_self';
            let newPage = window.open("", target);
            newPage.document.write(data.newPage.page);
        }
    }

    // populate children elements
    if (data.populate) {
        populateChildrenElements(data.populate);
        return false;
    }

    // champs modal
    if (data.modal) {
        toggleModal(data.modal);
        return false;
    }

    // modal form
    if (data.modalForm) {
        document.getElementById('#modal-forms-body').innerHTML = data.modalForm;
        document.getElementById('#modal-forms').classList.toggle('show');
        return false;
    }

    // modal form bs5
    if (data.modalFormBS5) {
        let modalId = data.modalFormBS5.id ?? 'champsModalId'

        let divModal = document.getElementById('modalBS5');
        console.log(divModal);
        if(divModal !== null){
            divModal.remove();
        }
        let newModalDiv = document.createElement('div');
        newModalDiv.id = 'modalBS5';
        newModalDiv.innerHTML = data.modalFormBS5.form;
        document.body.insertBefore(newModalDiv, document.body.firstElementChild)
        let chamspsModal = new bootstrap.Modal(document.getElementById(modalId), {
            keyboard: true, backdrop: true, focus: true
        })
        chamspsModal.show();
        return;
    }

    /**
     * Execute a user custom function
     * $json['customFunction'] = [
     *    "function" => "custom",
     *    "data" => $data
     * ];
     */
    if (data.customFunction) {
        champsStringToFunction(data.customFunction.function, data.customFunction.data)
        return false;
    }

}

/**************************
 ***   ZIPCODE SEARCH   ***
 **************************/

/**
 * Async function that consult the ViaCep API and fulfill the fields in form using classes bellow
 *  .champs_zipcode_search_street
 *  .champs_zipcode_search_city
 *  .champs_zipcode_search_state
 *  .champs_zipcode_search_neighborhood
 *
 *  The error will be shown in element with class .champs_zipcode_search_error, if the element was not found,
 *  an alert will appear
 *
 * @param zipcode
 * @returns {Promise<any>}
 */
async function zipcodeSearch(zipcode) {
    const errorMessage = document.querySelectorAll('.champs_zipcode_search_error');
    const street = document.querySelectorAll(".champs_zipcode_search_street");
    const neighborhood = document.querySelectorAll(".champs_zipcode_search_neighborhood");
    const city = document.querySelectorAll(".champs_zipcode_search_city");
    const state = document.querySelectorAll(".champs_zipcode_search_state");
    fulfillElements(errorMessage, '');
    fulfillElements(street, '');
    fulfillElements(neighborhood, '');
    fulfillElements(city, '');
    fulfillElements(state, '');
    try {

        var zipcodeStr = zipcode.value.replace(/\D/g, '');
        var validate_zip_code = /^[0-9]{8}$/;

        if (zipcodeStr === "" && !validate_zip_code.test(zip_code)) {
            throw Error('CEP informado é inválido!');
        }

        let zipcodeSearchApi = await fetch(`https://viacep.com.br/ws/${zipcodeStr}/json/`);
        var zipcodeSearchResolved = await zipcodeSearchApi.json();
        if (zipcodeSearchResolved.erro) {
            throw Error('CEP não encontrado!');
        }

        fulfillElements(street, zipcodeSearchResolved.logradouro);
        fulfillElements(neighborhood, zipcodeSearchResolved.bairro);
        fulfillElements(city, zipcodeSearchResolved.localidade);
        fulfillElements(state, zipcodeSearchResolved.uf);

        return zipcodeSearchResolved;
    } catch (error) {
        const message = "CEP inválido. Tente novamente!";
        if (!fulfillElements(errorMessage, message)) {

            ajaxMessage(
                messageTemplate.replace('[[message]]', message)
                , secondsToFadeout);
        }
        document.querySelector(".champs_zipcode_search").focus();
        console.error(error);
    }
}

/***************************
 ***   ANIMATE MESSAGE   ***
 ***************************/

if (!document.querySelector(".champs_post_response")) {
    const messageDiv = document.createElement("div");
    messageDiv.classList.add("champs_post_response");
    document.body.insertBefore(messageDiv, document.body.firstElementChild)
}

const animateMessages = async (secondsToFadeout) => {
    var ms = secondsToFadeout * 1000;
    messageTimes = document.querySelectorAll(".champs_message_time");
    messageTimes.forEach((messageTime) => {
        messageTime.animate([{"width": "100%"}, {"width": "0%"}], ms);
    });
    await wait(ms)
    messageTimes.forEach((messageTime) => {
        messageTime.parentElement.style.display = 'none';
    });
};

function ajaxMessage(message, secondsToFadeout) {
    let ajaxResponse = document.querySelectorAll('.champs_post_response');
    ajaxResponse.forEach((el) => {
        el.innerHTML = message
    });
    animateMessages(secondsToFadeout);
}

/**************************
 ***   HANDLER EVENTS   ***
 **************************/

/**
 * Monitoring the event click to manage runtime events
 *
 * @param event
 */
function champsRuntimeChangeEventsHandler(event) {
    let element = event.target;

    if (element.classList.contains("champs_zipcode_search")) {
        zipcodeSearch(element);
        return;
    }

    if (element.classList.contains("champs_send_post_on_update")) {
        event.preventDefault();
        fetchSend(element);
        return;
    }

    if (element.classList.contains("champs_search_in_model")) {
        performSearchInModel(element);
        return;
    }

}

/**
 * Monitoring the event click to manage runtime events
 *
 * @param event
 */
function champsRuntimeClickEventsHandler(event) {
    let element = event.target;
    if (element.classList.contains("champs_modal_close")) {
        toggleModal();
        return;
    }

    if (element.classList.contains("champs_checkbox_parent_select")) {
        checkBoxParent(element);
        return;
    }

    if (element.classList.contains("champs_checkbox_child_select")) {
        checkBoxChildren(element);
        return;
    }

    if (element.classList.contains("champs_send_post_on_click")) {
        event.preventDefault();
        fetchSend(element);
        return;
    }
}

function champsRuntimeSubmitEventsHandler(event) {
    let element = event.target;

    if (element.tagName === 'FORM' && element.classList.contains("champs_send_post_off") === false) {
        event.preventDefault();
        fetchSend(element);
        return;
    }

}

document.addEventListener("click", champsRuntimeClickEventsHandler);
document.addEventListener("change", champsRuntimeChangeEventsHandler);
document.addEventListener("submit", champsRuntimeSubmitEventsHandler);

/****************
 ***   INIT   ***
 ****************/

animateMessages(secondsToFadeout);


