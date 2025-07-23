function validaFormulario(){
    if(document.frmmes.txtmes.value==""){
        alert("Preencha o campo mês");
        document.frmmes.txtmes.focus();
        return false;
    }
    else {
        let mes=parseFloat(document.frmmes.txtmes.value);
        switch(mes){
            case 1:
                console.log(" Mês" + mes + "é Janeiro");
                return false;
            break;
            case 2:
                console.log("O Mês " + mes + ", é Fevereiro");
                return false;
            break;
            case 3:
                console.log("O Mês " + mes + ", é Março");
                return false;
            break;
            case 4:
                console.log("O Mês " + mes + ", é Abril");
                return false;
            break;
            case 5:
                console.log("O Mês " + mes + ", é Maio");
                return false;
            break;
            case 6:
                console.log("O Mês " + mes + ", é Junho");
                return false;
            break;
            case 7:
                console.log("O Mês " + mes + ", é Julho");
                return false;
            break;
            case 8:
                console.log("O Mês " + mes + ", é Agosto");
                return false;
            break;
            case 9:
                console.log("O Mês " + mes + " ,é Setembro");
                return false;
            break;
            case 10:
                console.log("O Mês " + mes + ", é Outubro");
                return false;
            break;
            case 11:
                console.log("O Mês " + mes + ", é Novembro");
                return false;
            break;
            case 12:
                console.log("O Mês " + mes + ", é Dezembro");
                return false;
            break;
            default:
                console.log("Este mês (" + mes + "), é Inválido");
                return false;
            break;
        }
    }
}