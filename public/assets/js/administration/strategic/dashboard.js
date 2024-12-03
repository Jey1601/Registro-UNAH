import { Sidebar } from "../../modules/behavior/support.mjs";
import { Chart } from "../../modules/behavior/chart.mjs";
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

document.addEventListener("DOMContentLoaded", function() {
    const contenedor = document.getElementById("barChart"); 
    const barChartContainer = document.getElementById("barChartContainer"); 
    // Supongo que 'datosEstadistica' es un arreglo o estructura con los datos necesarios
    const datosEstadistica = [
        { day: "Lunes", value: 35 },
        { day: "Martes", value: 45 },
        { day: "Miércoles", value: 55 },
        { day: "Jueves", value: 75 },
        { day: "Viernes", value: 85 }
    ];

    const chart = Chart.generateBarChart("Días de la semana", datosEstadistica);
    contenedor.appendChild(chart);

    


});