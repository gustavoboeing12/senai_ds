#include <stdio.h>
int main()
{
	// Variável char
	char ch;
	printf("Digite uma letra entre A e Z");
	// Joga na variável ch o que o usuário digitar
	ch = getchar();
	// Se for maior que A e também menor que Z, execute:
	if(ch >= 'A' && ch <= 'Z'){
		printf("Você acertou");
	
	}
}
