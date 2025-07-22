function validaFormulario(){
        if(document.frmnumero.txtnum.value==""){
          alert("Preencha o campo número!!!!");
          document.frmnumero.txtnum.focus();
          return false;
        }
        let num=parseFloat(document.frmnumero.txtnum.value);
        
        if(num%2==0){
            if(num%3==0){
            alert("Este número atende aos critérios");
        }else{
            alert("Este número é par, porém não é divisível por 3");
        }
        }else{
            alert("O número não atende aos critérios");
        
        }
    }
