<?php

namespace App\Utils;


class TimeAgo
{
  function getTimeAgo($oldTime) {
    $timeDiff = time()-strtotime($oldTime);
    if ($timeDiff < 60) {
      return "moins d'une minute";
    } elseif (($timeDiff >= 60)&&($timeDiff <= 3600)) {
      $res = (round($timeDiff/60));
      if ($res <= 1) {
        return "".$res." minute";
      } else {
        return "".$res." minutes";
      }
    } elseif ((($timeDiff > 3600)&&($timeDiff < 86400))) {
      $res = (round($timeDiff/3600));
      if ($res <= 1) {
        return "".$res." heure";
      } else {
        return "".$res." heures";
      }
    } elseif (($timeDiff >= 86400)&&($timeDiff < 2592000)) {
      $res = (round($timeDiff/86400));
      if ($res <= 1) {
        return "".$res." jour";
      } else {
        return "".$res." jours";
      }
    } elseif (($timeDiff >= 2592000)&&($timeDiff < 31104000)) {
      $res = (round($timeDiff/2592000));
      return "".$res." mois";
    } else {
      $res = (round($timeDiff/31104000));
      if ($res <= 1) {
        return "".$res." an";
      } else {
        return "".$res." ans";
      }
    }

  }
}


?>
