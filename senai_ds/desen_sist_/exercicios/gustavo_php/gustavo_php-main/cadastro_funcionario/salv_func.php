<?php
   // Função para redimensionar a imagem
   function redimensionarImagem($imagem, $largura, $altura){
     // obtém as dimensões originais da imagem
     // getimagesize() Retorna a altura e largura de uma imagem
     list($larguraOriginal,$alturaOriginal) = getimagesize($imagem);

     // Cria uma nova imagem em branco com as novas dimensões
     // imagecreatetruecolor() Cria uma nova imagem em branco em alta qualidade
     $novaImagem = imagecreatetruecolor($largura, $altura);

     // Carrega a imagem original(JPEG) a partir do arquivo
     // imagecreatefromjpeg() cria uma imagem php a partir de um jpeg
     $imagemOriginal = imagecreatefromjpeg($imagem);

     // Copia e redimensiona a imagem original para a nova
     // imagecopyresampled() copia com redimensionamento e suavização
     imagecopyresampled($novaImagem, $imagemOriginal, 0,0,0,0, 
     $largura, $larguraOriginal, $altura, $alturaOriginal);

     // Inicia um buffer para guardar a imagem com o texto binario
     // ob_start() inicia o "output_buffering" guardando a saida
     ob_start();

     // imagejpeg() Envia a imagem para o output
     imagejpeg($novaImagem);

     // ob_get_clean pega o conteudo do buffer e limpa
     $dadosImagem = ob_get_clean();

     // Libera a memória usada pelas imagens
     // imagedestroy() limpa a memória da imagem criada
     imagedestroy($novaImagem);
     imagedestroy($imagemOriginal);

     // Retorna a imagem redimensionada em formato binário
     return $dadosImagem;
}

  // Configuração do banco de dados
  $host = 'localhost';
  $dbname = 'bd_imagens';
  $username = 'root';
  $password = '';

  try {
    // Conexão com o banco via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Define que erros vão lançar exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se foi um post e se tem arquivo 'foto'
    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto"])){
        if($_FILES['foto']['error'] == 0){
            // Pega as respectivas informações do funcionário
            $nome = $_POST['nome']; 
            $telefone = $_POST['telefone'];
            $nomeFoto = $_FILES['foto']['name'];
            $tipoFoto = $_FILES['foto']['type'];

            // Redimensiona a imagem
            // tmp_name é o caminho temporário
            $foto = redimensionarImagem($_FILES['foto']['tmp_name'], 300, 400);

            // Insere no banco de dados usando sql preparado
            $sql = "INSERT INTO funcionarios (nome,telefone,nome_foto,tipo_foto,foto)
                    VALUES (:nome,:telefone,:nome_foto,:tipo_foto,:foto)";

            // Prepara a query para evitar ataque sql injection
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':nome',$nome);
            $stmt -> bindParam(':telefone',$telefone);
            $stmt -> bindParam(':nome_foto',$nome_foto);
            $stmt -> bindParam(':tipo_foto',$tipo_foto);
            $stmt -> bindParam(':foto',$foto,PDO::PARAM_LOB);

            if($stmt -> execute()){
                echo "Funcionário cadastrado!";
            } else{
                echo "Erro ao cadastrar funcionário";
            }
        } else{
            echo "Ao fazer o upload da foto! Código: ".$_FILES['foto']['error'];
        }
    }

  } catch(PDOException $e){
      // Mostra o erro se houver
      echo "Erro" .$e -> getMessage();
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Imagens</title>
</head>
<body>
     <h1>Lista de imagens</h1>

     <a href="cons_func.php">Listar funcionários</a>
     
     <address>
        <center>
            Gustavo Fratoni Boeing
        </center>
     </address>
</body>
</html>