#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
#include <math.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    float num1, num2;
    
    printf("Digite 2 números quaisquer \n");
    scanf("%f%f",&num1,&num2);
    
    if(num1 > num2){
    	printf("Ordem dos números: %f,%f",num1,num2);
	} else{
		printf("Ordem dos números: %f,%f",num2,num1);
	}
}