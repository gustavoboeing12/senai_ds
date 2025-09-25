#include <math.h>
#include <locale.h>
int main()
{
	// Declaração das variáveis
    int num1, num2, num3, num4, total;
    
    // Pegando valores
    printf("Digite o primeiro número para a soma: ");
    scanf("%d%*c",&num1);
    printf("Digite o segundo número para a soma: ");
    scanf("%d%*c",&num2);
    printf("Digite o terceiro número para a soma: ");
    scanf("%d%*c",&num3);
    printf("Digite o quarto número para a soma: ");
    scanf("%d%*c",&num4);
    
    // Cálculo da soma
    total = num1 + num2 + num3 + num4;
    
    // Imprime o valor final
    printf("O valor total da soma dos 4 números é: %d%",total);

}