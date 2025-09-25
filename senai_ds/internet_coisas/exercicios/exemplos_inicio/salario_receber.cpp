#include <math.h>
#include <locale.h>
int main()
{
	// Declaração de variáveis
    float salario, sal_receber, gratifi, imposto;
	
	// Pegando o salário do funcionário
    printf("Digite o salário do funcionário: ");
    // Recebendo o valor digitado pelo usuário
    scanf("%f%*c", &salario);
    
    // Cálculo da gratificação
    gratifi = salario * 0.05;
    printf("Valor da gratificação: %0.2f%",gratifi);
    
    // Cálculo dos impostos
    imposto = salario * 0.07;
    printf("Valor dos impostos:  %0.2f%",imposto);
    
    // Cálculo do salário a receber
    sal_receber = salario + gratifi - imposto;
    //Printa o resultado final
    printf("O salário a receber é de: %0.2f%",sal_receber);
}