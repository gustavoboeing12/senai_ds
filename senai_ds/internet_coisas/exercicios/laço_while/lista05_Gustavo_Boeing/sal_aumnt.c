#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    float salario = 1000, aumento = 1.5;
    int ano, ano_inicio = 2020;
    
    printf("Digite o ano atual: \n");
    scanf("%d",&ano);
    
    while(ano > ano_inicio){
       salario += salario * (aumento/100);
       aumento *= 2;
       ano_inicio += 1;
	}
	printf("Novo salario: %.2f",salario);
}