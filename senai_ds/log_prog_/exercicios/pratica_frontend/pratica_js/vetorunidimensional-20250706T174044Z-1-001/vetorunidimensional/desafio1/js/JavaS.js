function validaFormulario(){
    let num = [];
    let i;
    for(i=0;i<4;i++){
    num[i] = parseInt(prompt("Digite um número"));
    console.log("O número informado está na posição " + i + " e representa o " + num[i]);
    }
    console.log("Em ordem decrescente: ")
    for(i=3;i>=0;i--){
        console.log(num[i])
    }
    return false;
    }