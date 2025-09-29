#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int x = 0;
    do{
    	printf("Valor de X = %d \n",x);
    	x += 1;
	} while(x != 5);
	
	printf("\n Valor de X depois de sar da estrutura = %d",x);
}