#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
#include <math.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    float num1, num2, num3;
    
    printf("Digite 3 números quaisquer \n");
    scanf("%f,%f,%f",&num1,&num2,&num3);
    
    if(num1 > num2 && num1 > num3){
    	printf("O maior número é: %f",num1);
	} else if(num2 > num1 && num2 > num3){
		printf("O maior número é: %f",num2);
	} else{
		printf("O maior número é: %f",num3);
	}
}