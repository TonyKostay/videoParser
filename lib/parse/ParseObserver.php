<?php
namespace Parse;
/*
 *The class is designed to extend the methods of working with video hosting.
 */

class ParseObserver extends Service {

    public function get_content(string $url): ?array {
        try {
            $url = htmlspecialchars($url);
            if ($this->isYouTube($url)) {
                $responseBody = $this->requestToYouTube($url);
                if (!$responseBody) {
                    return null;
                }

                return $this->parseResponseXml($responseBody);
            }
            if ($this->isVimeo($url)) {
                $responseBody = $this->requestToVimeo($url);
                if (!$responseBody) {
                    return null;
                }

                return $this->parseResponseXml($responseBody);
            }
        } catch (ServiceException $e) {
            return array('error' => $e->getMessage());
        }
        return null;
    }
}