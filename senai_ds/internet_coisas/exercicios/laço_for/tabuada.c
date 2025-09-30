#include <math.h>
#include <stdio.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	for(int x = 1;x <= 10;x++){
		for(int y = 1;y <= 10;y++){
			printf("%d x %d = %d\n",x,y,x*y);
		}
	}
}