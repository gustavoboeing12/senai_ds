#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    float preco_un;
    char refrigeracao, categoria;
    int i = 1, prod, custo_estq = 0;
    
    printf("Digite a quantidade de produtos para analise: \n");
    scanf("%d",&prod);
    
    while(prod > 0){
    	printf("Digite o preco unitario do produto %d \n",i);
    	scanf("%f",&preco_un);
    	if(preco_un < 0){
    		printf("O preço não pode ser negativo");
    		break;
		}
    	printf("Digite a refrigerecao do produto(S ou N) %d \n",i);
    	scanf(" %c*c",&refrigeracao);
    	if(refrigeracao != 'S' && refrigeracao != 'N'){
    		printf("A refrigeracao deve ser em S ou N \n");
    		break;
		}
    	printf("Digite a categoria do produto %d\n (A-Alimentacao)(L-limpeza)(V-Vestuario) \n",i);
    	scanf(" %c*c",&categoria);
    	if(categoria != 'A' && categoria != 'L' && categoria != 'V'){
    		printf("A categoria deve ser em A, V ou L \n");
    		break;
		}
    	
    	if(preco_un < 20){
    		if(categoria == 'A'){
    			printf("O custo de estocagem e $2,00 \n");
    			custo_estq += 2;
			} else if(categoria == "L"){
				printf("O custo de estocagem e $3,00 \n");
    			custo_estq += 3;
			} else{
				printf("O custo de estocagem e $4,00 \n");
    			custo_estq += 4;
			}
		} else if(preco_un >= 20 && preco_un <= 50){
			if(refrigeracao == 'S'){
				printf("O custo de estocagem e $6,00 \n");
    			custo_estq += 6;
			} else{
				printf("O custo de estocagem e $0,00 \n");
			}
		} else{
			if(refrigeracao == 'S'){
				if(categoria == 'A'){
					printf("O custo de estocagem e $5,00 \n");
    		       	custo_estq += 5;
				} else if(categoria == 'L'){
						printf("O custo de estocagem e $2,00 \n");
    		        	custo_estq += 2;
				} else{
						printf("O custo de estocagem e $4,00 \n");
    			        custo_estq += 4;
				}
			} else{
				if(categoria == 'A' || categoria == 'V'){
						printf("O custo de estocagem e $0,00 \n");
				} else{
						printf("O custo de estocagem e $1,00 \n");
    			        custo_estq += 1;
				}
			}
		}
		 i += 1;
		 prod -= 1;
	}
	printf("O preco de estocagem total dos produtos e de: %.2f",custo_estq);
}