function validaFormulario(){
        let i;
        let num = [];
        //Entrada de dados
        for(i=0;i<3;i++){
        
        num[i] = parseInt(prompt("Digite o número"));
        console.log("O valor do vetor num na posição " + i + " é " + num[i]);
        }
        return false;
}