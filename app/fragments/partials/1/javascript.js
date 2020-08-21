inventoryCounter('inventory-counter');
function inventoryCounter(element) {
	let el = $("#" + element);
	let start = $(el).attr('data-start');
	let scopeStart = $(el).attr('data-scope-start');
	let scopeEnd = $(el).attr('data-scope-end');
	let direction = $(el).attr('data-direction');
	let refresh = $(el).attr('data-refresh');
	let currency = $(el).attr('data-currency');
	$(el).text(start + ' ' + currency);
	console.log();
	var interval = setInterval(function() {
		let rand = Math.floor(Math.random()*(scopeEnd-scopeStart+1)/1);
		if (direction == 'd') {
			start = parseInt(parseInt(start) - parseInt(rand));
		} else {
			start = parseInt(parseInt(start) + parseInt(rand));
		}
		if (start <= 0) {
			el.text('0');
			clearInterval(interval);
			return;
		}
		$(el).text(start + ' ' + currency);
	}, parseInt(refresh * 1000));
}