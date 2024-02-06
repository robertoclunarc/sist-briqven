<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <?php
        // Parse pdf file and build necessary objects.
        require('PdfParser.php');
        set_time_limit(300);
        $parser = new PdfParser();
        //$pdf = $parser->parseFile('NOMINASENERO.pdf');
        $pdf = $parser->parseFile('arc2022_1-863.pdf');
        echo "NOMINASENERO<br>";        
        $nifs=array();
        while (($lastpos=strpos($pdf,"NIF:",0))!=0){            
            array_push($nifs,  substr($pdf, $lastpos+5, 10));
            $pdf=  substr($pdf, $lastpos+15);
        }
        //print_r ($nifs);
         echo $pdf;               
        include("pdf2text.php");
        $result = pdf2text("arc2022_1-863.pdf");
        echo $result;
        //$myfile = fopen("sample.txt", "w") or die("Unable to open file!");  
        //fwrite($myfile, $result);
        //fclose($myfile); 
    ?>
        
    </body>
</html>

  