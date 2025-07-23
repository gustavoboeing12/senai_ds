<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Array05 tabuada em PHP</title>
</head>
<body>
     <?php
        $tabuadas = array (1,2,3,4,5,6,7,8,9,10);
            foreach($tabuadas as $tabuada){
              for ($i = 0; $i <= 10; $i++){
                      echo "$i"
              }
              echo "<br/>";
            }

        
     ?>
</body>
</html>