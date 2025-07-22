function validaFormulario(){
    let i= 1;
    let n = parseInt(prompt("Digite um número"));
    let resultado;
    
    for(i=1;i<=10;i++){
    resultado = i * n
        console.log(i + "x" + n + "=" + resultado);
    }
    return false;

}