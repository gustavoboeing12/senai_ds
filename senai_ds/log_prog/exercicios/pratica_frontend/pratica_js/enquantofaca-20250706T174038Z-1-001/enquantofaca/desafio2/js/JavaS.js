function validaFormulario(){

    A();
  
    return false;
  
  }
  
  function A(){
  
    let i = 1;
  
    let num = (prompt("Digite um número"));
    num = parseInt(num);
    console.log(num);
  
    let soma = 0;
    soma = soma + num;
  
    do{
  
       num = prompt("Digite 0 para sair e outro número para continuar: ");
       num = parseInt(num);
       
  
       if(num > 0){
  
          soma = soma + num;
          console.log(num);
       }
       
  
    }while(num != 0)
  
       console.log("O resultado da soma é:"+soma);
  
  }
  