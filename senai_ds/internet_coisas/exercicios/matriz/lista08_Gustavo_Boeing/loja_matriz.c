#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	char lojas[8][30], prods[4][30];
	float precos[8][4];
	
	for(int i=0;i<8;i++){
		printf("Digite a loja %d \n",i+1);
		scanf("%s", lojas[i]);
	}
	for(int j=0;j<4;j++){
		printf("Digite o nome do produto %d \n",j+1);
		scanf("%s", prods[j]);
	}
	for(int i=0;i<8;i++){
		printf("\n Loja: %s \n",lojas[i]);
		for(int j=0;j<4;j++){
			printf("Digite o preco do produto %s: R$",prods[j]);
			scanf("%f",&precos[i][j]);
			
		}
	}
	for(int i=0;i<8;i++){
	    for(int j=0;j<4;j++){
		   if(precos[i][j] <= 60){	
		   	printf("\n Produto: %s | Loja: %s | Preco: R$ %.2f \n", prods[j],lojas[i],precos[i][j]);
		   }
        }
    }
}