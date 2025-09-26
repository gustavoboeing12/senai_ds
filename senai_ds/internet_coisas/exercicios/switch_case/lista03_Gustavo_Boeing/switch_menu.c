#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int opcao;
    float salario;
    
    printf("Digite seu salário atual:");
    scanf("%f",&salario);
    
    printf("Menu de opções: \n ");
    printf("1-Imposto\n 2-Novo salário\n 3-Classificação \n: ");
    scanf("%d",&opcao);
    
    switch(opcao){
    	case 1:
    		if(salario < 500){
    			printf("O imposto sobre seu salário é de: %.2f",salario*0.05);
			} else if(salario >= 500 && salario <= 850){
				printf("O imposto sobre seu salário é de: %.2f",salario*0.10);
			} else{
				 printf("O imposto sobre seu salário é de: %.2f",salario*0.15);
			}
    		break;
    	case 2:
    		if(salario > 1500){
    			printf("O seu salário aumentado é de: %.2f",salario + 25);
			} else if(salario >= 750 && salario <= 1500){
				printf("O seu salário aumentado é de: %.2f",salario + 50);
			} else if(salario >= 450 && salario < 750){
				printf("O seu salário aumentado é de: %.2f",salario + 75);
			} else{
				printf("O seu salário aumentado é de: %.2f",salario + 100);
			}
    		break;
    	case 3:
    		if(salario <= 700){
    			printf("Mal remunerado");
			} else{
				printf("Bem remunerado");
			}
    		break;
    	
    	default:
    		printf("Opção inválida");
	}
}