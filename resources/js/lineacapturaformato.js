window.formatInput = function (event) {
    let input = event.target;
    let value = input.value.replace(/\s+/g, ''); // Elimina todos los espacios
    let formattedValue = '';

    for (let i = 0; i < value.length; i += 6) {
        if (i > 0) {
            formattedValue += ' ';
        }
        formattedValue += value.substring(i, i + 6);
    }

    input.value = formattedValue;
};
