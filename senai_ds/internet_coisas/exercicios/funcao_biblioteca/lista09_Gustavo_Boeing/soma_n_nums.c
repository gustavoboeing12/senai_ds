#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int soma_n_nums(int x, int y){
	int soma = 0;
	if(y => x){
		printf("Soma = 0");
	} else{
		while(x > y){
			x++;
			soma += x;
		}
	}
	printf("Soma = %d",soma);
	return soma;
}
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	int num1, num2;
	
	printf("Digite um numero: \n");
	scanf("%d",&num1);
	printf("Digite um outro numero: \n");
	scanf("%d",&num2);
	soma_n_nums(num1,num2);

	    
}