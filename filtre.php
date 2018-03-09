<?php
  session_start();
  if (ISSET($_GET['idcat']))
  {
    $id_cat = $_GET['idcat'];
  }
  else
  {
    $id_cat = 1;
  }

  try
  {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=blogsuperlab;charset=utf8', 'root', 'user', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    //return $bdd;
  }
  catch (Exception $e)
  {
    die('Erreur : ' . $e->getMessage());
  }

  // filtrage des articles par categorie donnee en GET
  $req = $bdd->prepare('SELECT cat.name_categorie,
                        cat.id_categorie,
                        art.id_article,
                        art.auteur,
                        art.titre,
                        art.contenu,
                        art.date_article
                        FROM articles AS art,
                        articles_has_categories AS mid,
                        categories AS cat
                        WHERE mid.id_categorie = cat.id_categorie
                        AND art.id_article = mid.id_article
                        AND cat.id_categorie = '.$id_cat.'');
   $req->execute();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/css/css/fontawesome-all.css">
    <link rel="stylesheet" type="text/css" href="assets/css/blog.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <title>Catégorie</title>
</head>

<body>
    <nav>
        <a href='blogadmin.php' class="backSuperlab connected">
            <i class="fas fa-arrow-left"></i>
            superBlog.be
        </a>
        <div class="header__btn-login">
            <i class="far fa-lg fa-user-circle"></i>
            <p class="login-name">Admin</p>
        </div>
    </nav>

    <?php
        // affichage
        while ($art_tries = $req->fetch())
        {
        ?>

      <article class="dashboard-article">
          <div class="dash-edit-del">
              <div class="dashboard-article-edit">
                  <a href="modifarticle.php?identifiant=<?= $art_tries['id_article']; ?>">
                      <i class="fas fa-edit "></i>
                  </a>

              </div>
              <div class="delete-article">
                <a href="delete.php?identifiant=<?= $art_tries['id_article']; ?>">
                  <i class="fas fa-times "></i>
                </a>
              </div>
          </div>
          <div class="dashboard-article-content">
              <h1>
                <?= $art_tries['titre']; ?>
              </h1>
              <div class="article-infos">
                  <p class="article-author">Posté le
                      <time>
                        <?= $art_tries['date_article']; ?>
                      </time> par
                      <span class="author-name">
                        <?= $art_tries['auteur']; ?>
                      </span>
                  </p>
              </div>
              <p>
                <?= $art_tries['contenu']; ?>
              </p>
          </div>
      </article>

      <?php
      }
      $req->closeCursor();
      ?>

</main>
<script src="assets/js/dashboard.js"></script>
</body>

</html>