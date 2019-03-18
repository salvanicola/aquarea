function openNav() {
  document.getElementById("header").style.width = "100%";
}

function closeNav() {
  document.getElementById("header").style.width = "0";
}

function openMen() {
  document.getElementById("piscina").classList.toggle("show");
  var dropdowns = document.getElementsByClassName("overlay");
  var i;
  for (i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.classList.contains('show') && openDropdown.getAttribute('id')!='piscina') {
      openDropdown.classList.remove('show');
    }
  }
}

function openMen2(){
  document.getElementById("palestra").classList.toggle("show");
  var dropdowns = document.getElementsByClassName("overlay");
  var i;
  for (i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.classList.contains('show') && openDropdown.getAttribute('id')!='palestra') {
      openDropdown.classList.remove('show');
    }
  }
}