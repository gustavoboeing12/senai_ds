function validaFormulario(){
        if (document.getElementById('numint').value==""){
        alert("O campo de número deve ser preenchido");
        document.frmnumint.txtnumint.focus();
        return false;
}
else{
      let numint=document.frmnumint.txtnumint.value;
      if(numint % 2 == 0){
         alert(" O número " + numint +  "  par");
      }else{
         alert("O número " + numint + " é impar")
      }
}
}