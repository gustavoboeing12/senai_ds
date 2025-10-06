#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int num;
int verifica_num(num){
	if(num >= 0){
		printf("O numero eh positivo!");
	} else{
		printf("O numero eh negativo!");
	}
}
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	printf("Digite um numero: \n");
	scanf("%d",&num);
	verifica_num(num);

	    
}