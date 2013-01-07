
jQuery(function($) {
  var tour = new Tour();
  tour.addStep({
    element: "#userWelcome",
    placement: "left",
    title: "Welcome to Marca!",
    content: "We hope you enjoy Marca. "
    + "Let's get started.",
    options: {
      labels: {next: "Let's go!", end: "Stop tour"}
    }
  });
  tour.addStep({
    element: "#userCourses",
    placement: "bottom",
    title: "First things first",
    content: "These are your courses.",
    options: {
      labels: {prev: "Back", next: "Cool", end: "Stop tour"}
    }
  });
  tour.start();

  if ( tour.ended() ) {
    $('<div class="alert">\
      <button class="close" data-dismiss="alert">×</button>\
      You ended the demo tour. <a href="" class="restart">Restart the tour.</a>\
      </div>').prependTo(".content").alert();
  }

  $(".restart").click(function (e) {
    e.preventDefault();
    tour.restart();
    $(this).parents(".alert").alert("close");
  });
});
