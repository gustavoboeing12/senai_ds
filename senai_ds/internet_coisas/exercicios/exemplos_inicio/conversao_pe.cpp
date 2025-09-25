#include <math.h>
#include <locale.h>
int main()
{
	// Declaração das variáveis
    float pe, jarda, milha, polegada;
	
	// Pega o valor de medida em pés
    printf("Digite o valor da medida em PÉS: ");
    scanf("%f%*c", &pe);
    
    // Conversão dos valores
    polegada = pe * 12;
    jarda = pe / 3;
    milha = jarda / 1760;
    
    // Printa os valores convertidos
    printf("Os valores convertidos em: ");
    printf("Polegadas %0.2f%: ",polegada);
    printf("Jardas %f0.2%: ",jarda);
    printf("Milhas %f0.2%: ",milha);
    
   
}