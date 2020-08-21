$(".btn.answer").click(function() {
	if ($(this).closest('.question').next().length > 0) {
		$(this).closest('.question').hide().next().fadeIn();
	} else {
		// End of questions
	}
}
