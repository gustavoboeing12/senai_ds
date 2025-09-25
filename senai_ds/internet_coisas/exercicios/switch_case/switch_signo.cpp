#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int dia,mes;
    
    printf("Digite seu dia de aniversário\n: ");
    scanf("%d",&dia);
    printf("Agora, digite seu mês de aniversário(em número)\n: ");
    scanf("%d",&mes);
    
    switch(mes){
    	case 1:
    	    if(dia >= 20){
    	    	printf("Aquário");
			} else{
				printf("Capricórnio");
			}
    		break;
    	case 2:
    		if(dia >= 19){
    	    	printf("Peixes");
			} else{
				printf("Aquário");
			}
    		break;
    	case 3:
    		if(dia >= 21){
    	    	printf("Áries");
			} else{
				printf("Peixes");
			}
    		break;
    	case 4:
    		if(dia >= 20){
    	    	printf("Touro");
			} else{
				printf("Áries");
			}
    		break;
    	case 5:
    		if(dia >= 21){
    	    	printf("Gêmeos");
			} else{
				printf("Touro");
			}
    		break;
    	case 6:
    		if(dia >= 22){
    	    	printf("Câncer");
			} else{
				printf("Gêmeos");
			}
    		break;
    	case 7:
    		if(dia >= 23){
    	    	printf("Leão");
			} else{
				printf("Câncer");
			}
    		break;
    	case 8:
    		if(dia >= 23){
    	    	printf("Virgem");
			} else{
				printf("Leão");
			}
    		break;
    	case 9:
    		if(dia >= 23){
    	    	printf("Libra");
			} else{
				printf("Virgem");
			}
    		break;
    	case 10:
    		if(dia >= 23){
    	    	printf("Escorpião");
			} else{
				printf("Libra");
			}
    		break;
    	case 11:
    		if(dia >= 22){
    	    	printf("Sagitário");
			} else{
				printf("Escorpião");
			}
    		break;
    	case 12:
    		if(dia >= 22){
    	    	printf("Capricórnio");
			} else{
				printf("Sagitário");
			}
    		break;
    	default:
    		printf("Erro");
    		break;
	}
}