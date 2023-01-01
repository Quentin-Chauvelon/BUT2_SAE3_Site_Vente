function passwordPrompt() {
    let prompt = window.prompt("Veuillez vérifier votre mot de passe :", "");
    location.href = '/adminView/' + prompt;
}


function PreviousClicked() {
    let previous = document.getElementsByClassName("previous")[0];
    let current = document.getElementsByClassName("current")[0];
    let next = document.getElementsByClassName("next")[0];

    previous.classList.remove("previous");
    previous.classList.add("next");

    current.classList.remove("current");
    current.classList.add("previous");
    
    next.classList.remove("next");
    next.classList.add("current");
}


function NextClicked() {
    let previous = document.getElementsByClassName("previous")[0];
    let current = document.getElementsByClassName("current")[0];
    let next = document.getElementsByClassName("next")[0];

    previous.classList.remove("previous");
    previous.classList.add("current");

    current.classList.remove("current");
    current.classList.add("next");
    
    next.classList.remove("next");
    next.classList.add("previous");
}