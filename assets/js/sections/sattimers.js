function update(i) {

  var now = new Date();
  if (dateArray[i] == 0) {
    var element = document.getElementById("tevel"+(i+1)/2+"Timer");
    element.innerHTML = "NOT FUNCTIONAL";
    element.classList.add("nonfunctional");
    element = document.getElementById("emoji"+(i+1)/2);
    element.innerHTML = "&#x1F480;";
    element = document.getElementById("tevel"+(i+1)/2+"Timeout");
    element.className = "expired";
    element = document.getElementById("tevel"+(i+1)/2+"AosTime");
    element.className = "expired";
    element = document.getElementById("tevel"+(i+1)/2+"LosTime");
    element.className = "expired";
    element = document.getElementById("tevel"+(i+1)/2+"Aos");
    element.className = "expired";
    element = document.getElementById("tevel"+(i+1)/2+"Los");
    element.className = "expired";
    element = document.getElementById("tevel"+(i+1)/2+"MaxEl");
    element.className = "expired";
    element = document.getElementById("tevel"+(i+1)/2+"Duration");
    element.className = "expired";
} else {

     var distance = parseInt(dateArray[i]) - new Date(now.getTime());
     var satDate = new Date(parseInt(dateArray[i]));

     var days = Math.floor(distance / (1000 * 60 * 60 * 24));
     var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
     var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
     var seconds = Math.floor((distance % (1000 * 60)) / 1000);

     if (distance < 0) {
       clearInterval(x);
       var element = document.getElementById("tevel"+(i+1)/2+"Timer");
       element.innerHTML = "TIMED OUT";
       element.classList.add("timedout");
       element = document.getElementById("emoji"+(i+1)/2);
       element.innerHTML = "&#x274C;";
       element = document.getElementById("tevel"+(i+1)/2+"Timeout");
       element.className = "expired";
       element.innerHTML = moment(satDate).format(custom_date_format)+" "+satDate.getUTCHours().toString().padStart(2, '0')+":"+satDate.getUTCMinutes().toString().padStart(2, '0');
       element = document.getElementById("tevel"+(i+1)/2+"AosTime");
       element.className = "expired";
       element = document.getElementById("tevel"+(i+1)/2+"LosTime");
       element.className = "expired";
       element = document.getElementById("tevel"+(i+1)/2+"Aos");
       element.className = "expired";
       element = document.getElementById("tevel"+(i+1)/2+"Los");
       element.className = "expired";
       element = document.getElementById("tevel"+(i+1)/2+"MaxEl");
       element.className = "expired";
       element = document.getElementById("tevel"+(i+1)/2+"Duration");
       element.className = "expired";
     } else {
       var element = document.getElementById("tevel"+(i+1)/2+"Timer");
       element.innerHTML = days + "d " + hours + "h " + minutes.toString().padStart(2, '0') + "m " + seconds.toString().padStart(2, '0') + "s";
       element.classList.add("active");
       element = document.getElementById("emoji"+(i+1)/2);
       element.innerHTML = "&#x2705;";
       element = document.getElementById("tevel"+(i+1)/2+"Timeout");
       element.innerHTML = moment(satDate).format(custom_date_format)+" "+satDate.getUTCHours().toString().padStart(2, '0')+":"+satDate.getUTCMinutes().toString().padStart(2, '0');
       if (dateArray[i+1] == 1) {
          element = document.getElementById("tevel"+(i+1)/2+"AosTime");
          element.className = "active";
          element = document.getElementById("tevel"+(i+1)/2+"LosTime");
          element.className = "active";
          element = document.getElementById("tevel"+(i+1)/2+"Aos");
          element.className = "active";
          element = document.getElementById("tevel"+(i+1)/2+"Los");
          element.className = "active";
          element = document.getElementById("tevel"+(i+1)/2+"MaxEl");
          element.className = "active";
          element = document.getElementById("tevel"+(i+1)/2+"Duration");
          element.className = "active";
       } else if (dateArray[i+1] == 0) {
          element = document.getElementById("tevel"+(i+1)/2+"AosTime");
          element.className = "timedout";
          element = document.getElementById("tevel"+(i+1)/2+"LosTime");
          element.className = "timedout";
          element = document.getElementById("tevel"+(i+1)/2+"Aos");
          element.className = "timedout";
          element = document.getElementById("tevel"+(i+1)/2+"Los");
          element.className = "timedout";
          element = document.getElementById("tevel"+(i+1)/2+"MaxEl");
          element.className = "timedout";
          element = document.getElementById("tevel"+(i+1)/2+"Duration");
          element.className = "timedout";
       }
     }
  }
}

for (let i=1; i<=26; i+=2) {
      var x = setInterval(function() {
               update(i);
            }, 1000);
}
