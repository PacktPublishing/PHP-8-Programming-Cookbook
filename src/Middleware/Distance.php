<?php
namespace Cookbook\Middleware;
use RuntimeException;
use InvalidArgumentException;
use Cookbook\Middleware\BaseHandler;
use Cookbook\Middleware\Traits\VerifyIso2Trait;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
class Distance extends BaseHandler
{
    use VerifyIso2Trait;
    public const DISTANCE_UNITS = ['km','miles'];
    public const USAGE = 'USAGE: '
        . 'city_from : City to start from,'
        . 'city_to : Destination city, '
        . 'iso2_from : ISO2 code of from country, '
        . 'iso2_to   : ISO2 code of destination country, '
        . 'units     : km | miles (default km)';
    #[Cookbook\Middleware\Distance\handle(
        "@param GenAiConnect connect : GenAiConnect instance",
        "@param string city_from : City to start from",
        "@param string city_to   : Destination city",
        "@param string iso2_from : ISO2 code of from country",
        "@param string iso2_to   : ISO2 code of destination country",
        "@param string units     : km | miles",
        "@return string response : Translated phrase",
        "@throws InvalidArgumentException"
    )]    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $data = $request->getParsedBody()['data'] ?? '';
        $args = json_decode($data, associative: TRUE, flags: JSON_THROW_ON_ERROR);
        $city_from = $args['city_from'] ?? '';
        $city_to  = $args['city_to'] ?? '';
        $iso2_from = $args['iso2_from'] ?? 'Unknown';
        $iso2_to  = $args['iso2_to'] ?? 'Unknown';
        $units = $args['units'] ?? 'km';
        if (empty($city_from)) {
            throw new InvalidArgumentException('SOURCE CITY MISSING: ' . self::USAGE);
        }
        if (empty($city_to)) {
            throw new InvalidArgumentException('DESTINATION CITY MISSING: ' . self::USAGE);
        }
        if (!$this->verify_iso2($iso2_from)) {
            throw new InvalidArgumentException("INVALID SOURCE ISO2 CODE: $iso2_from: " . self::USAGE);
        }
        if (!$this->verify_iso2($iso2_to)) {
            throw new InvalidArgumentException("INVALID DESTINATION ISO2 CODE: $iso2_to: " . self::USAGE);
        }
        if (!in_array($units, static::DISTANCE_UNITS)) {
            throw new InvalidArgumentException('UNIT MUST BE ONE OF: ' . implode(',', static::DISTANCE_UNITS) . ' ' . self::USAGE);
        }
        $connect = $this->container->get('GenAiConnect');
        if (empty($connect)) {
            throw new RuntimeException('Required service is offline. ' . self::USAGE);
        }
        $prompt = "Give me the distance in $units "
                . "from: $city_from, $iso2_from, "
                . "to: $city_to, $iso2_to. "
                . 'Return only the distance figure in km or miles as given with no additional explanation or text.';
        return (new JsonResponse($connect->genAIcall($prompt)))->withStatus(200);
    }
}
