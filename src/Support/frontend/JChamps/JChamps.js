/****************************
 ***   SUPPORT FUNCTION   ***
 ****************************/

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
    if(el.tagName === 'SPAN' || el.tagName === 'DIV'){
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

    if (listEl.length === undefined){
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

if(document.body.dataset.box_load_effect === undefined
    || document.body.dataset.box_load_effect.toLowerCase() === 'true'){

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
        console.log("The data attribute 'group' is mandatory in select all checkbox element!")
        return;
    }
    const childrenElements = document.querySelectorAll(`.champs_checkbox_child_select[data-group=${parentCheckbox.dataset.group}]`);
    const counterElementSelector = parentCheckbox.dataset.counter_element !== undefined
        ? parentCheckbox.dataset.counter_element
        : ".champs_counter_checkbox";

    const counterEl = document.querySelectorAll(counterElementSelector);
    counter = childrenElements.length ?? 0;

    if(parentCheckbox.checked){
        childrenElements.forEach((el) => {
            el.checked = true;
        });
        fulfillElements(counterEl, counter);
    }else{
        childrenElements.forEach((el) => {
            el.checked = false;
        });
        fulfillElements(counterEl, 0);

    }
}

function checkBoxChildren(childCheckbox) {
    if (childCheckbox.dataset.group === undefined || !childCheckbox.dataset.group) {
        console.log("The data attribute 'group' is mandatory in select all children checkbox elements!")
        return;
    }

    if(childCheckbox.dataset.counter_element === undefined){
        return;
    }

    counterElementSelector = childCheckbox.dataset.counter_element;
    const counterEl = document.querySelectorAll(counterElementSelector);
    counter = counterEl[0].innerHTML ?? 0;

    if(childCheckbox.checked){
        counter++;
    }else{
        counter--;
    }
    fulfillElements(counterEl, counter);
    parentCheckbox = document.querySelector(`.champs_checkbox_parent_select[data-group=${childCheckbox.dataset.group}]`);
    childCheckboxes = document.querySelectorAll(`.champs_checkbox_child_select[data-group=${childCheckbox.dataset.group}]`);
    parentCheckbox.checked = counter === childCheckboxes.length;
}

// const parentCheckbox = document.querySelector(".champs_checkbox_parent_select");
// if (parentCheckbox !== null) {
//     parentCheckbox.addEventListener("click", (event) => {
//
//         var counter = 0;
//
//         if (parentCheckbox.dataset.children_class === undefined) {
//             console.log("The data attribute 'children_class' is mandatory in select all checkbox!")
//             return;
//         }
//         let childrenClass = parentCheckbox.dataset.children_class;
//         const childrenElements = document.querySelectorAll(`.${childrenClass}`);
//         const counterElementSelector = parentCheckbox.dataset.counter_element !== undefined
//             ? parentCheckbox.dataset.counter_element
//             : ".champs_counter_checkbox";
//
//         const counterEl = document.querySelectorAll(counterElementSelector);
//         counter = childrenElements.length ?? 0;
//
//         if(parentCheckbox.checked){
//             childrenElements.forEach((el) => {
//                 el.checked = true;
//             });
//             fulfillElements(counterEl, counter);
//         }else{
//             childrenElements.forEach((el) => {
//                 el.checked = false;
//             });
//             fulfillElements(counterEl, 0);
//
//         }
//     });
// }

// const childCheckboxes = document.querySelectorAll(".champs_checkbox_child_select");
// if (childCheckboxes !== null) {
//     childCheckboxes.forEach((childCheckbox) => {
//         childCheckbox.addEventListener("click", (event) => {
//             const clicked = event.target;
//
//             if(childCheckbox.dataset.counter_element === undefined){
//                 return;
//             }
//
//             counterElementSelector = childCheckbox.dataset.counter_element;
//             const counterEl = document.querySelectorAll(counterElementSelector);
//             counter = counterEl[0].innerHTML ?? 0;
//
//             if(clicked.checked){
//                 counter++;
//             }else{
//                 counter--;
//             }
//             fulfillElements(counterEl, counter);
//             parentCheckbox.checked = counter === childCheckboxes.length;
//         });
//     });
// }


/*********************
 ***   MODAL   ***
 *********************/

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

async function fetchSendUnfinished(el) {

    // if element has attr disabled, cancel submit
    if (el.hasAttribute("disabled")) {
        return false;
    }

    // if data attr confirm, show the message to user and check if he wants continue
    if (el.hasAttribute("data-confirm")) {
        if (!confirm(el.dataset.confirm)) {
            return false;
        }
    }

    // let uploadValidation = el.hasAttribute("data-upload_validation")
    //     ? el.dataset.upload_validation.toLowerCase() === 'true'
    //     : false;
    //
    //
    //
    // if (uploadValidation) {
    //     const uploadValidationEl = document.querySelector(".upload_validation_element");
    //
    //     if (uploadValidationEl.files.length == 0) {
    //         alert("Select at least 1 files to upload.");
    //         return
    //     }
    //
    //     if (uploadValidationEl.files.length > uploadValidationEl.dataset.max_files_limit) {
    //         alert(`You can submit `);
    //         return
    //     }
    // }

    // disable the element if data attr disable_element_after_click is true
    let disableButtonAfterSend = el.dataset.disable_element_after_click === undefined
        ? false
        : el.dataset.disable_element_after_click.toLowerCase() === 'true';
    if (disableButtonAfterSend) {
        el.setAttribute('disabled', true);
    }

    // disable the element if data attr disable_element_after_click is true
    let withInputs = el.dataset.with_inputs === undefined
        ? true
        : el.dataset.with_inputs.toLowerCase() === 'true';

    // Confirm if the inputs must be send and if the parent form exists. If necessary, create a blank newForm
    const closestForm = el.closest("form");
    if (!withInputs || !closestForm) {
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


    // Create an input element for each data attribute. But delete this inputs if they already exists
    for (var d in el.dataset) {
        // if input exists, delete it
        let inputChampsSelector = sendForm.querySelector(`[data-inputchamps-${d}]`);
        if (inputChampsSelector) inputChampsSelector.remove();
        // create the new input
        let newInput = document.createElement("input");
        newInput.setAttribute("type", "hidden")
        newInput.setAttribute(`data-inputchamps-${d}`, "")
        newInput.setAttribute("name", d)
        newInput.setAttribute("value", el.dataset[d])
        sendForm.appendChild(newInput);
    }

    // create an object FormData with form inputs
    const formData = new FormData(sendForm);


    const connectionFetchApi = await fetch(el.dataset.route, {
        method: "POST",
        body: formData
    });

    let data = await connectionFetchApi.json();

    // show a message
    if (data.message) {
        ajaxMessage(data.message);
        return false;
    }

    // redirect
    if (data.redirect) {
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

    // champs modal
    if (data.modal) {
        toggleModal(data.modal);
        return false;
    }


    // modal form

    // modal form bs5

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

        console.log(zipcodeStr, validate_zip_code.test(zipcodeStr))
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

        console.log(zipcodeSearchResolved)
        return zipcodeSearchResolved;
    } catch (error) {
        const message = "CEP inválido. Tente novamente!";
        if (!fulfillElements(errorMessage, message)) {
            alert(message)
        }
        document.querySelector(".champs_zipcode_search").focus();
        console.log(error);
    }
}

const zipcode = document.querySelector(".champs_zipcode_search");
if(zipcode !== null){
    zipcode.addEventListener("change", () => zipcodeSearch(zipcode));
}

/***************************
 ***   ANIMATE MESSAGE   ***
 ***************************/

let mileSecondsTimeWait = 5000;

const animateMessages = async () => {
    messageTimes = document.querySelectorAll(".message_time");
    messageTimes.forEach((messageTime) => {
        messageTime.animate([{"width": "100%"}, {"width": "0%"}], mileSecondsTimeWait);
    });
    await wait(mileSecondsTimeWait)
    messageTimes.forEach((messageTime) => {
        messageTime.parentElement.style.display = 'none';
    });
};

function ajaxMessage(message, time) {
    let ajaxResponse = document.querySelectorAll('.ajax_response');
    ajaxResponse.forEach((el) => {
        el.innerHTML = message
    });
    animateMessages();
}

animateMessages();

/**************************
 ***   HANDLER EVENTS   ***
 **************************/

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

    if (element.classList.contains("champs_send_by_post")) {
        event.preventDefault();
        fetchSend(element);
        return;
    }
}
document.addEventListener("click", champsRuntimeClickEventsHandler);