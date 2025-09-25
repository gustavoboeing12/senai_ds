#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	// Se o que o usuário digitar for igual a 'p', execute:
	if(getchar() == 'p'){
		printf("Você digitou p");
	// Senão:
	} else{
		printf("Você não digitou p");
	}
	
}