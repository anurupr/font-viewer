 var timerStart = Date.now();

  $(document).ready(function() {
                 console.log("Time until DOMready: ", ( Date.now()-timerStart ) / 1000,"s");
 });
 $(window).load(function() {
     console.log("Time until everything loaded: ", (Date.now()-timerStart) / 1000,"s");
 });