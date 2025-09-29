#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	// Declaração de variáveis
    int x = 1;
    int y = 10;
    // Enquanto y for menor que x, faça:
    while(y > x){
    	printf("Valor de Y = %d \n",y);
    	// Decremento do Y em 2
    	y -= 2;
    	// Então ele volta á condição y>x, se for falsa, ele sai da estrutura
	}
	printf("\n Valor de Y depois de sair da estrutura = %d",y);
}