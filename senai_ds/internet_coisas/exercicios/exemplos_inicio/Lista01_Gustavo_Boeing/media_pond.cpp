#include <math.h>
#include <locale.h>
int main()
{
	// Declaração das variáveis
    float nota1, nota2, nota3, media_pond;
    int peso1, peso2, peso3;
    
    // Pega as notas e seus respectivos pesos
    printf("Digite a primeira nota: ");
    scanf("%f%*c",&nota1);
    printf("Digite o peso da nota: ");
    scanf("%d%*c",&peso1);
    // 2
    printf("Digite a segunda nota: ");
    scanf("%f%*c",&nota2);
    printf("Digite o peso da nota: ");
    scanf("%d%*c",&peso2);
    // 3
    printf("Digite a terceira nota: ");
    scanf("%f%*c",&nota3);
    printf("Digite o peso da nota: ");
    scanf("%d%*c",&peso3);
    
    // Faz a média ponderada
    media_pond = (nota1*peso1 + nota2*peso2 + nota3*peso3)/(peso1+peso2+peso3);
    
    // Printa a média
    printf("A média é de: %0.2f%",media_pond);

}