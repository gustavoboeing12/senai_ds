#include <stdio.h>
int main()
{
	// Variável char
	char ch;
	printf("Digite uma letra entre A e Z");
	// Joga na variável ch o que o usuário digitar
	ch = getchar();
	// If interno, um dentro do outro
	// Se o ch for maior que A, execute:
	if(ch >= 'A'){
		// Se o ch for menor que Z, execute:
		if(ch <= 'Z'){
			printf("Você acertou");
		}
	}
}
