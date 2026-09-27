<?php
declare(strict_types=1);

/**
 * [EN]    eSocial use case — single sign-evento endpoint, KMS custody.
 *         Setup: composer install && cp .env.example .env  (edit .env)
 *         Run:   php -S 0.0.0.0:8104 -t public
 * [PT-BR] Caso de uso eSocial — endpoint único de assinatura, custódia KMS.
 *         Configurar: composer install && cp .env.example .env  (editar .env)
 *         Executar:   php -S 0.0.0.0:8104 -t public
 */

require __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use SolidSign\EsocialSigningService;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$app = AppFactory::create();
$service = new EsocialSigningService();

$app->post('/api/esocial/sign-evento', function (Request $request, Response $response) use ($service) {
    $params = (array) $request->getParsedBody();
    $uploadedFiles = $request->getUploadedFiles();
    $document = $uploadedFiles['document'] ?? null;
    $kmsCode = $params['kmsCode'] ?? null;

    if ($document === null || $document->getError() !== UPLOAD_ERR_OK || $kmsCode === null) {
        $response->getBody()->write(json_encode(['error' => 'document and kmsCode are required']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $signed = $service->sign((string) $document->getStream(), $document->getClientFilename() ?? 'evento.xml', $kmsCode);
    if ($signed === null) {
        $response->getBody()->write(json_encode(['error' => 'Signing failed. Check logs.']));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    $response->getBody()->write($signed);
    return $response
        ->withHeader('Content-Type', 'application/xml')
        ->withHeader('Content-Disposition', 'attachment; filename="evento_esocial_signed.xml"');
});

$app->run();
