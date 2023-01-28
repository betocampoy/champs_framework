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

function fulfillElement(els, value) {
    console.log("aqui", els, value)

    // errorMessage.innerHTML = "";
}


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
    fulfillElement(errorMessage, '');
    try {
        let zipcodeSearchApi = await fetch(`https://viacep.com.br/ws/${zipcode}/json/`);
        var zipcodeSearchResolved = await zipcodeSearchApi.json();
        if (zipcodeSearchResolved.erro) {
            throw Error('CEP não encontrado!');
        }
        // var cidade = document.getElementById('cidade');
        // var logradouro = document.getElementById('endereco');
        // var estado = document.getElementById('estado');
        //
        // cidade.value = consultaCEPConvertida.localidade;
        // logradouro.value = consultaCEPConvertida.logradouro;
        // estado.value = consultaCEPConvertida.uf;

        console.log(zipcodeSearchResolved);
        return zipcodeSearchResolved;
    } catch (error) {
        errorMessage.innerHTML = `<p>CEP inválido. Tente novamente!</p>`
        console.log(error);
    }
}

let zipcode = document.querySelector(".champs_zipcode_search");
zipcode.addEventListener("change", () => zipcodeSearch(zipcode.value));

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
