#include <math.h>
#include <stdio.h>
#include <locale.h>
#include <string.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");

    char carros[80], carro_eco[20];
    float consumo[5];
    int mais_eco = 0, i;
    
    for(i = 0;i < 5; i++){
    	printf("Digite o modelo de carro numero %d: \n",i+1);
    	scanf("%s",&carros[i]);
		printf("Digite o consumo do carro %d(km/l): \n",i+1);
		scanf("%f",&consumo[i]);
	}
	for(i = 0;i < 5; i++){
		if(i == 0 && consumo[i] > mais_eco){
			mais_eco == i;
		}
	}
	printf("O carro mais economico: %s",carros[mais_eco]);
}