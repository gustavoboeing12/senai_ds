function validaFormulario(){
    if(document.getElementById('numint').value==""){
        alert("Preencha o campo vazio");
        document.frmnumint.txtnumint.focus();
        return false;
    }

    let i = 0
    let  numint = parseInt(document.getElementById('numint').value);
    
    while(i<=9){
        i = i + 1;
        console.log(numint + "x" + i + " = " + numint * i) ;
    }
    return false;
}

