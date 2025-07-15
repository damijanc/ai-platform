<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\Platform\Bridge\Google\Embeddings;

use Symfony\AI\Platform\Bridge\Google\Embeddings;
use Symfony\AI\Platform\Exception\RuntimeException;
use Symfony\AI\Platform\Model;
use Symfony\AI\Platform\Response\VectorResponse;
use Symfony\AI\Platform\ResponseConverterInterface;
use Symfony\AI\Platform\Vector\Vector;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @author Valtteri R <valtzu@gmail.com>
 */
final readonly class ResponseConverter implements ResponseConverterInterface
{
    public function supports(Model $model): bool
    {
        return $model instanceof Embeddings;
    }

    public function convert(ResponseInterface $response, array $options = []): VectorResponse
    {
        $data = $response->toArray();

        if (!isset($data['embeddings'])) {
            throw new RuntimeException('Response does not contain data');
        }

        return new VectorResponse(
            ...array_map(
                static fn (array $item): Vector => new Vector($item['values']),
                $data['embeddings'],
            ),
        );
    }
}
