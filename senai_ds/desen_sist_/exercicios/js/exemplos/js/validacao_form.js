var formEl =document.getElementById("meuForm")

//Chama a função captura_eventos
captura_eventos(formEl,'submit',formValid);

//Função para capturar eventos
function captura_eventos(objeto, evento, funcao){
    //Teste addEventListener
    if(objeto.addEventListener)
        objeto.addEventListener(evento,funcaom,true)
    //Teste attachEvent
    else if(objeto.attachEvent){
        var evento = 'on' + evento;
        objeto.attachEvent(evento,funcao)
    }
}

//Função para cancelar eventos
function cancela_evento(evento){
    if(Event.preventDefault){
        Event.preventDefault()
    } else{
        window.Event.returnValue = false;
    }
}

//Função que verifica os campos radio e checkbox
function verificaCampos(campo){
    //variável que verifica os radios
    var checados = false;
    //verifica os radios
    for(var i=0;i<campo.length;i++)
        if(campo[i].checked){
            checados=true;
        }
        
        if(!checados){
            alert("Marque o campo "+ campo[0].name);
            cancela_evento(evento);
            return false;
        }
}

function formValid(event){
    //Pega os campos text e select
    var campoNome = formEl.textname.value;
        campoCidade = formEl.cidades;
        campoRadios = formEl.sexo;
        campoCheckbox = formEl.rede;

    //verifica campo de texto
    if(campoNome.length == 0){
        alert("O campo nome é obrigatório.");
        return false;
    }

    //Laço que percorre todas as opções
    for(var i=0;i<campoCidade.lenght;i++);
        if(campoCidade[i].selected){
            if(campoCidade[i],value == ""){
                alert("Selecione uma opção");
            cancela_evento(evento);
            }
        }
}

//Chama a função verificaCampos para o rádio
verificaCampos(campoRadios);

//Chama a função verificaCampos para o checkbox
verificaCampos(campoCheckbox);

