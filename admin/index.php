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
            <div class="row">
                <h1><strong>Liste des items</strong><a href="insert.php" btn btn-lg><span class="glyphicon glyphicon-plus">Ajouter</span></a></h1>
            </div>
            
            <table class="table table-stripered table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Categorie</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    
                    <?php 
                    require 'database.php' ; 
                    $db = Database::connect() ; //me connecte a la base de donnee
                    $statement = $db->query("SELECT items.id , items.name , items.description , items.price , categories.name AS category FROM items LEFT JOIN categories ON items.category=categories.id ORDER BY items.id DESC ;") ; //je recupere la requete
                    
                    while ($item = $statement->fetch())
                    {
                        //j'affiche les informations dans leurs div
                        echo "<tr>" ;
                        echo "<td>" . $item['name'] . "</td>" ;
                        echo "<td>" . $item['description'] . "</td>" ; 
                        echo "<td>" . number_format((float)$item['price'],2,'.','') . "</td>" ;
                        echo "<td>" . $item['category'] . "</td>" ;
                        echo "<td width=300>" ;
                        echo '<a class = "button default" role="button" href="view.php?id='. $item['id'] . '"><span class="glyphicon glyphicon-eye-open"></span>Voir</a>' ; 
                        echo '<a class = "button blue-button" role="button" href="update.php?id='. $item['id'] . '"><span class="glyphicon glyphicon-pencil"></span>Modifier</a>' ; 
                        echo '<a class = "button red-button" role="button" href="delete.php?id='. $item['id'] . '"><span class="glyphicon glyphicon-remove"></span>Supprimer</a>' ;
                        echo "</td>" ; 
                        echo "</tr>" ; 
                    }
                    
                    Database::disconnect() ;
                    
                    ?>
                    
                </tbody>
            </table>
            
        </div>
    </body>
    
</html>