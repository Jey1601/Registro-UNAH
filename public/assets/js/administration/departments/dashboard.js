import { Sidebar } from "../../modules/behavior/support.mjs";
import { Chart } from "../../modules/behavior/chart.mjs";
import { AcademicPlanning } from "../../modules/request/AcademicPlanning.mjs";
import { Login } from "../../modules/request/login.mjs";
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");

/**
 *
 * @author Jeyson Espinal (20201001015)
 * @created 2024-11
 */

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar("../../../");

document.addEventListener("DOMContentLoaded", function () {
  // Example usage
  const chartData = [
    { label: "Primary", percentage: 25, value: 250000000 },
    { label: "Secondary", percentage: 20, value: 200000000 },
    { label: "Tertiary", percentage: 18, value: 180000000 },
    { label: "Quaternary", percentage: 12, value: 120000000 },
    { label: "Quinary", percentage: 10, value: 100000000 },
    { label: "Senary", percentage: 8, value: 80000000 },
    { label: "Septenary", percentage: 3.5, value: 70000000 },
    { label: "Economia", percentage: 3.5, value: 70000000 },
  ];

  const container = document.querySelector("#pieChart");
  const chart2 = Chart.generatePieChart(chartData);
  if (chart2) container.appendChild(chart2);
});

const logoutBtn = document.getElementById("logoutBtn");
logoutBtn.addEventListener("click", function (event) {
  event.preventDefault();
  Login.logout("../../../index.html");
});
