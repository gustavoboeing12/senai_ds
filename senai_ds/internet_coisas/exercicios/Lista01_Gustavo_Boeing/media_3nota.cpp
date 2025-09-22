#include <math.h>
#include <locale.h>
int main()
{
	// Declaração das variáveis
    float nota1, nota2, nota3, media;
    
    // Pega as notas
    printf("Digite a primeira nota: ");
    scanf("%f%*c",&nota1);
    printf("Digite a segunda nota: ");
    scanf("%f%*c",&nota2);
    printf("Digite a terceira nota: ");
    scanf("%f%*c",&nota3);
    
    // Faz a média
    media = (nota1 + nota2 + nota3)/3;
    
    // Printa a média
    printf("A média é de: %0.2f%",media);

}