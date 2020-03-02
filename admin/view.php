<?php 
    require "database.php" ; 

    if (!empty($_GET['id']))
    {
        $itemId = checkInput($_GET['id']) ;   
    }

    $db = Database::connect() ;
    $statement = $db->prepare ("SELECT items.id , items.name , items.description , items.price , items.image , categories.name AS category FROM items LEFT JOIN categories ON items.category=categories.id WHERE items.id = ? ;") ;

    $statement->execute(array($itemId)) ; 
    $itemContent = $statement->fetch() ; 
    Database::disconnect() ;

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
            <h1><strong>Voir un item</strong></h1><br>
            <div class="row">
                
                <div class="col-sm-6 view">
                    <form>
                        <div class="form-group">
                            <label>Nom:</label><?php echo " " . $itemContent['name'] ; ?>
                        </div>
                        <div class="form-group">
                            <label>Description:</label><?php echo " " . $itemContent['description'] ; ?>
                        </div>
                        <div class="form-group">
                            <label>Prix:</label><?php echo " " . number_format((float)$itemContent['price'] , 2 , '.','') ; ?>
                        </div>
                        <div class="form-group">
                            <label>Categorie:</label><?php echo " " . $itemContent['category'] ; ?>
                        </div>
                        <div class="form-group">
                            <label>Image:</label><?php echo " " . $itemContent['image'] ; ?>
                        </div>
                    </form>
                    <div class="form-action">
                        <a class="button blue-button" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span>Retour</a>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="img-thumbnail">
                        <img src="<?php echo "../images/". $itemContent['image'] ?>" alt="...">
                        <div class="price"><?php echo number_format((float)$itemContent['price'],2,'.','') ; ?></div>
                        <div class="caption">
                            <h4><?php echo $itemContent['name'] ; ?></h4>
                            <p><?php echo $itemContent['description'] ; ?></p>
                            <a href="#" class="command-button" role="button"><span class="glyphicon glyphicon-shopping-cart"></span>Commander</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </body>
    
</html>
