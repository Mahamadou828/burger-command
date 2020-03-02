<?php 
    require "database.php" ; 
    $nameError =  $descriptionError = $priceError = $categoryError = $imageError = " " ; 
    $name =  $description = $price = $category = $image = " " ;

    if (!empty ($_GET['id']))
    {
        $id = checkInput($_GET['id']) ;
    }

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
        $isUploadSuccess = true ;
        
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
            $isImageUploaded = false ;  
        }
        else
        {
            $isImageUploaded = true ; 
            
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
        
        if (($isSuccess && $isUploadSuccess && $isImageUploaded) || ($isSuccess && !$isImageUploaded))
        {
            $db = Database::connect() ;
            
            if ($isImageUploaded)
            {
                $statement=$db->prepare("UPDATE items set name = ? ,description= ?,price= ?,category= ?,image= ? ) WHERE id=?");
                $statement->execute(array ($name ,  $description , $price , $category , $image , $id)) ; 
            }
            else
            {
                $statement=$db->prepare("UPDATE items set name = ? ,description= ?,price= ?,category= ?) WHERE id=?");
                $statement->execute(array ($name ,  $description , $price , $category , $id)) ; 
            }
            Database::disconnect() ; 
            header("Location: index.php") ;
        }
        else if ($isImageUploaded && !$isUploadSuccess)
        {
            $db = Database::connect() ; 
            $statement=$db->prepare("SELECT image FROM items WHERE id=?");
            $statement->execute(array ($id)) ;
            $item = $statement->fetch() ; 
            $image = $item ['image'] ;;
            Database::disconnect() ; 
        }
    }
    else
    {
        $db = Database::connect() ; 
        $statement=$db->prepare("SELECT * FROM items WHERE id=?");
        $statement->execute(array ($id)) ;
        $item = $statement->fetch() ; 
        $name =  $item ['name'] ;
        $description = $item ['description'] ;
        $price = $item ['price'] ;
        $category = $item ['category'] ;
        $image = $item ['image'] ;;
        Database::disconnect() ; 
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
            <h1><strong>Modifier un item</strong></h1><br>
            <div class="row">
                <div class="col-sm-6">
                    <form class="form" method="post" action="<?php echo "update.php?id=" . $id ?>" role="form" enctype="multipart/form-data">

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

                            <label for="category">Categorie:</label>
                            <select class="form-control" id="category" name="category">
                                <?php 
                                    $db = Database::connect() ;
                                    foreach ($db->query("SELECT * FROM categories") as $row)
                                    {
                                        if ($row['id'] === $category)
                                            {
                                            echo "<option selected='selected' value='". $row['id'] . "'>". $row['name'] . "</option>" ;
                                            }
                                        else
                                            echo "<option value='". $row['id'] . "'>". $row['name'] . "</option>";
                                    }
                                    Database::disconnect() ; 
                                ?>
                            </select>
                            <span class="error"><?php echo $categoryError ; ?></span>

                        </div>
                        <div class="form-group">
                            
                            <label>Actuel:</label><p><?php echo $image ; ?></p>
                            <label for="image">Image:</label>
                            <input type="file" id="image" name="image">
                            <span class="error"><?php echo $imageError ; ?></span>

                        </div>
                        <div class="regoup">
                            <button type="submit" class="button green-button"><span class="glyphicon glyphicon-pencil">Modifier</span></button>
                            <a class="button blue-button" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span>Retour</a>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6 col-md-4">
                            <div class="img-thumbnail">
                                <img src="<?php echo "../images/". $image ?>" alt="...">
                                <div class="price"><?php echo number_format((float)$price,2,'.','') ; ?></div>
                                <div class="caption">
                                    <h4><?php echo $name ; ?></h4>
                                    <p><?php echo $description ; ?></p>
                                    <a href="#" class="btn" role="button"><span class="glyphicon glyphicon-shopping-cart"></span>Commander</a>
                                </div>
                            </div>
                        </div>
            </div>
        </div>
    </body>
</html>
