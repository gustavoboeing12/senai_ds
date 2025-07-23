function validaFormulario(){
    if(document.frmnota.txtnota1.value==""){
        alert("Preencha o campo de nota");
        document.frmnota.txtnota1.focus();
        return false;
    }
    else if(document.frmnota.txtnota2.value==""){
        alert("Preencha o campo de nota");
        document.frmnota.txtnota2.focus();
        return false
    }
    else if(document.frmnota.txtnota3.value==""){
        alert("Preencha o campo de nota");
        document.frmnota.txtnota3.focus();
        return false;
    }
    let nota1=parseFloat(document.frmnota.txtnota1.value);
    let nota2=parseFloat(document.frmnota.txtnota2.value);
    let nota3=parseFloat(document.frmnota.txtnota3.value);

    let media=(nota1+nota2+nota3)/3

    if(media >= 7){
    alert ("Aprovado"+media.toFixed(2));

    }else if (media >=5){
      alert("Você está de recperação. " + media.toFixed(2));
    }else{
            alert ("Reprovado"+media.toFixed(2));    
        }
        }
    
    
    
