<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TruncateExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('truncate', [$this, 'getTruncate']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('truncate', [$this, 'getSidebar'],['is_safe' => ['html']]),
        ];
    }

    public function getTruncate($value)
    {

        $value = substr($value,0,10).' ...';
        return $value;
    }
}


// public function getFilters(): array
// {
//     return [
//         // If your filter generates SAFE HTML, you should add a third
//         // parameter: ['is_safe' => ['html']]
//         // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
//         new TwigFilter('filter_name', [$this, 'getSidebar']),
//     ];
// }

// public function getFunctions(): array
// {
//     return [
//         new TwigFunction('sidebar', [$this, 'getSidebar'],['is_safe' => ['html']]),
//     ];
// }

// public function getSidebar()
// {
//     $companies = $this->companyRepository->findAllCompany();
//     return $this->twig->render('partials/sidebar.html.twig',[
//         "companies" => $companies
//     ]);
// }