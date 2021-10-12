/*
Encierro todo en una función asíncrona para poder usar async y await cómodamente
*/

let dateNow;
let currentDate;
let sortingSicDate = document.getElementById('fecha');

const getData = async (fecha) => {
    const respuestaRaw = await fetch(`https://bk77.co/json/dashboard/sortingSic.php?centro=BK77&Fecha=${fecha}`);
    const respuesta = await respuestaRaw.json();
    const etiquetas = respuesta.etiquetas; // <- Aquí estamos pasando el valor traído usando AJAX
    const datos = respuesta.datos; // <- Aquí estamos pasando el valor traído usando AJAX

    new Chart(document.getElementById("SortingSIC"), {
        type: 'line',
        data: {
            labels: etiquetas,
            datasets: [{
                label: "Horas",
                type: "line",
                label: 'Cajas Clasificadas',
                data: datos,
                fill: false,
                backgroundColor: "#0000FF",
                borderColor: "#0000FF",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                lineTension: 0.3,
                pointBackgroundColor: "#0000FF",
                pointBorderColor: "#0000FF",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "#0000FF",
                pointHoverBorderColor: "#0000FF",
                pointHoverBorderWidth: 2,
                pointRadius: 6,
                pointHitRadius: 10
            },
            {
                label: "",
                type: "line",
                fill: true,
                pointRadius: 0,
                backgroundColor: 'rgb(255, 0, 0)',
                data: [2500, 5000, 7500, 10000, 12500, 15000],
            },
            {
                label: "",
                type: "line",
                fill: true,
                pointRadius: 0,
                backgroundColor: 'rgb(255, 255, 0)',
                data: [0, 0, 0, 0, 0],
            },
            {
                label: "",
                type: "line",
                fill: true,
                pointRadius: 0,
                backgroundColor: 'rgb(0, 128, 0)',
                data: [20000, 20000, 20000, 20000, 20000, 20000],
            }
            ]
        },
        options: {
            scales: {
                xAxes: [{ stacked: false, barPercentage: 2 }],
                yAxes: [{ stacked: false }],
            },
            responsive: true,
            title: {
                display: true,
                text: 'SIC Clasificación Envases'
            },
            legend: {
                display: true,
                labels: {
                    boxWidth: 20,
                    padding: 10
                }
            },
            tooltips: {
                mode: 'label',
                intersect: false
            },
            animation: {
                duration: 0
            },
        }
    });
}

const getDataTwo = async (fecha) => {
    const respuestaRaw2 = await fetch(`https://bk77.co/json/dashboard/sortingClasificador.php?centro=BK77&Fecha=${fecha}`);
    const respuesta2 = await respuestaRaw2.json();
    const clasificador = respuesta2.clasificador; // <- Aquí estamos pasando el valor traído usando AJAX
    const Cajas = respuesta2.Cajas; // <- Aquí estamos pasando el valor traído usando AJAX
    const Horas = respuesta2.Horas; // <- Aquí estamos pasando el valor traído usando AJAX
    var barChartData = {
        labels: clasificador,
        datasets: [{
            label: 'Cajas',
            backgroundColor: 'rgb(224, 33, 36)',
            yAxisID: 'y-axis-1',
            data: Cajas,
        }, {
            label: 'Horas',
            backgroundColor: 'rgb(255, 242, 0)',
            yAxisID: 'y-axis-2',
            data: Horas,

        }]
    };
    new Chart(document.getElementById("SortingClasificador"), {

        type: 'bar',
        data: barChartData,
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Clasificación Envases Cajas y Horas'
            },
            tooltips: {
                mode: 'index',
                intersect: true
            },

            scales: {
                yAxes: [{
                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: 'left',
                    id: 'y-axis-1',
                }, {
                    type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                    display: true,
                    position: 'right',
                    id: 'y-axis-2',
                    gridLines: {
                        drawOnChartArea: false
                    }
                }],
            },
            legend: {
                display: true,
                labels: {
                    boxWidth: 20,
                    padding: 10
                }
            },
            animation: {
                duration: 0
            },
        }
    });
}


(async () => {
    await getData('2021-08-28');
    await getDataTwo('2021-08-28');
    dateNow = formatDate(new Date());
    sortingSicDate.value = dateNow;
})();

sortingSicDate.addEventListener('change', async ({ target: { value } }) => {
    currentDate = value;
    await getData(value)
    await getDataTwo(value)
});



function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}

setInterval(async () => {
   /// if (dateNow === currentDate) {
        await getData(dateNow);
        await getDataTwo(dateNow);
    //} 
}, 5000);







