#include <math.h>
#include <stdio.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	int X[10][6];
	
    for(int l=0; l<10; l++){
    	for(int c=0; c<6; c++){
    		X[l][c] = c;
    		printf("|%d|",X[l][c]);
		}
		printf("\n");
    }
}