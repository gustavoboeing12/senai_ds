#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int M[2][2], maior_num = 0, elemento;
    
    for(int l=0; l<2; l++){
    	for(int c=0; c<2; c++){
    		printf("Digite o elemento da linha %d e coluna %d \n",l+1,c+1);
    		scanf("%d",&elemento);
    		M[l][c] = elemento;
    		if(maior_num < elemento){
    			maior_num = elemento;
			}
		}
	} 
	printf("Resultado da matriz resultante: \n");
	for(int l=0; l<2; l++){
    	for(int c=0; c<2; c++){
    		printf("|%d| \n", M[l][c] * maior_num);
		}
	}  
}