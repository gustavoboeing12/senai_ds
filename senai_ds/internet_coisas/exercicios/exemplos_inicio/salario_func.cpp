#include <math.h>
#include <locale.h>
int main()
{
	// Declaração de variáveis
	float salario, sal_aument;
	
	// Pegando o salário do funcionário
    printf("Digite o salário do funcionário: ");
    // Recebendo o valor digitado pelo usuário
    scanf("%f%*c", &salario);
    
    // Calculando o aumento de 25%
    sal_aument = salario * 1.25;
    
    // Printa o resultado final
    printf("O novo salário é de: %0.2f%", sal_aument);
}