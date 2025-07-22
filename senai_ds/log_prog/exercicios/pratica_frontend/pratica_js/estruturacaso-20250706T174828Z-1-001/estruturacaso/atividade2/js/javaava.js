function validaFormulario(){
    if(document.getElementById('num1int').value==""){
        alert("Preencha o campo em branco");
        document.frmcadop.txtnum1int.focus();
        return false;
    }
    if(document.getElementById('num2int').value==""){
        alert("Preencha o campo em branco");
        document.frmcadop.txtnum2int.focus();
        return false;
    }
    if(document.getElementById('opint').value==""){
        alert("Preencha o campo em branco");
        document.frmcadop.txtopint.focus();
        return false;
    }else{
    let num1int=parseInt(document.getElementById('num1int').value);
    let num2int=parseInt(document.getElementById('num2int').value);
    let opint=parseInt(document.getElementById('opint').value);
    let result
    switch(opint){
    case 1:
        result = num1int + num2int
        console.log( num1int + "+"   + num2int + "=" + result );
        if(result%2==0){
          console.log("O número é par");
        }else{
            console.log("O número é impar")
        }
        return false;
        break;
    case 2:
        result = num1int - num2int
        console.log(num1int + "-" + num2int + "=" + result );
        if(result%2==0){
            console.log("O número é par");
        }else{
            console.log("O número é impar");
        }
        return false;
        break;
    case 3:
        result = num1int * num2int
       console.log(num1int + "x" + num2int + "=" + result);
       if(result%2==0){
        console.log("O número é par");
    }else{
        console.log("O número é impar");
    }
    return false;
    break;
    case 4:
        if(num1int==0 || num2int==0){
            console.log("Impossivel dividir por zero");
            return false;
         }
         else{
        result = num1int / num2int
        console.log(num1int + "/" + num2int + "=" + result);
        if(result%2==0){
         console.log("O número é par");
     }else{
         console.log("O número é impar");
         return false;
         break;
     }
     }
    }
    }
}