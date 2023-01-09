// On inverse les deux headers
function isStickyHeader(headerNotSticky, headerSticky) {
  headerNotSticky.classList.toggle("isSticky");
  headerSticky.classList.toggle("isSticky");
}


// function passwordPrompt() {
//   let prompt = window.prompt("Veuillez vérifier votre mot de passe :", "");
//   location.href = '/adminView/' + prompt;
// }


document.addEventListener("DOMContentLoaded", function(event) { 
  // la div sticky detection est positionné juste en dessous le header et permet quand elle touche le haut de la page, de modifier le header à afficher
  const el = document.querySelector(".sticky_detection")

  let headerNotSticky = document.getElementById("header_not_sticky");
  let headerSticky = document.getElementById("header_sticky");

window.addEventListener("load", (event) => {
  // Quand l'élément sticky_detection touche le haut de la page, on inverse le header a afficher
  const observer = new IntersectionObserver( 
    ([e]) => isStickyHeader(headerSticky,headerNotSticky),
    { threshold: [1] }
  );

  observer.observe(el);
}, false);
  // isStickyHeader(headerNotSticky, headerSticky);

  // Si l'utilisateur n'est pas en haut de la page, on affiche le petit header (si on ne fait pas ça, les headers peuvent s'inverser)
  // if (window.scrollY > 75) {
  //   headerNotSticky.classList.remove("isSticky");
  //   headerSticky.classList.add("isSticky");
  // }
  
  document.onreadystatechange = function sticky() {

    if(document.readyState == "complete"){

      if ( window.scrollY < 65) {
        headerNotSticky.classList.add("isSticky");
        headerSticky.classList.remove("isSticky");
      } else {
        headerNotSticky.classList.remove("isSticky");
        headerSticky.classList.add("isSticky");
}
    }

  }
  
});

