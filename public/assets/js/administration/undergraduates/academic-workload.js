import { Sidebar } from "../modules/behavior/support.mjs";

/* ========== Constantes  ============*/
const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");




toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);

Sidebar.buildSidebar('../../../')