function validaFormulario(){
    let i;
    let num=[];
    for(i=0;i<=6;i++){
    num[i] = parseInt(prompt("Digite um número"));
    console.log(num[i]);
    }
    for(i=0;i<6;i++){
      if(i%2==1){
        console.log("Os número que estão em posição ímpar são respectivamente:"+ num[i]);
      }
    }
    return false;
      
}