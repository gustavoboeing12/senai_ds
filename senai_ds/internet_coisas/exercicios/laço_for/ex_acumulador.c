#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int i, soma, num;
    
    soma = 0;
    
    for(i = 1;i <= 5;i++){
    	printf("Digite um número: ");
    	scanf("%d*c",&num);
    	soma += num;	
	}
	printf("Soma = %d",soma);
}