<?php
if (!isset($_GET["ctext"])) {
    die("No input text has been specified");
}
include("../Class.colotar.php");
header("Content-Type: image/png");
$c = new Colotar;
$c->getColotar($_GET["ctext"]);
?>