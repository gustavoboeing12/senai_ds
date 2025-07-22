function validaFormulario(){
    if(document.frmcadidade.txtidade.value==""){
        alert("Campo idade deve ser preenchido");
        document.frmcadidade.txtidade.focus();
        return false;
}else{
    let idade=document.frmcadidade.txtidade.value;
    if(idade>=18){
        alert("Você é maior de idade");
    }else{
        alert("Você é menor de idade");
    }
}

}