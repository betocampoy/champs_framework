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
function fulfillElement(listEl, value) {
    // if list is empty, return
    if (listEl.length === 0) return false;

    listEl.forEach((el) => {
        if(el.tagName === 'SPAN' || el.tagName === 'DIV'){
            el.innerHTML = value;
            return
        }

        if (el.tagName === 'INPUT' || el.tagName === 'SELECT') {
            el.value = value;
            return
        }
    });

    return true;
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
    load.style.display = "flex";
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
    document.body.appendChild(load);
    load.appendChild(loadBox);
    loadBox.appendChild(loadBoxCircle);
    loadBox.appendChild(loadBoxTitle);
}

/**
 * Show the loader before navigate to another page
 */
window.onbeforeunload = function () {
    boxLoadShow()
};

/**
 * Hide the loader after pega complete loaded
 */
window.addEventListener('load', () => {
    boxLoadHide()
});

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
    fulfillElement(errorMessage, '');
    fulfillElement(street, '');
    fulfillElement(neighborhood, '');
    fulfillElement(city, '');
    fulfillElement(state, '');
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

        fulfillElement(street, zipcodeSearchResolved.logradouro);
        fulfillElement(neighborhood, zipcodeSearchResolved.bairro);
        fulfillElement(city, zipcodeSearchResolved.localidade);
        fulfillElement(state, zipcodeSearchResolved.uf);

        console.log(zipcodeSearchResolved)
        return zipcodeSearchResolved;
    } catch (error) {
        const message = "CEP inválido. Tente novamente!";
        if (!fulfillElement(errorMessage, message)) {
            alert(message)
        }
        document.querySelector(".champs_zipcode_search").focus();
        console.log(error);
    }
}

const zipcode = document.querySelector(".champs_zipcode_search");
zipcode.addEventListener("change", () => zipcodeSearch(zipcode));

/***************************
 ***   ANIMATE MESSAGE   ***
 ***************************/

let mileSecondsTimeWait = 5000;

function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

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
        el.append(message)
    });
    animateMessages();
}

animateMessages();
