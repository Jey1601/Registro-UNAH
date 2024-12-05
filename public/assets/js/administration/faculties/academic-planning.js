import { Sidebar } from "../../modules/behavior/support.mjs";
import { AcademicPlanning } from "../../modules/request/AcademicPlanning.mjs";

const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");


toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')

