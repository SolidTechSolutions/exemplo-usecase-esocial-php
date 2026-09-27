# 🇧🇷 SolidSign API - Caso de Uso: eSocial — PHP

Este projeto demonstra a integração com a **SolidSign API** para assinar eventos do **eSocial** (S-1000, S-2200 e qualquer outro evento S-xxxx): uma assinatura XML única sobre o envelope inteiro, com o certificado e-CPF/e-CNPJ do empregador, via KMS SolidSign.

## Endpoint

`POST /api/esocial/sign-evento` — recebe o XML do evento (`document`) e o `kmsCode` do empregador, retorna o XML assinado.

## Parâmetros de assinatura (fixos, iguais pra qualquer evento)

`profile=XML_INTERNATIONAL`, `signaturePackaging=ENVELOPED`, `hashAlgorithm=SHA256`, `canonicalizationMethod=INCLUSIVE`, `isRemoveXPathExclusionFilter=true`, `isRemoveNamespacePrefixFromNodeNames=true`.

## Stack
1. PHP 8.1+
2. Slim Framework + Guzzle

## Como Executar

```bash
composer install
cp .env.example .env   # edite com seu token
php -S 0.0.0.0:8104 -t public
```

```
curl -X POST http://localhost:8104/api/esocial/sign-evento \
  -F "document=@evento-s1000.xml" -F "kmsCode=$KMS_EMPREGADOR" \
  -o evento-s1000-assinado.xml
```

## Outros métodos de certificação

Para HSM em nuvem ou navegador (PKCS#1), use os mesmos parâmetros nos exemplos genéricos [`exemplo-php-integracao-xml-cloud`](https://github.com/SolidTechSolutions/exemplo-php-integracao-xml-cloud) e [`exemplo-php-integracao-xml-pkcs1`](https://github.com/SolidTechSolutions/exemplo-php-integracao-xml-pkcs1).

## Tratamento de Erros
O sistema loga o JSON detalhado de erro da SolidSign.

---

# 🇬🇧 SolidSign API - Use Case: eSocial — PHP

This project demonstrates integrating with the **SolidSign API** to sign **eSocial** events (S-1000, S-2200, and any other S-xxxx event): a single XML signature over the entire envelope, with the employer's e-CPF/e-CNPJ certificate, via SolidSign's KMS.

## Endpoint

`POST /api/esocial/sign-evento` — takes the event's XML (`document`) and the employer's `kmsCode`, returns the signed XML.

## Signature parameters (fixed, the same for any event)

`profile=XML_INTERNATIONAL`, `signaturePackaging=ENVELOPED`, `hashAlgorithm=SHA256`, `canonicalizationMethod=INCLUSIVE`, `isRemoveXPathExclusionFilter=true`, `isRemoveNamespacePrefixFromNodeNames=true`.

## Stack
1. PHP 8.1+
2. Slim Framework + Guzzle

## How to Run

```bash
composer install
cp .env.example .env
php -S 0.0.0.0:8104 -t public
```

## Other certification methods

For cloud HSM or browser (PKCS#1) signing, apply the same parameters to [`exemplo-php-integracao-xml-cloud`](https://github.com/SolidTechSolutions/exemplo-php-integracao-xml-cloud) and [`exemplo-php-integracao-xml-pkcs1`](https://github.com/SolidTechSolutions/exemplo-php-integracao-xml-pkcs1).

## Error Handling
The system logs SolidSign's detailed error JSON.

---

# 🇪🇸 SolidSign API - Caso de Uso: eSocial — PHP

Este proyecto demuestra la integración con la **SolidSign API** para firmar eventos del **eSocial** (S-1000, S-2200 y cualquier otro evento S-xxxx): una única firma XML sobre el envelope entero, con el certificado e-CPF/e-CNPJ del empleador, vía KMS.

## Endpoint

`POST /api/esocial/sign-evento` — recibe el XML del evento (`document`) y el `kmsCode` del empleador, devuelve el XML firmado.

## Parámetros de firma (fijos, iguales para cualquier evento)

`profile=XML_INTERNATIONAL`, `signaturePackaging=ENVELOPED`, `hashAlgorithm=SHA256`, `canonicalizationMethod=INCLUSIVE`, `isRemoveXPathExclusionFilter=true`, `isRemoveNamespacePrefixFromNodeNames=true`.

## Stack
1. PHP 8.1+
2. Slim Framework + Guzzle

## Cómo Ejecutar

```bash
composer install
cp .env.example .env
php -S 0.0.0.0:8104 -t public
```

## Otros métodos de certificación

Para HSM en la nube o navegador (PKCS#1), aplique los mismos parámetros a [`exemplo-php-integracao-xml-cloud`](https://github.com/SolidTechSolutions/exemplo-php-integracao-xml-cloud) y [`exemplo-php-integracao-xml-pkcs1`](https://github.com/SolidTechSolutions/exemplo-php-integracao-xml-pkcs1).

## Gestión de Errores
El sistema registra el JSON detallado de errores de SolidSign.
