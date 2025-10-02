#include <math.h>
#include <stdio.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");

    char carros[5];
    float consumo[5];
    int mais_eco = 0;
    
    for(int i=0;i<=5;i++){
    	printf("Digite o modelo de carro numero %d: \n",i);
    	scanf(" %c*c",&carros[i];
		printf("Digite o consumo desse carro(km/l): \n");
		scanf("%f",&consumo[i]);
		
		if(consumo[i] < mais_eco){
			mais_eco = consumo[i];
		}
	}
}