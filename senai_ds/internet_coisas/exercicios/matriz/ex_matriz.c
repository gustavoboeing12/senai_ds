#include <math.h>
#include <stdio.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	int x[3][4];
	
    for(int l=0; l<3; l++){
    	printf("Elementos da linha %d",l);
    	for(int c=0; c<4; c++){
    		printf("|%d|",x[l,c]);
		}
		printf("\n");
    }
}