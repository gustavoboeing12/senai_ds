#include <stdio.h>
#include <stdlib.h>
#include <locale.h>
int main()
{
	// Seta as letras para o português
	setlocale(LC_ALL,"Portuguese");
	
    int aprov, repr, ex, alunos;
    float nota1, nota2, media_al, media_cl;
    
    aprov = 0;
    repr = 0;
    ex = 0;
    alunos = 1;
    media_cl = 0;
    
    while(alunos <= 6){
    	printf("Digite as duas notas do aluno %d \n",alunos);
    	scanf("%f%f",&nota1,&nota2);
    	media_al = (nota1 + nota2)/2;
    	media_cl += media_al;
    	printf("Media: %.2f \n",media_al);
    	if(media_al >= 7){
    		printf("Aprovado! \n");
    		aprov += 1;
		} else if(media_al < 3){
			printf("Reprovado! \n");
			repr += 1;
		} else{
			printf("Exame \n");
			ex += 1;
		}
		alunos += 1;
	}
	media_cl /= 6;
	printf("Total de alunos reprovados: %d \n",repr);
	printf("Total de alunos em exame: %d \n",ex);
	printf("Total de alunos aprovados: %d \n",aprov);
	printf("Média da classe: %.2f \n",media_cl);
    
    
}