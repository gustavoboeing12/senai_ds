<!DOCTYPE html>
<html lang="pt-br"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de tarefas</title>
</head>
<body>
     <h1>Gerenciador de tarefas</h1>
     <form>
        <fieldset>
            <legend>Nova tarefa</legend>
            
            <label>Tarefa: </label>
            <input type="text" name="nome" />

            <label>Descrição(opicional): </label>
            <textarea name="descricao"></textarea>

            <label>Prazo(opicional): </label>
            <input type="text" name="prazo" />

            <fieldset>
              <legend>Prioridade</legend>
                 <label>Baixa</label>
                 <input type="radio" name="prioridade" value="baixa" checked />

                 <label>Média</label>
                 <input type="radio" name="prioridade" value="media" />

                 <label>Alta</label>
                 <input type="radio" name="prioridade" value="alta" />
            </fieldset>
                 <label>Tarefa concluida: </label>
                 <input type="checkbox" name="concluida" value="sim" />

                 <input type="submit" value="cadastrar" />
        </fieldset>
     </form>
     <table>
        <tr>
            <th>Tarefas</th>
            <th>Descrição</th>
            <th>Prazo</th>
            <th>Prioridade</th>
            <th>concluida</th>
        </tr>
        <?php foreach ($lista_tarefas as $tarefa) : ?>
            <tr>
                 <td><?php echo $tarefa['nome']; ?> </td>
                 <td><?php echo $tarefa['descricao']; ?> </td>
                 <td><?php echo $tarefa['prazo']; ?> </td>
                 <td><?php echo $tarefa['prioridade']; ?> </td>
                 <td><?php echo $tarefa['concluida']; ?> </td>
            </tr>
        <?php endforeach; ?>
     </table>
</body>
</html>