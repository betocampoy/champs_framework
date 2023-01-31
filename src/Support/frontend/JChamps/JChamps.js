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

const parentCheckbox = document.querySelector(".champs_checkbox_parent_select");
if (parentCheckbox !== null) {
    parentCheckbox.addEventListener("click", (event) => {

        var counter = 0;

        if (parentCheckbox.dataset.children_class === undefined) {
            console.log("The data attribute 'children_class' is mandatory in select all checkbox!")
            return;
        }
        let childrenClass = parentCheckbox.dataset.children_class;
        const childrenElements = document.querySelectorAll(`.${childrenClass}`);
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
    });
}

const childCheckboxes = document.querySelectorAll(".champs_checkbox_child_select");
if (childCheckboxes !== null) {
    childCheckboxes.forEach((childCheckbox) => {
        childCheckbox.addEventListener("click", (event) => {
            const clicked = event.target;

            if(childCheckbox.dataset.counter_element === undefined){
                return;
            }

            counterElementSelector = childCheckbox.dataset.counter_element;
            const counterEl = document.querySelectorAll(counterElementSelector);
            counter = counterEl[0].innerHTML ?? 0;

            if(clicked.checked){
                counter++;
            }else{
                counter--;
            }
            fulfillElements(counterEl, counter);
            parentCheckbox.checked = counter === childCheckboxes.length;
        });
    });
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
