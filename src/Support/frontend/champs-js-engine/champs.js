/*
ANIMATE MESSAGE
 */

let mileSecondsTimeWait = 5000;

function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

const animateMessages =  async () => {
    messageTimes = document.querySelectorAll(".message_time");
    messageTimes.forEach((messageTime) => {
        messageTime.animate([{"width": "100%"}, {"width": "0%"}],mileSecondsTimeWait);
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