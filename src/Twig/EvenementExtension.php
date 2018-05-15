<?php

namespace App\Twig;

use App\Entity\Evenement;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EvenementExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('nb_participants_total', [$this, 'nombreTotal'], ['is_safe' => ['html']]),
            new TwigFilter('nb_participants_adultes', [$this, 'nombreAdultes'], ['is_safe' => ['html']]),
            new TwigFilter('nb_participants_enfants', [$this, 'nombreEnfants'], ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('nb_participants_total', [$this, 'nombreTotal']),
            new TwigFunction('nb_participants_adultes', [$this, 'nombreAdultes']),
            new TwigFunction('nb_participants_enfants', [$this, 'nombreEnfants']),
        ];
    }

    public function nombreTotal(Evenement $evenement)
    {
        $res = 0;
        foreach ($evenement->getInscriptionEvenements() as $inscriptionEvenement) {
            $res += $inscriptionEvenement->getNbAdultes()+$inscriptionEvenement->getNbEnfants();
        }
        return $res;
    }

    public function nombreAdultes(Evenement $evenement)
    {
        $res = 0;
        foreach ($evenement->getInscriptionEvenements() as $inscriptionEvenement) {
            $res += $inscriptionEvenement->getNbAdultes();
        }
        return $res;
    }

    public function nombreEnfants(Evenement $evenement)
    {
        $res = 0;
        foreach ($evenement->getInscriptionEvenements() as $inscriptionEvenement) {
            $res += $inscriptionEvenement->getNbEnfants();
        }
        return $res;
    }
}
