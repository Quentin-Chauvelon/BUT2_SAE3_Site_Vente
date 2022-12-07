function moveToSelected(element) {

  if (element == "next")
    var selected = document.getElementsByClassName(".next")
  
  else if (element == "prev")
    var selected = document.getElementsByClassName(".prev")
  // } else {
  //   var selected = element;

  console.log(element)
  console.log(selected)

  // var next = $(selected).next();
  // var prev = $(selected).prev();
  // var prevSecond = $(prev).prev();
  // var nextSecond = $(next).next();

  // $(selected).removeClass().addClass("selected");

  // $(prev).removeClass().addClass("prev");
  // $(next).removeClass().addClass("next");

  // $(nextSecond).removeClass().addClass("nextRightSecond");
  // $(prevSecond).removeClass().addClass("prevLeftSecond");

  // $(nextSecond).nextAll().removeClass().addClass('hideRight');
  // $(prevSecond).prevAll().removeClass().addClass('hideLeft');
}

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