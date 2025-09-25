#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
    int numero;
    printf("Digite um número qualquer \n");
    scanf("%d",&numero);
    if(numero < 0){
    	printf("Número menor que zero");
	} else if(numero <= 10){
		printf("Número >= 0 e < 10");
	} else if(numero <= 100){
		printf("Número >= 10 e < 100");
	} else{
		printf("Número >= 100");
	}
	
}