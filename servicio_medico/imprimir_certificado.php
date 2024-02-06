<?php
require("planilla_certificado.php");
echo planilla_certificado($_GET['uid']);
?>
<script  language="javascript">
function printHTML() { 
  if (window.print) { 
    window.print();
  }
}
document.addEventListener("DOMContentLoaded", function(event) {
  printHTML(); 
});
</script>