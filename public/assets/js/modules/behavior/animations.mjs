  
class AnimationLoad{


   /**
   *
   * @author Jeyson Espinal (20201001015)
   * @created 2024-11
   */
  static hideSun() {
    const body = document.querySelector('body');
    setTimeout(()=>{
      
      body.classList.remove('animation');
      body.style.opacity='1';
      
    },2000)
   

  }
  

}


export{AnimationLoad};
