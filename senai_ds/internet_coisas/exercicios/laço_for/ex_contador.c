#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int i,num,cont;
    
    cont = 0;
    
    for(i = 1; i <= 5; i++){
    	printf("Digite um número: ");
    	scanf("%d*c",&num);
    	if(num > 5){
    		cont++;
		}
	}
	printf("Quantidade de números maiores que 5 = %d",cont);
}