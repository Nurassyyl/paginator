<?php

namespace App\HomeController;
use Aura\SqlQuery\QueryFactory;
use PDO;
use JasonGrimes\Paginator;

class HomeController {

  private $faker;
  private $queryFactory;

  public function __construct() {
    $this->pdo = new PDO('mysql:host=localhost;dbname=users', 'root', '');
    $this->faker = \Faker\Factory::create();
    $this->queryFactory = new QueryFactory('mysql');
  }

  public function getAll() {
    $select = $this->queryFactory->newSelect();
    $select
            ->cols(['*'])
            ->from('posts');
    $sth = $this->pdo->prepare($select->getStatement());
    $sth->execute($select->getBindValues());
    $totalItems = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $totalItems;
  }

  public function getPage() {
    $select = $this->queryFactory->newSelect();
    $select
            ->cols(['*'])
            ->from('posts')
            ->setPaging(3)
            ->page($_GET['page'] ?? 1);
    $sth = $this->pdo->prepare($select->getStatement());
    $sth->execute($select->getBindValues());
    $posts = $sth->fetchAll(PDO::FETCH_ASSOC);
    $templates = new \League\Plates\Engine('../app/path');
    $items = $this->getAll();
    $totalItems = count($items);
    $itemsPerPage = 3;
    $currentPage = $_GET['page'];
    $urlPattern = '?page=(:num)';
    $paginator = new Paginator($totalItems, $itemsPerPage, $currentPage, $urlPattern);
    echo $templates->render('profile', ['posts' => $posts, 'paginator' => $paginator]);
  }

  public function insert() {
    $insert = $this->queryFactory->newInsert();
    $insert->into('posts');
    for ($i=0; $i<30; $i++) {
            $insert->cols([                     
                'posts' => $this->faker->words(1, true)
            ]);
            $insert->addRow();
    }
    $sth = $this->pdo->prepare($insert->getStatement());
    $sth->execute($insert->getBindValues());
  }

}

?>