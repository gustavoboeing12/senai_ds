#include <math.h>
#include <stdio.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");

    int numeros[10];
    int num_maior_50 = 0;
    
    for(int i=0;i<10;i++){
    	printf("Digite o %d numero: \n",i+1);
    	scanf("%d",&numeros[i]);
    	if(numeros[i] > 50){
    		printf("O numero %d no indice %d eh maior que 50! \n",numeros[i],i);
    		num_maior_50++;
		}
	}
	if(num_maior_50 == 0){
		printf("Nenhum numero eh maior que 50");
	}
}