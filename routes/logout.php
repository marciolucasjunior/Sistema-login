<?php
session_start();

//LIMPAR SESSAO
session_unset();
//DESTRUIR SESSAO
session_destroy();
header('location: ../index.php');

