function validaFormulario(){

    let conta = 1;
    let somatoria = 0;

    while( conta <= 3 ){
    
        let numero = parseInt(prompt("Digite o número"));
        console.log("O número informado é: " + numero );
        somatoria = somatoria + numero;
        conta = conta + 1;
    }
        console.log("A somatória dos números é " + somatoria);
    
   return false;
}