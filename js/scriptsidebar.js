function openNav() {
  document.getElementById("header").style.width = "100%";
}

function closeNav() {
  document.getElementById("header").style.width = "0";
}

function piscinafun() {
	var state=document.getElementById("piscina").style.height;
	console.log(state);
	if(state != "0px") {
		setClose();
	}
	else{
		setOpen();	
	}
}

function setOpen(){
	console.log("im opening the menu");
	document.getElementById("piscina").style.height="100%";
}

function setClose(){
	document.getElementById("piscina").style.height="0";
}