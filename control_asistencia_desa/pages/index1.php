<!DOCTYPE html>
<html lang="es">

<head>
<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

     <link href="../css/estilo.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    
    <link rel="stylesheet" href="../bootstrap/bootstrap-select-1.12.4/dist/css/bootstrap-select.css">
    
   <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../bootstrap/bootstrap-select-1.12.4/dist/js/bootstrap-select.js"></script>	
<style type="text/css">
   .selected {
       background: lightBlue
}
</style>

<script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

<script  language="javascript">
$(document).ready(function() {
    var table = $('#example').DataTable();
 
    $('#example tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
    } );
 
    $('#button').click( function () {
        alert( table.rows('.selected').data().length +' row(s) selected' );
    } );
} );
</script>
</head>
<table id="example" class="display" style="width:100%">
       <thead>
           <tr>
               <th>ID</th>
               <th>Name</th>
               <th>Class</th>
           </tr>
       </thead>
       <tbody>
           <tr onmousedown="RowClick(this,false);">
               <td>1</td>
               <td>John</td>
               <td>4th</td>
           </tr>
            <tr onmousedown="RowClick(this,false);">
               <td>2</td>
               <td>Jack</td>
               <td>5th</td>
           </tr>
            <tr onmousedown="RowClick(this,false);">
               <td>3</td>
               <td>Michel</td>
               <td>6th</td>
           </tr>
           <tr onmousedown="RowClick(this,false);">
               <td>4</td>
               <td>Mike</td>
               <td>7th</td>
           </tr>
           <tr onmousedown="RowClick(this,false);">
               <td>5</td>
               <td>Yke</td>
               <td>8th</td>
           </tr>
            <tr onmousedown="RowClick(this,false);">
               <td>6</td>
               <td>4ke</td>
               <td>9th</td>
           </tr>
           <tr onmousedown="RowClick(this,false);">
               <td>7</td>
               <td>7ke</td>
               <td>10th</td>
           </tr>
       </tbody>
</table>