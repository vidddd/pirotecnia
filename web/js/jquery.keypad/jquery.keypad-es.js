/* http://keith-wood.name/keypad.html
   Spanish initialisation for the jQuery keypad extension
   Written by Cristhian Benitez (cbenitez@gmail.com). */
(function($) { // hide the namespace
	$.keypad.regional['es'] = {
		buttonText: '...', buttonStatus: 'Abrir el teclado',
		closeText: 'Cerrar', closeStatus: 'Cerrar el teclado',
		clearText: 'Limpiar', clearStatus: 'Eliminar todo el texto',
		backText: 'Borrar', backStatus: 'Borrar el caracter anterior',
		shiftText: 'Shift', shiftStatus: 'Cambiar mayusculas/minusculas',
		spacebarText: '&nbsp;', spacebarStatus: '',
		enterText: 'Enter', enterStatus: '',
		tabText: '→', tabStatus: '',
		alphabeticLayout: $.keypad.qwertyAlphabetic,
		fullLayout: $.keypad.qwertyLayout,
		isAlphabetic: $.keypad.isAlphabetic,
		isNumeric: $.keypad.isNumeric,
		toUpper: $.keypad.toUpper,
		showAnim: 'slideDown',
		keypadOnly: false,
		isRTL: false};
	$.keypad.setDefaults($.keypad.regional['es']);
})(jQuery);
