import { Sidebar } from "../modules/behavior/support.mjs";
import { Login } from "../modules/request/login.mjs";

const toggleSidebarButton = document.getElementById("toggleSidebar");
const closeSidebarButton = document.getElementById("closeSidebar");

const path ='../../';

toggleSidebarButton.addEventListener("click", Sidebar.toggleSidebar);
closeSidebarButton.addEventListener("click", Sidebar.toggleSidebar);


Sidebar.buildSidebar('../../');

const logoutBtn = document.getElementById('logoutBtn');
logoutBtn.addEventListener('click', function(event){
    event.preventDefault();
    Login.logout(path+'/index.html')
});  

