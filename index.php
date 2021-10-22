<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use NFePHP\DA\NFe\Danfe;

function gerarDANFE(ServerRequestInterface $request): ResponseInterface
{
    $origBody = $request->getBody();
    $body = json_decode($origBody);
    $xml = $body->xml;
    $orientacao = $body->orientacao;
    $papel = 'A4';
    $margSup = $body->margSup;
    $margEsq = $body->margEsq;
    $logotipo = $body->logotipo;

    $danfe = new Danfe($xml);
    $danfe->debugMode(false);
    $danfe->printParameters($orientacao, $papel, $margSup, $margEsq);
    $danfe->creditsIntegratorFooter('NFe Fácil - https://nfefacil.net');

    if (isset($logotipo)) {
        $imagem = $logotipo->imagem;
        $alinhamento = $logotipo->alinhamento;
        $monocromatico = $logotipo->monocromatico;
        $danfe->logoParameters($imagem, $alinhamento, $monocromatico);
    }

    $pdf = $danfe->render();
    return (new Response())
        ->withBody(Utils::streamFor($pdf))
        ->withStatus(200)
        ->withHeader('Content-type', 'application/pdf')
        ->withHeader('Access-Control-Allow-Origin', '*');
}
