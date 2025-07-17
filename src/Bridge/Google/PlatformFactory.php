<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\Platform\Bridge\Google;

use Symfony\AI\Platform\Bridge\Google\Contract\GoogleContract;
use Symfony\AI\Platform\Bridge\Google\Embeddings\ModelClient as EmbeddingsModelClient;
use Symfony\AI\Platform\Bridge\Google\Embeddings\ResponseConverter as EmbeddingsResponseConverter;
use Symfony\AI\Platform\Bridge\Google\Gemini\ModelClient as GeminiModelClient;
use Symfony\AI\Platform\Bridge\Google\Gemini\ResponseConverter as GeminiResponseConverter;
use Symfony\AI\Platform\Contract;
use Symfony\AI\Platform\Platform;
use Symfony\Component\HttpClient\EventSourceHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @author Roy Garrido
 */
final readonly class PlatformFactory
{
    public static function create(
        #[\SensitiveParameter]
        string $apiKey,
        ?HttpClientInterface $httpClient = null,
        ?Contract $contract = null,
    ): Platform {
        $httpClient = $httpClient instanceof EventSourceHttpClient ? $httpClient : new EventSourceHttpClient($httpClient);

        return new Platform(
            [new EmbeddingsModelClient($httpClient, $apiKey), new GeminiModelClient($httpClient, $apiKey)],
            [new EmbeddingsResponseConverter(), new GeminiResponseConverter()],
            $contract ?? GoogleContract::create(),
        );
    }
}
