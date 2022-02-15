<?php

$this->load->view('templates/default/header');
$this->load->view('templates/default/navbar');
$this->load->view('templates/default/sidebar');
$this->load->view('templates/default/openContentWrapper');
echo $contents;
$this->load->view('templates/default/closeContentWrapper');
$this->load->view('templates/default/footer');
$this->load->view('templates/vendas/javascripts.php');
$this->load->view('templates/default/closeBody');
