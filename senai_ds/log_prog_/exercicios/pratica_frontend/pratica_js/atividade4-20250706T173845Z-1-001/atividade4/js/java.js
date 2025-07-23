function validaFormulario(){
    if(document.frmnumeros.txtnum1.value==""){
        alert("Preencha o campo número 1");
        document.frmnumeros.txtnum1.focus();
        return false;
    }
    else if(document.frmnumeros.txtnum2.value==""){
        alert("Preencha o campo número 2");
        document.frmnumeros.txtnum2.focus=="";
        return false;
    }
    let num1=parseFloat(document.frmnumeros.txtnum1.value);
    let num2=parseFloat(document.frmnumeros.txtnum2.value);

    if(num1>num2){
        alert("O primeiro número(" + num1 + ")é maior que o segundo número(" + num2 + ")");

    }else if (num2>num1){
        alert("O segundo número (" + num2 + ")é maior que o primeiro número(" + num1 + ")");
    }else if(num2=num1){
        alert("Os dois números possuem os mesmos valores(" + num1 + " e " + num2 + ")");
    }

        }
    
