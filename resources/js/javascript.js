function ShowHide(IdLayer, IdImage) {
	var x;
	var SetDisplay;
	var SetImage;
	var Image = document.getElementById(IdImage).src;
	
	x = Image.split('/');
	if (x [x.length - 1] == 'icon_plus.png') {
		SetDisplay = 'block';
		SetImage = 'images/icon_minus.png';
	}
	else {
		SetDisplay = 'none';
		SetImage = 'images/icon_plus.png';
	}
	
	document.getElementById(IdImage).src = SetImage;
	document.getElementById(IdLayer).style.display = SetDisplay;
	document.getElementById(IdImage).onClick = function() {
		ShowHide(IdLayer, IdImage);
	}
}

function HideStatus() {
	window.status = '';
	return true;
}

function open_file(file) {
	window.open(file);
	return;
}

function mouseOver(elements) {
	if (elements.bgColor != '#faf287')
		$(elements).css('background-color', '#e6e6e6');
	//$(elements).css('font-weight', 'bold');
}

function mouseOut(elements) {
	if (elements.bgColor != '#faf287')
		$(elements).css('background-color', '#ffffff');
	//$(elements).css('font-weight', 'normal');
}