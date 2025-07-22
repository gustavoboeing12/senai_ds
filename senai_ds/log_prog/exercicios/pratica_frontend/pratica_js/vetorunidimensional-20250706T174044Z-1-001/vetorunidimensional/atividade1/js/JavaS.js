function validaFormulario(){
   let notas = [];
   let cont = 0;
   let i;
   for(i=0;i<3;i++){
   notas[i]=parseFloat(prompt("Digite uma nota: "));
   console.log("A nota informada para a posição "+ i + " é: " + notas[i]);
   }
    console.log("As notas maiores que 7,5 foram: ");
   for(i=0;i<3;i++){
    if(notas[i]>=7.5){
        console.log(notas[i]);
        cont=cont+1
   }   
   console.log("A quantidade de notas superiores a 7.5 foi: " + cont);   
}
   return false;
}