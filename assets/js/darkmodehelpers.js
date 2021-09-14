// use closure to cache result (color is only read once)
function _getBodyBackground() {
	var result = null;
	function inner() {
		if (result === null) {
			result = $('body').css( "background-color");
		}
		return result;
	}
	return inner;
}
getBodyBackground = _getBodyBackground();

// use closure to cache result (check it once is enough)
function _isDarkModeTheme() {
	var result = null;
	function inner() {
		if (result === null) {
			// TODO use better solution as soon as the themes know if they are dark or not
			// check if background color is white
			result = false;
			var background = getBodyBackground();
			if (background != ('rgb(255, 255, 255)')) {
				result = true;
			}
		}
		return result;
	}
	return inner;
}
isDarkModeTheme = _isDarkModeTheme();

function ifDarkModeThemeReturn(darkModeValue, notDarkModeValue) {
	if (isDarkModeTheme()) {
		return darkModeValue;
	}
	if (!notDarkModeValue) {
		return null;
	}
	return notDarkModeValue;
}
