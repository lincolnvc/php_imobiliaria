function ChangeLang(a) {
	var b, elemento = "";
	if (document.createEvent) {
		var c = document.createEvent("HTMLEvents");
		c.initEvent("click", true, true)
	}
	if (a == 'pt') {
		elemento = $(".goog-te-banner-frame:eq(0)").contents().find("button[id*='restore']")
	} else {
		switch (a) {
		case 'de':
			b = "alem";
			break;
		case 'es':
			b = "espanhol";
			break;
		case 'fr':
			b = "fran";
			break;
		case 'en':
			b = "ing";
			break;
		case 'it':
			b = "italiano";
			break
		}
		elemento = $(".goog-te-menu-frame:eq(0)").contents().find("span:contains('" + b + "')");
	}
	if (elemento.length > 0) {
		if (document.createEvent) {
			elemento[0].dispatchEvent(c)
		} else {
			elemento[0].click()
		}
	}
}
function googleTranslateElementInit() {
	new google.translate.TranslateElement({
		pageLanguage: 'pt',
		autoDisplay: false,
		includedLanguages: 'de,es,fr,en,it',
		layout: google.translate.TranslateElement.InlineLayout.SIMPLE
	},
	'google_translate_element');
}