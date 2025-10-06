#include<stdio.h>
#include<string.h>
#include "C:\Users\gustavo_f_boeing\Documents\GitHub\GitHub\senai_ds\senai_ds\internet_coisas\exercicios\funcao_biblioteca/rotina.h"
int main(){
	setlocate(LC_ALL,"portuguese");
	int num1, num2, res;
	sub_rotina1();
	sub_rotina2();
	printf("Digite um numero: ");
	scanf("%d*c",&num1);
	printf("Digite um outro numero: ");
	scanf("%d*c",&num2);
	res=sub_rotina3(num1,num2);
	printf("Resultado = %d",res);
getchar();
return 0;
}