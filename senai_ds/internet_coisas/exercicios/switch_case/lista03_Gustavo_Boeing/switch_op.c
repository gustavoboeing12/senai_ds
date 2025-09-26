#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    char op;
    float num1,num2;
    
    printf("Digite um número, escolha um operador e digite outro número\n: ");
    scanf("%f %c %f",&num1,&op,&num2);
    
    switch(op){
    	case '+':
    		printf(" = %.2f", num1 + num2);
    		break;
    	case '-':
    		printf(" = %.2f", num1 - num2);
    		break;
    	case '*':
    		printf(" = %.2f", num1 * num2);
    		break;
    	case '/':
    		if(num2 == 0){
    		  printf("Não é possível dividir por 0");
         	}else{
         	  printf(" = %.2f", num1 / num2);
			}
    	  	break;
    	default:
    		printf("Operador inválido");
	}
}