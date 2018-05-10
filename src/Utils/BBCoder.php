<?php

namespace App\Utils;

/**
 *
 */
class BBCoder
{
  public function bbcodeToHtml($texte)
  {
      $texte = "<p>".$texte."</p>";
      $texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>', $texte); //gras
      $texte = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>', $texte); //italique
      $texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte); //souligné
      $texte = preg_replace('`\[url=\'(.+)\'\](.+)\[/url\]`isU', '<a href="$1" target="_blank">$2</a>', $texte); //url

      $texte = preg_replace('`\[t1\](.+)\[/t1\]`isU', '<h2>$1</h2>', $texte); //titre
      $texte = preg_replace('`\[t2\](.+)\[/t2\]`isU', '<h4>$1</h4>', $texte); //sous-titre

      $texte = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $texte); //supprimer lignes vides
      $texte = str_replace("\n", "</p><p>", $texte);

      $texte = preg_replace('`\[img\](.+)\[/img\]`isU', '<img src="/uploads/articles/images/$1" alt="Photo" class="d-block mx-auto" style="max-height: 300px; cursor:pointer;" onclick="openModalImage(this);">', $texte); //images

      $texte = preg_replace('`\[youtube\]https:\/\/www.youtube.com\/watch\?v\=(.+)\[/youtube\]`isU', '<iframe width="854" height="480" src="https://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', $texte);

      $texte = nl2br($texte);

      return $texte;
  }
}


 ?>
