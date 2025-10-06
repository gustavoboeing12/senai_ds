#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int vet[18], matriz[3][6], q = 1;
    
    for(int i=0; i<18; i++){
    	printf("Digite o elemento numero %d: \n",i+1);
    	scanf("%d",&vet[i]);
	}
	for(int i=0; i<18; i++){
	   for(int l=0; l<3; l++){
		   for(int c=0; c<6; c++){
		   	   matriz[l][c] = vet[i];
			   i++;
			   printf("|%d|",matriz[l][c]);
               if(q % 6 == 0){
               	printf("\n");
			   }
			   q++;
	 	   }
	   }
}}