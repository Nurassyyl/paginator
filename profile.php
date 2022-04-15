<?php $this->layout('template', ['title' => 'User Profile']) ?>

<h1>User Profile</h1>

<?php 
foreach ($posts as $post) {
    echo $post['id'] . PHP_EOL . $post['posts'] . "<br>";
}
?>


 <?php
 echo $paginator;
 ?>

 