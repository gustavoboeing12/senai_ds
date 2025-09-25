#include <stdio.h>
int main()
{
	// getchar pega o que o usuário digitar
	// Se for igual a p minúsculo, execute:
	if(getchar() == 'p'){
		printf("Você digitou p");
		printf("Pressione outra tecla");
		getchar();
	}
}