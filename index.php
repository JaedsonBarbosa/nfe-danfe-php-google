<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use NFePHP\DA\NFe\Danfe;

function gerarDANFE(ServerRequestInterface $request): ResponseInterface
{
    $origBody = $request->getBody();
    $body = json_decode($origBody, true);
    $xml = $body['xml'];
    $orientacao = $body['orientacao'];
    $papel = 'A4';
    $margSup = $body['margSup'];
    $margEsq = $body['margEsq'];

    $danfe = new Danfe($xml);
    $danfe->debugMode(false);
    $danfe->exibirValorTributos = false;
    $danfe->printParameters($orientacao, $papel, $margSup, $margEsq);
    $danfe->creditsIntegratorFooter('NFe Fácil - https://nfefacil.net');
    $pdf = $danfe->render();
    return (new Response())
        ->withBody(Utils::streamFor($pdf))
        ->withStatus(200)
        ->withHeader('Content-type', 'application/pdf')
        ->withHeader('Access-Control-Allow-Origin', '*');
}
