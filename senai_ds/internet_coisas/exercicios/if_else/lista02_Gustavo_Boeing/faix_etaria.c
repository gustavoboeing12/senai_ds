#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
    int idade;
    printf("Digite sua idade \n");
    scanf("%d",&idade);
    if(idade < 11){
    	printf("Criança");
	} else if(idade >= 12 && idade<= 18){
		printf("Adolescente");
	} else if(idade >= 19 && idade <= 24){
		printf("Jovem");
	} else if(idade >= 25 && idade <= 59){
	    printf("Adulto");
	} else{
		printf("Idoso");
	}
}
	
