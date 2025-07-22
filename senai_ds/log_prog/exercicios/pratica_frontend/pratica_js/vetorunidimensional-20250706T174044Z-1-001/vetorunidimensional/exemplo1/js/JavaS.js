function validaFormulario(){
    let i;
    let text = [];
    for(i=0;i<4;i++){
        text[i]=(prompt("Digite um texto"));
    }
    for(i=0;i<4;i++){
        console.log("O texto digitado na posição " + i + " é " + text[i]);
    }
    return false;
}