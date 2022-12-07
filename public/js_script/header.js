// la div sticky detection est positionné juste en dessous le header et permet quand elle touche le haut de la page, de modifier le header à afficher
const el = document.querySelector(".sticky_detection")

var headerNotSticky = document.getElementById("header_not_sticky");
var headerSticky = document.getElementById("header_sticky");


document.addEventListener("DOMContentLoaded", function(event) { 
  // document.getElementById("carrousel_previous_button").click(moveToSelected("prev"))
  document.getElementById("carrousel_previous_button").click(console.log("test"))

  document.getElementById("carrousel_next_button").click(moveToSelected("next"))

  // Si l'utilisateur n'est pas en haut de la page, on affiche le petit header (si on ne fait pas ça, les headers peuvent s'inverser)
  if (window.scrollY > 75) {
    headerNotSticky.classList.remove("isSticky");
    headerSticky.classList.add("isSticky");
  }
});

// On inverse les deux headers
function isStickyHeader() {
  headerNotSticky.classList.toggle("isSticky");
  headerSticky.classList.toggle("isSticky");
}

// Quand l'élément sticky_detection touche le haut de la page, on inverse le header a afficher
const observer = new IntersectionObserver( 
  ([e]) => isStickyHeader(),
  { threshold: [1] }
);

observer.observe(el);