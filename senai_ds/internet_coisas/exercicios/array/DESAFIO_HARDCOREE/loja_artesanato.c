#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    float val_unit[3];
    int quant_vend[3], c = 0;
    float val_tot = 0, val_geral = 0;
    
    for(int i=0;i<3;i++){
		printf("Digite o valor unitario do objeto %d \n",i+1);
		scanf("%f", &val_unit[i]);
		printf("Digite a quantidade vendida do objeto %d \n",i+1);
		scanf("%d",&quant_vend[i]);
	}
	printf("----Relatorio----");
	for(int i=0;i<3;i++){
		    printf("\n Quantidade vendida do objeto %d: %d \n",i+1, quant_vend[i]);
		    printf("\n Valor unitario do objeto %d: %.2f \n",i+1, val_unit[i]);
		    printf("\n Valor total do objeto %d: %.2f \n \n",i+1, val_unit[i]*quant_vend[i]);
		    while(c < 3){
		    	val_geral += val_unit[i];
			}
			printf("Valor geral das vendas: %.2f",val_geral);
			printf("Valor da comissão para o vendedor(5% sobre %.2f): ",val_geral, (val_geral*5)/100);
    }
	    
}