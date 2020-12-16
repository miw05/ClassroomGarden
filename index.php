<?php

// Organization
$organization = json_decode(file_get_contents("https://raw.githubusercontent.com/miw05/directory/master/organization.json"));


// People
$directory = json_decode(file_get_contents("https://raw.githubusercontent.com/miw05/directory/master/peoples.json"));

$studentsPerPromo = array();
$teachers         = array();

foreach ($directory as $people) {

  if (!empty($people->promo)) {
    $studentsPerPromo[$people->promo][] = $people;
  }

  if (!empty($people->teaching)) {
    $teachers[] = $people;
  }

}

// Order by
krsort($studentsPerPromo);  // By deskCoordinate
shuffle($teachers); // By random



?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MIW - Classroom</title>
    <meta name="robots" content="noimageindex, nofollow, nosnippet">
    <meta name="description" content="Feuille d'émargement">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link type="text/css" href="<?= ($_GET['css']) ? filter_var($_GET['css'], FILTER_SANITIZE_URL) : 'style.css' ?>" rel="stylesheet">

    <!--
        Exemple d'appel avec une feuille de CSS externe : 
        https://miw.websenso.dev/ClassroomGarden/?css=https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css
    -->

</head>



<!--



	View source is a feature, not a bug. Thanks for your curiosity and
	interest in participating!

	Here are the submission guidelines for the new and improved csszengarden.com:

	- CSS3? Of course! Prefix for ALL browsers where necessary.
	- go responsive; test your layout at multiple screen sizes.
	- your browser testing baseline: IE9+, recent Chrome/Firefox/Safari, and iOS/Android
	- Graceful degradation is acceptable, and in fact highly encouraged.
	- use classes for styling. Don't use ids.
	- web fonts are cool, just make sure you have a license to share the files. Hosted 
	  services that are applied via the CSS file (ie. Google Fonts) will work fine, but
	  most that require custom HTML won't. TypeKit is supported, see the readme on this
	  page for usage instructions: https://github.com/mezzoblue/csszengarden.com/

	And a few tips on building your CSS file:

	- use :first-child, :last-child and :nth-child to get at non-classed elements
	- use ::before and ::after to create pseudo-elements for extra styling
	- use multiple background images to apply as many as you need to any element
	- use the Kellum Method for image replacement, if still needed. http://goo.gl/GXxdI
	- don't rely on the extra divs at the bottom. Use ::before and ::after instead.

		
-->


