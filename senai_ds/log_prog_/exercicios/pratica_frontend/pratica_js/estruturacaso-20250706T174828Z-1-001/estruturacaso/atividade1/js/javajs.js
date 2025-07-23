function validaFormulario(){
    if(document.frmnumint.txtnumint.value==""){
        alert("Preencha o campo em branco ");
        document.frmnumint.txtnumint.focus();
        return false;
    }else{
    let numint=parseFloat(document.frmnumint.txtnumint.value);
    switch(true){
        case(numint >= 0 && numint <= 3):
            console.log("Essa idade representa um bebê!!");
            return false;
            break;
        case(numint >=4 && numint <= 10):
            console.log("Essa idade representa um adolescente!!");
            return false;
            break;
        case(numint >=11 && numint <=18):
        default:
            console.log("Essa idade representa um adulto!!");
            return false;
            break;
    }
    }
}