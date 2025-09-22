#include <math.h>
#include <locale.h>
int main()
{
	// Declaração de variáveis
    float deposito, juros, rendimento;
	
	// Pega o valor do depósito
    printf("Digite o valor do depósito: ");
    // Recebendo o valor digitado pelo usuário
    scanf("%f%*c", &deposito);
    
    // Pega o valor dos juros
    printf("Digite o valor dos juros(em decimal): ");
    // Recebendo o valor digitado pelo usuário
    scanf("%f%*c", &juros);
    
    // Cálculo do rendimento
    rendimento = deposito * juros;
    
    // Printa o rendimento isolado e conjunto
    printf("O valor do rendimento foi de: %0.2f%", rendimento);
    printf("O valor total do depósito junto ao rendimento foi de: %0.2f%", deposito + rendimento);
}