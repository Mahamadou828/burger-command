<?php 
    require "database.php" ; 

    if (!empty($_GET['id']))
    {
        $id = checkInput($_GET['id']) ;
    }

    if (!empty($_POST['id']))
    {
        $db = Database::connect() ; 
        $id = checkInput($_POST['id']) ;
        $statement=$db->prepare("DELETE FROM items WHERE id=?");
        $statement->execute(array ($id)) ; 
        Database::disconnect() ; 
        header("Location: index.php") ;
    }

    function checkInput ($caractere)
    {
        $caractere = trim($caractere) ; 
        $caractere = stripslashes ($caractere) ; 
        $caractere = htmlspecialchars ($caractere) ; 
        return $caractere ; 
    }

?>

<!DOCTYPE html>

<html>
    
    <head>
        <title>Administration</title>
        <link rel="stylesheet" href="../css/style.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width , initial-scale = 1">
        <script src="js/script.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link rel="stylesheet" href="../glyphicon/glyphicon.css">
    </head>
    
    <body>
        <h1 class="text-logo"><span class = "glyphicon glyphicon-cutlery"></span>Burger Command<span class="glyphicon glyphicon-cutlery"></span></h1>
        <div class="container admin">
            <h1><strong>Supprimer un item</strong></h1><br>
            <div class="row">
                <form class="form" method="post" action="delete.php" role="form">
                    
                    <div class="form-action">
                        
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <p class="alert alert-warning">Etes vous sur de vouloir supprimer</p>
                        <div class="regroup">
                            <button type="submit" class="button orange-button">Supprimer</button>
                            <a class="button default" href="index.php">Retour</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
