<?php
/*
 * Vimeo API refuses to provide a response in JSON format, so I had to parse xml
 */
namespace Parse;

use Exception;
use SimpleXMLElement;

abstract class Service {

    public function isYouTube($videoUrl): bool {
        $urlData = parse_url($videoUrl);
        if ($urlData['host'] == 'www.youtube.com'){
            return true;
        }

        return false;
    }

    public function isVimeo($videoUrl): bool {
        $urlData = parse_url($videoUrl);
        if ($urlData['host'] == 'vimeo.com'){
            return true;
        }

        return false;
    }

    public function requestToYouTube($videoUrl): ?string {
        $videoUrl = urlencode($videoUrl);
        $responseBody = file_get_contents("https://www.youtube.com/oembed?url={$videoUrl}&format=xml");
        if (!$responseBody) {
            return null;
        }

        return $responseBody;
    }

    public function requestToVimeo(string $videoUrl): ?string {
        $videoUrl = urlencode($videoUrl);
        $responseBody = file_get_contents("https://vimeo.come/api/oembed.xml?url={$videoUrl}");
        if (!$responseBody) {
            return null;
        }

        return $responseBody;
    }

    public function parseResponseXml(string $xml): array{
        try{
            $xmlElement = new SimpleXMLElement($xml);
            return array
            (
                'provider_name' => (string)$xmlElement->provider_name,
                'provider_url'  => (string)$xmlElement->provider_url,
                'title'         => (string)$xmlElement->title,
                'author_name'   => (string)$xmlElement->author_name,
                'author_url'    => (string)$xmlElement->author_url,
                'account_type'  => (string)$xmlElement->account_type,
                'html'          => (string)$xmlElement->html,
                'video_id'      => (string)$xmlElement->video_id,
            );

        } catch (Exception $e){
            throw new ServiceException('Не удалось сформировать данные');
        }
    }
}