<body>
<div class="page-wrapper">

    <section class="intro">
        <header role="banner">
            <h1><?= $organization->legalName ?></h1>
        </header>
    </section>

    <div class="tools">
        <ul>
            <li><a class="button" href="javascript:window.print();" title="Imprimer sur 1 feuille A4">Imprimer pour émargement</a></li>
        </ul>
    </div>

    <div class="students" role="main">

      <?php foreach ($studentsPerPromo as $promo => $students) { ?>
          <section class="classroom promo-<?= $promo ?>">

              <h2>Promotion <?= $promo ?></h2>
              <ul>
                <?php

                // Sort Student by deskCoordinate
                usort($students, function ($a, $b) {
                  return strcmp($a->deskCoordinate, $b->deskCoordinate);
                });

                foreach ($students as $student) { ?>

                    <li data-deskCoordinate="<?= $student->deskCoordinate ?>">

                        <img src="<?= ($student->github) ? 'https://images.weserv.nl/?w=100&h=100&&output=webp&fit=cover&url=' . rtrim($student->github, '/') . '.png' : 'https://via.placeholder.com/100x100' ?>" width="100" height="100" loading="lazy" alt="<?= $student->name ?>">
                        <h4 class="name"><?= $student->name ?></h4>
                        <ul class="networks_links">
                          <?php if($student->linkedin) { ?><li><a class="linkedin" href="<?= $student->linkedin ?>" rel="noopener noreferrer" target="_blank">LinkedIn</a></li><?php } ?>

                          <?php if($student->github) { ?><li><a class="github" href="<?= $student->github ?>" rel="noopener noreferrer" target="_blank">GitHub</a></li><?php } ?>

                        </ul>

                    </li>

                <?php } ?>
              </ul>



          </section>
      <?php } ?>


    </div>

    <aside class="sidebar" role="complementary">
        <div class="wrapper">


            <section class="teachers">

                <h2>Enseignant·e·s</h2>

                <ul>

                  <?php foreach ($teachers as $teacher) { ?>

                      <li>

                          <img src="<?= ($teacher->github) ? 'https://images.weserv.nl/?w=100&h=100&&output=webp&fit=cover&url=' . rtrim($teacher->github, '/') . '.png' : 'https://via.placeholder.com/100x100' ?>"  width="100" height="100" loading="lazy" alt="<?= $teacher->name ?>">
                          <h4 class="name"><?= $teacher->name ?></h4>

                          <ul class="networks_links">
                            <?php if($teacher->linkedin) { ?><li><a class="linkedin" href="<?= $teacher->linkedin ?>" rel="noopener noreferrer" target="_blank">LinkedIn</a></li><?php } ?>
                            <?php if($teacher->github) { ?><li><a class="github" href="<?= $teacher->github ?>" rel="noopener noreferrer" target="_blank">GitHub</a></li><?php } ?>
                          </ul>

                          <ul class="teaching">
                            <?php
                            $teachings = explode(";", $teacher->teaching);
                            foreach($teachings as $teaching) {   ?>
                                <li class="<?= trim(strtolower($teaching)) ?>"><?= $teaching ?></li>
                            <?php } ?>
                          </ul>

                      </li>

                  <?php } ?>
                </ul>


            </section>
        </div>
    </aside>

    <footer class="footer">
        <div class="outro">
            <p>Cette feuille est générée depuis l'<a href="https://github.com/miw05/directory" rel="noopener noreferrer" target="_blank">annuaire de la MIW</a> et basé sur le <a href="https://github.com/miw05/ClassroomGarden" rel="noopener noreferrer" target="_blank">layout ClassroomGarden</a>. Et ce projet est inspiré de <a href="http://www.csszengarden.com/" rel="noopener noreferrer" target="_blank">www.CssZenGarden.com</a>.</p>

            <p><?= $organization->description ?></p>
            <p>Elle a été fondée en <?= date('Y', strtotime($organization->foundingDate)) ?>.</p>
        </div>

        <div class="miw_contact" >
            <h4>Coordonnées</h4>
            <ul>
                <li><span class="label">Adresse :</span> <?= $organization->address ?></li>
                <li><span class="label">Téléphone :</span> <a href="tel:<?= $organization->telephone ?>"><?= $organization->telephone ?></a></li>
                <li><span class="label">Email :</span> <a href="mailto:<?= $organization->email ?>"><?= $organization->email ?></a></li>
                <li><span class="label">Site Web :</span> <a href="<?= $organization->url ?>" target="_blank" rel="noopener noreferrer"><?= $organization->url ?></a></li>
                <li><span class="label">Facebook :</span> <a href="<?= $organization->facebook ?>" target="_blank" rel="noopener noreferrer"><?= $organization->facebook ?></a></li>
                <li><span class="label">GitHub :</span> <a href="<?= $organization->github ?>" target="_blank" rel="noopener noreferrer"><?= $organization->github ?></a></li>
            </ul>
        </div>

    </footer>

</div>



<!--

	These superfluous divs/spans were originally provided as catch-alls to add extra imagery.
	These days we have full ::before and ::after support, favour using those instead.
	These only remain for historical design compatibility. They might go away one day.
		
-->
<div class="extra1" role="presentation"></div><div class="extra2" role="presentation"></div><div class="extra3" role="presentation"></div>
<div class="extra4" role="presentation"></div><div class="extra5" role="presentation"></div><div class="extra6" role="presentation"></div>


</body>
</html>