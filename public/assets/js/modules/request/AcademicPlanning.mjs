import { Alert, Modal } from "../behavior/support.mjs";
import { Login } from "./login.mjs";

class AcademicPlanning{
    static path = '../../../';

    static async verityAcademicPlanning() {
    
        try {
            const response = await fetch(this.path+"api/get/academicPlanning/verityAcademicPlanning.php");
    
            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }
    
            const data = await response.json();
          
           
           if(data.status == 'success'){
               
            window.location.href = this.path+'views/administration/departments/academic-planning.html';
              
           }else {
                const body = document.querySelector('#warningModal .modal-body');
                const footer = document.querySelector('#warningModal .modal-footer');
                const warningModalLabel = document.getElementById('warningModalLabel');
                warningModalLabel.innerText = "";
                warningModalLabel.innerText = "Proceso de planificación académica";
                // Limpiar contenido existente
                body.innerHTML = '';
                footer.innerHTML = '';
            
                // Crear el contenedor centralizado
                const centeredContainer = document.createElement('div');
                centeredContainer.className = 'd-flex flex-column justify-content-center align-items-center text-center';
            
                // Crear y agregar imagen con animación
                const imgContainer = document.createElement('div');
                imgContainer.className = 'mb-4';
            
                const img = document.createElement('img');
                img.src = '../../../assets/img/icons/clock-icon.png';
                img.alt = '';
                img.className = 'animated-icon';
                imgContainer.appendChild(img);
            
                // Crear y agregar título
                const title = document.createElement('p');
                title.className = 'fs-4';
                title.textContent = 'El proceso de planificación academica aún no está activo.';
            
             
        
                // Crear y agregar párrafo de información adicional
                const infoParagraph = document.createElement('p');
                infoParagraph.className = 'mt-4';
                infoParagraph.innerHTML = `
                    Revisa las fechas del proceso en.
                    <a href="https://www.unah.edu.hn/calendarios" class="text-decoration-none text-primary fw-bold">
                        Calendarios
                    </a> 
                `;
            
                // Agregar todos los elementos al contenedor centralizado
                centeredContainer.appendChild(imgContainer);
                centeredContainer.appendChild(title);
                centeredContainer.appendChild(infoParagraph);
            
                // Agregar el contenedor al cuerpo del modal
                body.appendChild(centeredContainer);
              
                // Mostrar la modal
                Modal.showModal('warningModal');
            
            }

        } catch (error) {
            console.log(error);
            Alert.display('error', 'Algo ha salido mal', 'Lo sentimos');
        }
    }


    static async getDataAcademicSchedulesAcademicPlanning() {
        try {
            const response = await fetch(this.path+"api/get/academicPlanning/getDataAcademicSchedulesAcademicPlanning.php");

            if (!response.ok) {
                throw new Error("Error en la solicitud: " + response.status);
            }

            const data = await response.json();
            console.log(data);
            return data.data; 
             // Retorna los centros regionales
        } catch (error) {
          
            return [];  // Si hay un error, retornamos un array vacío
        }
    }
   
    static async regionalCentersAcademicPlanning(idProfessor) {
        const data = {
            username_user_professor: idProfessor  // Sustituye con el valor que quieras enviar
        };
    
        try {
            const response = await fetch(this.path + 'api/post/academicPlanning/regionalCentersAcademicPlanning.php', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),  
            });
    
            const responseData = await response.json();  
           
            return responseData.data; 
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }

    static async UndergraduatesAcademicPlanning(username_user_professor, id_regionalcenter ) {
        
        
        const data = {
            username_user_professor: username_user_professor,
            id_regionalcenter: id_regionalcenter
        };

        try {
            const response = await fetch(this.path + 'api/post/academicPlanning/UndergraduatesAcademicPlanning.php', {
                method: 'POST',
                headers: {  
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)  // Convierte el objeto JavaScript a JSON
            });
    
            const responseData = await response.json();  // Convierte la respuesta en formato JSON
          // console.log("Respuesta del servidor:", responseData);  // Maneja la respuesta
            return responseData.data;  
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }


    static async getDataProfessorsAcademicPlanning(regionalCenter, username_user_professor, days, startTime, endTime) {
        
        
        const data = {
            username_user_professor: username_user_professor,
            id_regionalcenter: regionalCenter,
            days: days,
            startTime: startTime,
            endTime: endTime
        };

        try {
            const response = await fetch(this.path + 'api/post/academicPlanning/PostDataProfessorsAcademicPlanning.php', {
                method: 'POST',
                headers: {  
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)  // Convierte el objeto JavaScript a JSON
            });
    
            const responseData = await response.json();  // Convierte la respuesta en formato JSON
            console.log("Respuesta del servidor:", responseData);  // Maneja la respuesta
            
          return responseData.data;  
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }


    static async getDataInfrastructureAcademicPlanning(username_user_professor, id_regionalcenter ) {
        
        
        const data = {
            username_user_professor: username_user_professor,
            id_regionalcenter: id_regionalcenter
        };

        try {
            const response = await fetch(this.path + 'api/post/academicPlanning/PostDataInfrastructureAcademicPlanning.php', {
                method: 'POST',
                headers: {   
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)  // Convierte el objeto JavaScript a JSON
            });
    
            const responseData = await response.json();  // Convierte la respuesta en formato JSON
       
          return responseData.data;  
           
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }
    

    static async postDataClassesAcademicPlanning(idUndergraduate, academicPeriodicity) {
        
        
        const data = {
            idUndergraduate: idUndergraduate,
            academicPeriodicity: academicPeriodicity
        };
       
        try {
            const response = await fetch(this.path + 'api/post/academicPlanning/PostDataClassesAcademicPlanning.php', {
                method: 'POST', 
                headers: {   
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)  // Convierte el objeto JavaScript a JSON
            });
    
            const responseData = await response.json();  // Convierte la respuesta en formato JSON
            console.log("Respuesta del servidor:", responseData);  // Maneja la respuesta
            
            if(responseData.status == 'succes'){
                Alert.display(responseData.status,'oh','Clases actualizadas','../../../../') 
            }else{
                Alert.display(responseData.status,'oh',responseData.message,'../../../../') 
            }

            return responseData.data;  
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }
    
    static async getClassSectionByDepartmentHeadAcademicPlanning(department_id, regional_center_id){
        
        const data = {
            department_id: department_id,
            regional_center_id: regional_center_id
        };
       
        
        try {
            const response = await fetch(this.path + 'api/post/academicPlanning/PostClassSectionByDepartmentHeadAcademicPlanning.php', {
                method: 'POST', 
                headers: {    
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)  // Convierte el objeto JavaScript a JSON
            });
    
            const responseData = await response.json();  // Convierte la respuesta en formato JSON
            console.log("Respuesta del servidor:", responseData);  // Maneja la respuesta
            
            if(responseData.status == 'succes'){
                Alert.display(responseData.status,'Enhorabuena','Secciones actualizadas','../../../../') 
            }else{
                Alert.display(responseData.status,'oh',responseData.message,'../../../../') 
            }

            return responseData.data;  
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }


    static async createClassSectionAcademicPlanning(formData){
        
        const data = {
            id_class: parseInt(formData.get('id_class'), 10),
            id_dates_academic_periodicity_year: parseInt(formData.get('id_dates_academic_periodicity_year'), 10),
            id_classroom_class_section:  parseInt(formData.get('id_classroom_class_section'), 10),
            id_academic_schedules:parseInt(formData.get('id_academic_schedules'), 10),
            id_professor_class_section:parseInt(formData.get('id_professor_class_section'), 10),
            numberof_spots_available_class_section: parseInt(formData.get('numberof_spots_available_class_section'), 10),
            status_class_section:  1,


        };
       
        console.log(data);
       try {
            const response = await fetch(this.path + 'api/post/academicPlanning/PostcreateClassSectionAcademicPlanning.php', {
                method: 'POST',  
                headers: {    
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)  
            });
    
            const responseData = await response.json();  
            console.log("Respuesta del servidor:", responseData);  
            
            if(responseData.status == 'succes'){
                Alert.display(responseData.status,'Enhorabuena','Secciones actualizadas','../../../../') 
            }else{
                Alert.display(responseData.status,'oh',responseData.message,'../../../../') 
            }

            return responseData.data; 
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }

    static async associateSlassSectionsDaysAcademicPlanning(formData){
        
        const data = {
            id_class: parseInt(formData.get('id_class'), 10),
            id_dates_academic_periodicity_year: parseInt(formData.get('id_dates_academic_periodicity_year'), 10),
            id_classroom_class_section:  parseInt(formData.get('id_classroom_class_section'), 10),
            id_academic_schedules:parseInt(formData.get('id_academic_schedules'), 10),
            id_professor_class_section:parseInt(formData.get('id_professor_class_section'), 10),
            numberof_spots_available_class_section: parseInt(formData.get('numberof_spots_available_class_section'), 10),
            status_class_section:  1,


        };
        console.log(data);
        try {
            const response = await fetch(this.path + 'api/post/academicPlanning/PostClassSectionsDaysAcademicPlanning.php', {
                method: 'POST',   
                headers: {    
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)  
            });
    
            const responseData = await response.json();  
            console.log("Respuesta del servidor:", responseData);  
            
            if(responseData.status == 'succes'){

                //Llamar al metodo que asocia días
                console.log('La sección insertada es');
                console.log(responseData.idClassSection);
                Alert.display(responseData.status,'Enhorabuena',responseData.message,'../../../../') 
            }else{
                Alert.display(responseData.status,'oh',responseData.message,'../../../../') 
            }

            return responseData.data; 
        } catch (error) {
            console.error('Error:', error);  
            return null;  
        }
    }
   
}

export {AcademicPlanning};