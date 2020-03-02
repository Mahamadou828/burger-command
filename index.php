<!DOCTYPE html>
<html>
    <head>
        <title>Burger Command</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width , initial-scale = 1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="glyphicon/glyphicon.css">
        <link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Holtwood+One+SC&display=swap" rel="stylesheet">
    </head>
    
    
    <body>
        
        <?php 
            require 'admin/database.php';
            echo "<div class='container site'>
            
                    <h1 class='text-logo'><span class='glyphicon glyphicon-cutlery'></span> Burger Code <span class='glyphicon glyphicon-cutlery'></span></h1>
                    <nav>
                    <ul class = 'nav nav-pills mb-3' id='pills-tab' role='tablist'>" ; 
        
            $db = Database::connect() ; 
            $statement = $db->query("SELECT * FROM categories") ; 
        
            while ($item = $statement->fetch())
            {
                echo "<li class='nav-item'>" ; 
                if ($item['id'] == '1')
                    {
                        echo "<a class='nav-link active' id='pills-" . $item['id'] . "-tab' data-toggle='pill' href='#pills-" . $item['id'] . "' role='tab' aria-controls='pills-" . $item['id'] . "1'>" . $item['name'] . "
                        </a>" ;
                    } 
                else
                    {
                        echo "<a class='nav-link' id='pills-" . $item['id'] . "-tab' data-toggle='pill' href='#pills-" . $item['id'] . "' role='tab' aria-controls='pills-" . $item['id'] . "1'>" . $item['name'] . "
                        </a>" ;
                    }
                echo "</li>" ; 
            }
            echo "</ul>
            </nav>
            <div class='tab-content' id='pills-tabContent'>" ;
        
            $statement = $db->query("SELECT * FROM categories") ; 
        
            while ($item = $statement->fetch())
            {
                if ($item['id'] == '1')
                {
                    echo "<div class='tab-pane fade show active' id='pills-" . $item['id'] . "' role='tabpanel' aria-labelledby='pills-" . $item['id'] . "-tab'>" ;
                }
                else
                {
                    echo "<div class='tab-pane fade' id='pills-" . $item['id'] . "' role='tabpanel' aria-labelledby='pills-" . $item['id'] . "-tab'>" ;
                }
                echo "<div class='row'>" ;
                         
                $request = $db->prepare("SELECT * FROM items WHERE items.category=?") ; 
                $request->execute(array($item['id'])) ;
                $result = $request->fetchAll() ;
                
                foreach ($result as $element)
                {
                    echo "<div class='col-sm-6 col-md-4'>
                            <div class='img-thumbnail text-center'>" ;
                    echo "<img src='images/" . $element['image'] . "' class='rounded' alt='...'>" ;
                    echo "<div class='price'>" . number_format((float)$element['price'],2,'.','') . "</div>" ;
                    echo "<div class='caption'>
                            <h4>" . $element['name'] . "</h4>
                            <p>" . $element['description'] ."</p>
                            <a class='command-button' href='#' role='button'><span class='glyphicon glyphicon-shopping-cart'></span>Commander</a>
                           </div>" ; 
                    echo "</div>" ; 
                    echo "</div>" ; 
                }
                echo "</div>" ; 
                echo "</div>" ;
            }
            echo "</div>" ; 
            echo "</div>" ; 
            
        ?>
        
    </body>
</html>

