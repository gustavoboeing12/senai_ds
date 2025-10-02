#include <math.h>
#include <stdio.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
	// Inicialiação de array e variável controladora
	int primo[9], divisores;
	
	printf("Teste de numero primo! \n");
    
    // Laço para obtenção dos 9 números
    for(int i = 0; i < 9; i++){
    	printf("Digite o numero %d: \n",i+1);
    	scanf("%d",&primo[i]);
    	
    	// Atribui 0 para verificar cada novo número digitado 
    	divisores = 0;
    	
    	// Laço para verificar se o número é primo
    	// Ele verifica quantas vezes o número pode ser dividido, até chegar nele mesmo.
        for(int y = 1; y <= primo[i]; y++){
        	// Se o resto da divisão for 0, ele é divisível pelo número em questão
        	if(primo[i] % y == 0){
        		divisores++;
			}
		}
		// Se o número possuir apenas 2 divisores:
	    if(divisores == 2){
		     printf("%d do indice %d eh primo \n",primo[i],i);
	    }
	}
}