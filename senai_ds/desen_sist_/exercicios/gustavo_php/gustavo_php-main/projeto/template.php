<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de tarefas</title>
</head>
<body>

    <h1>Gerenciador de tarefas</h1>

    <form method="get">
        <fieldset>
            <legend>Nova tarefa</legend>
            <label>
                Tarefa:
                <input type="text" name="nome" />
            </label><br><br>

            <label>
                Descrição (Opcional):
                <textarea name="descricao"></textarea>
            </label><br><br>

            <label>
                Prazo (Opcional):
                <input type="text" name="prazo" />
            </label><br><br>
        </fieldset>

        <fieldset>
            <legend>Prioridade:</legend>
            <label><input type="radio" name="prioridade" value="1" checked> Baixa</label>
            <label><input type="radio" name="prioridade" value="2"> Média</label>
            <label><input type="radio" name="prioridade" value="3"> Alta</label>
        </fieldset><br>

        <label>
            Tarefa concluída:
            <input type="checkbox" name="concluida" value="sim" />
        </label><br><br>

        <input type="submit" value="Cadastrar" />
    </form>

    <h2>Lista de labubus</h2>
    <table>
        <tr>
            <th>Tarefa</th>
            <th>Descrição</th>
            <th>Prazo</th>
            <th>Prioridade</th>
            <th>Concluída</th>
        </tr>
        <?php foreach ($lista_tarefas as $tarefa): ?>
            <tr>
                <td><?php echo ($tarefa['nome']); ?></td>
                <td><?php echo ($tarefa['descricao']); ?></td>
                <td><?php echo ($tarefa['prazo']); ?></td>
                <td><?php echo ($tarefa['prioridade']); ?></td>
                <td><?php echo ($tarefa['concluida']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <center>
        <ADDRESS>
                Gustavo Tobler - Estudante - Técnico em desenvolvimento de sistemas
        </ADDRESS>
        </center>

</body>
</html>
