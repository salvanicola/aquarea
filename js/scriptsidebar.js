function openNav() {
  document.getElementById("header").style.width = "100%";
  document.getElementById("headerformobile").style.display = "none";
  document.getElementById("imglogo").style.display = "none";
}

function closeNav() {
  document.getElementById("header").style.width = "0";
  document.getElementById("headerformobile").style.display = "initial";
  document.getElementById("imglogo").style.display = "initial";
}

function openMen() {
  document.getElementById("piscina").classList.toggle("show");
  var dropdowns = document.getElementsByClassName("overlay");
  var i;
  for (i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.classList.contains('show') && openDropdown.getAttribute('id')!='piscina') {
		//openDropdown.parentElement.style.paddingBottom = "8%";
      openDropdown.classList.remove('show');
    }
	//else if(openDropdown.getAttribute('id')=='piscina'){
		//openDropdown.parentElement.style.paddingBottom = "0";
	//}
  }
}

function openMen2(){
  document.getElementById("palestra").classList.toggle("show");
  var dropdowns = document.getElementsByClassName("overlay");
  var i;
  for (i = 0; i < dropdowns.length; i++) {
    var openDropdown = dropdowns[i];
    if (openDropdown.classList.contains('show') && openDropdown.getAttribute('id')!='palestra') {
		//openDropdown.parentElement.style.paddingBottom = "8%";
      openDropdown.classList.remove('show');
	  
    }
  }
}

var prevScrollpos = window.pageYOffset;
window.onscroll = function() {
var currentScrollPos = window.pageYOffset;
  if (prevScrollpos > currentScrollPos) {
    document.getElementById("headerformobile").style.top = "0";
  } else {
    document.getElementById("headerformobile").style.top = "-200px";
  }
  prevScrollpos = currentScrollPos;
}