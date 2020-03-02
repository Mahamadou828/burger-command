<?php 
    require "database.php" ; 
    $nameError =  $descriptionError = $priceError = $categoryError = $imageError = " " ; 
    $name =  $description = $price = $category = $image = " " ;

    if (!empty($_POST))
    {
        $name =  checkInput($_POST['name']) ;
        $description = checkInput($_POST['description']) ;
        $price = checkInput($_POST['price']) ;
        $category = checkInput($_POST['category']) ;
        $image = $_FILES['image']['name'] ;
        $imagePath = '../images/'.basename($image) ;
        $imageExtension = pathinfo ($imagePath , PATHINFO_EXTENSION) ;
        $isSuccess = true ; 
        $isUploadSuccess = false ; 
        
        if (empty($name))
        {
            $nameError = "ce champ ne peut pas etre vide " ; 
            $isSuccess = false ; 
        }
        
        if (empty($description))
        {
            $descriptionError = "ce champ ne peut pas etre vide " ; 
            $isSuccess = false ; 
        }
        
        if (empty($price))
        {
            $priceError = "ce champ ne peut pas etre vide " ; 
            $isSuccess = false ; 
        }
        
        if (empty($category))
        {
            $categoryError = "ce champ ne peut pas etre vide " ; 
            $isSuccess = false ; 
        }
        
        if (empty($image))
        {
            $imageError = "ce champ ne peut pas etre vide " ; 
            $isSuccess = false ; 
        }
        else
        {
            $isUploadSuccess = true ; 
            
            if ($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" )
            {
                $imageError = "Le format de l'image n'est pas correcte" ; 
                $isUploadSuccess = false ;
            }
            if (file_exists($imagePath))
            {
                $imageError = "le fichier existe deja" ; 
                $isUploadSuccess = false ;
            }
            if ($_FILES["image"]["size"] > 500000)
            {
                $imageError = "l'image est trop lourde" ; 
                $isUploadSuccess = false ;
            }
            if ($isUploadSuccess)
            {
                if (!move_uploaded_file($_FILES["image"]["tmp_name"] , $imagePath))
                {
                    $imageError = "veuillez reessayer" ; 
                    $isUploadSuccess = false ;
                }
            }
        }
        
        if ($isSuccess && $isUploadSuccess)
        {
            $db = Database::connect() ; 
            $statement=$db->prepare("INSERT INTO items(name , description , price, category , image) VALUES(?,?,?,?,?)");
            $statement->execute(array ($name ,  $description , $price , $category , $image)) ; 
            Database::disconnect() ; 
            header("Location: index.php") ;
        }
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
            <h1><strong>Ajouter un item</strong></h1><br>
            <div class="row">
            
                <form class="form" method="post" action="insert.php" role="form" enctype="multipart/form-data">
                    
                    <div class="form-group">
                        
                        <label for="name">Nom:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Ecrivez un nom" value="<?php echo $name ; ?>">
                        <span class="error"><?php echo $nameError ; ?></span>
                        
                    </div>
                    <div class="form-group">
                        
                        <label for="description">Description:</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Ecrivez une description du produit" value="<?php echo $description ; ?>">
                        <span class="error"><?php echo $descriptionError ; ?></span>
                        
                    </div>
                    <div class="form-group">
                        
                        <label for="price">Prix:</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Ecrivez le prix du produit" value="<?php echo $price ; ?>">
                        <span class="error"><?php echo $priceError ; ?></span>
                        
                    </div>
                    <div class="form-group">
                        
                        <label for="category">Prix:</label>
                        <select class="form-control" id="category" name="category">
                            <?php 
                                $db = Database::connect() ;
                                foreach ($db->query("SELECT * FROM categories") as $row)
                                {
                                    echo "<option value='". $row['id'] . "'>". $row['name'] . "</option>";
                                }
                                Database::disconnect() ; 
                            ?>
                        </select>
                        <span class="error"><?php echo $categoryError ; ?></span>
                        
                    </div>
                    <div class="form-group">
                        
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image">
                        <span class="error"><?php echo $imageError ; ?></span>
                        
                    </div>
                    <button type="submit" class="button green-button"><span class="glyphicon glyphicon-pencil">Ajouter</span></button>
                    <div class="form-action">
                    <a class="button blue-button" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span>Retour</a>
                </div>
                </form>
            </div>
        </div>
    </body>
</html>
