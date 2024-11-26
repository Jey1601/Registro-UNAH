  
class AnimationLoad{



  static hideSun() {
    const body = document.querySelector('body');
    setTimeout(()=>{
      
      body.classList.remove('animation');
      body.style.opacity='1';
      
    },2000)
   

  }
  

}


export{AnimationLoad};
