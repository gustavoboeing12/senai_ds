#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int i,num_cria, tempo_vida,cont_masc,cont_fem,cont_temp24;
    char sexo;
    
    cont_masc = 0;
    cont_fem = 0;
    cont_temp24 = 0;
    
    printf("Diga a quantidade de criancas mortas no periodo:  ");
    scanf("%d",&num_cria);
    
    for(i = 1;i <= num_cria;i++){
    	printf("\n");
    	printf("Identifique o sexo da crianca %d(maiusculo): ",i);
    	scanf(" %c*c",&sexo);
    	if(sexo == 'M'){
    		cont_masc += 1;
		} else if(sexo == 'F'){
			cont_fem += 1;
		} else{
			printf("Opcao invalida!");
			break;
		}
		
    	printf("Identifique o tempo de vida da crianca %d(Meses): ",i);
    	scanf("%d*c",&tempo_vida);
    	if(tempo_vida <= 24){
    		cont_temp24 += 1;
		}
	}
	printf("Porcentagem de meninos mortas no periodo: %d \n",cont_masc*100/num_cria);
	printf("Porcentagem de meninas mortas no periodo: %d \n",cont_fem*100/num_cria);
	printf("Porcentagem de criancas que viveram 24 meses ou menos: %d \n",cont_temp24*100/num_cria);
}