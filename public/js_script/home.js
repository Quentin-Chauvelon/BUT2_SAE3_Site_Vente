function passwordPrompt() {
    let prompt = window.prompt("Veuillez vérifier votre mot de passe :", "");
    location.href = '/adminView/' + prompt;
}

// function moveToSelected(element) {

//   if (element == "next")
//     let selected = document.getElementsByClassName(".next")
  
//   else if (element == "prev")
//     let selected = document.getElementsByClassName(".prev")
//   // } else {
//   //   let selected = element;

//   console.log(element)
//   console.log(selected)

//   let next = $(selected).next();
//   let prev = $(selected).prev();
//   let prevSecond = $(prev).prev();
//   let nextSecond = $(next).next();

//   $(selected).removeClass().addClass("selected");

//   $(prev).removeClass().addClass("prev");
//   $(next).removeClass().addClass("next");

//   $(nextSecond).removeClass().addClass("nextRightSecond");
//   $(prevSecond).removeClass().addClass("prevLeftSecond");

//   $(nextSecond).nextAll().removeClass().addClass('hideRight');
//   $(prevSecond).prevAll().removeClass().addClass('hideLeft');
// }

// $(document).keydown(function (e) {
//   switch (e.which) {
//     case 37:
//       moveToSelected('prev');
//       break;

//     case 39:
//       moveToSelected('next');
//       break;

//     default: return;
//   }
//   e.preventDefault();
// });



// $('#carousel div').click(function () {
//   moveToSelected($(this));
// });