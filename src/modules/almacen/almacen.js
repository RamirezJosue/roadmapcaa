document.querySelectorAll('.TotalTd1').forEach(function (TotalTd1) {
	if (TotalTd1.classList.length > 1) {
		var letra = TotalTd1.classList[1];
		var suma = 0;
		document.querySelectorAll('.ColTd1' + letra).forEach(function (celda) {
			var valor = parseInt(celda.innerHTML);
			suma += valor;
		});
		TotalTd1.innerHTML = suma;
	}
});
document.querySelectorAll('.TotalTd1').forEach(function (TotalTd1) {
	if (TotalTd1.classList.length > 1) {
		var letra = TotalTd1.classList[1];
		var suma = 0;
		document.querySelectorAll('.ColTd1' + letra).forEach(function (celda) {
			var valor = parseInt(celda.innerHTML);
			suma += valor;
		});
		TotalTd1.innerHTML = suma;
	}
}); 