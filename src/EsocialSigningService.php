<?php
declare(strict_types=1);

/**
 * [EN]    Signs an eSocial event XML (any S-xxxx event) with the employer's e-CPF/e-CNPJ
 *         certificate, using a SolidSign KMS-custodied certificate
 *         (POST /solidsign/dsig/xml/sign-kms). Always signs the ENTIRE document with the same
 *         fixed parameters, regardless of event type.
 * [PT-BR] Assina o XML de um evento eSocial (qualquer evento S-xxxx) com o certificado
 *         e-CPF/e-CNPJ do empregador, via KMS SolidSign. Sempre assina o DOCUMENTO INTEIRO.
 */

namespace SolidSign;

use GuzzleHttp\Client;

class EsocialSigningService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    public function sign(string $content, string $filename, string $kmsCode): ?string
    {
        $baseUrl = rtrim($_ENV['SOLIDSIGN_API_BASE_URL'] ?? '', '/');
        $auth = $_ENV['SOLIDSIGN_API_AUTHORIZATION'] ?? '';

        $multipart = [
            ['name' => 'document[0]', 'contents' => $content, 'filename' => $filename],
            ['name' => 'kmsCode', 'contents' => $kmsCode],
            ['name' => 'profile', 'contents' => $_ENV['SOLIDSIGN_ESOCIAL_PROFILE'] ?? 'XML_INTERNATIONAL'],
            ['name' => 'hashAlgorithm', 'contents' => $_ENV['SOLIDSIGN_ESOCIAL_HASH_ALGORITHM'] ?? 'SHA256'],
            ['name' => 'signaturePackaging', 'contents' => $_ENV['SOLIDSIGN_ESOCIAL_SIGNATURE_PACKAGING'] ?? 'ENVELOPED'],
            ['name' => 'canonicalizationMethod', 'contents' => $_ENV['SOLIDSIGN_ESOCIAL_CANONICALIZATION_METHOD'] ?? 'INCLUSIVE'],
            ['name' => 'isRemoveXPathExclusionFilter', 'contents' => $_ENV['SOLIDSIGN_ESOCIAL_REMOVE_XPATH_FILTER'] ?? 'true'],
            ['name' => 'isRemoveNamespacePrefixFromNodeNames', 'contents' => $_ENV['SOLIDSIGN_ESOCIAL_REMOVE_NAMESPACE_PREFIX'] ?? 'true'],
        ];

        $response = $this->client->post("$baseUrl/solidsign/dsig/xml/sign-kms", [
            'headers' => ['Authorization' => $auth],
            'multipart' => $multipart,
        ]);

        if ($response->getStatusCode() >= 400) {
            error_log("SolidSign error {$response->getStatusCode()}: " . $response->getBody());
            return null;
        }

        $signResp = json_decode((string) $response->getBody(), true);
        $doc = ($signResp['documents'] ?? [])[0] ?? null;
        if ($doc === null) {
            error_log('SolidSign response had no documents.');
            return null;
        }

        $href = $doc['_links']['self']['href'] ?? null;
        if ($href === null) {
            foreach ($doc['links'] ?? [] as $link) {
                if (($link['rel'] ?? null) === 'self') { $href = $link['href']; break; }
            }
        }
        if ($href === null) {
            error_log('SolidSign response had no download link.');
            return null;
        }

        $dlResp = $this->client->get($href, ['headers' => ['Authorization' => $auth]]);
        if ($dlResp->getStatusCode() >= 400) return null;

        return (string) $dlResp->getBody();
    }
}
