<?php


class JsonTranslator
{
    const EN = "en";
    const DE = "de";
    const ES = "es";
    const JP = "ja";
    const RU = "ru";
    const CN = "zh";
    const IN = "in";
    const FR = "fr";
    const IT = "it";
    const EL = "el";

    public $language = NULL;
    private $supported = array("en","es","ca","gl","eu","de","ja","ru","zh","in","fr","it","el");

    public function __construct($lang = NULL)
    {
        if(!empty($lang))
            $this->language = $lang;
        else
        {
            //error_log("HTTP_ACCEPT_LANGUAGE language is ".$_SERVER['HTTP_ACCEPT_LANGUAGE']);

            if(isset($_SERVER['HTTP_REDIRECTED_ACCEPT_LANGUAGE'])) {
                $lang = $this->prefered_language($this->supported,$_SERVER['HTTP_REDIRECTED_ACCEPT_LANGUAGE'] );
            } else {
                $lang = $this->prefered_language($this->supported);
            }

            $lang = $this->junaioWorkaround($lang);
            $_SERVER['HTTP_ACCEPT_LANGUAGE'] = $lang;

            if(stripos($lang, "en") !== FALSE)
                $this->language = JsonTranslator::EN;
            else if(stripos($lang, "de") !== FALSE)
                $this->language = JsonTranslator::DE;
            else if(stripos($lang, "ja") !== FALSE || stripos($lang, "jp") !== FALSE)
                $this->language = JsonTranslator::JP;
            else if(stripos($lang, "es") !== FALSE || stripos($lang, "ca") !== FALSE || stripos($lang, "eu") !== FALSE || stripos($lang, "gl") !== FALSE)//spanish, catalan, basque and gallego
            $this->language = JsonTranslator::ES;
            else if(stripos($lang, "ru") !== FALSE)
                $this->language = JsonTranslator::RU;
            else if(stripos($lang, "zh") !== FALSE)
                $this->language = JsonTranslator::CN;
            else if(stripos($lang, "id") !== FALSE || stripos($lang, "in") !== FALSE)
                $this->language = JsonTranslator::IN;
            else if(stripos($lang, "fr") !== FALSE)
                $this->language = JsonTranslator::FR;
            else if(stripos($lang, "it") !== FALSE)
                $this->language = JsonTranslator::IT;
            else if (stripos($lang, "el") !== FALSE)
                $this->language = JsonTranslator::EL;
            else
                $this->language = JsonTranslator::EN;
        }
    }

    public function getLanguagePack($filePath)
    {
        $data = file_get_contents($filePath);
        $json = json_decode($data, true);

        $languageArray = array();
        if($json != null)
        {
            $languageArray = $json[$this->language];
            if(!$languageArray)
            {
                //if language is not found then use the first one
                $languageArray = $json["en"];
            }
        }
        return $languageArray;
    }

    public function getDetectedLanguage() {
        return $this->language;
    }

    /*
  determine which language out of an available set the user prefers most

  $available_languages        array with language-tag-strings (must be lowercase) that are available
  $http_accept_language    a HTTP_ACCEPT_LANGUAGE string (read from $_SERVER['HTTP_ACCEPT_LANGUAGE'] if left out)
*/
    function prefered_language ($available_languages,$http_accept_language="auto") {
        // if $http_accept_language was left out, read it from the HTTP-Header
        if ($http_accept_language == "auto") $http_accept_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';

        // standard  for HTTP_ACCEPT_LANGUAGE is defined under
        // http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.4
        // pattern to find is therefore something like this:
        //    1#( language-range [ ";" "q" "=" qvalue ] )
        // where:
        //    language-range  = ( ( 1*8ALPHA *( "-" 1*8ALPHA ) ) | "*" )
        //    qvalue         = ( "0" [ "." 0*3DIGIT ] )
        //            | ( "1" [ "." 0*3("0") ] )
        preg_match_all("/([[:alpha:]]{1,8})(-([[:alpha:]|-]{1,8}))?" .
            "(\s*;\s*q\s*=\s*(1\.0{0,3}|0\.\d{0,3}))?\s*(,|$)/i",
            $http_accept_language, $hits, PREG_SET_ORDER);

        // default language (in case of no hits) is the first in the array
        $bestlang = $available_languages[0];
        $bestqval = 0;

        foreach ($hits as $arr) {
            // read data from the array of this hit
            $langprefix = strtolower ($arr[1]);
            if (!empty($arr[3])) {
                $langrange = strtolower ($arr[3]);
                $language = $langprefix . "-" . $langrange;
            }
            else $language = $langprefix;
            $qvalue = 1.0;
            if (!empty($arr[5])) $qvalue = floatval($arr[5]);

            // find q-maximal language
            if (in_array($language,$available_languages) && ($qvalue > $bestqval)) {
                $bestlang = $language;
                $bestqval = $qvalue;
            }
            // if no direct hit, try the prefix only but decrease q-value by 10% (as http_negotiate_language does)
            else if (in_array($langprefix,$available_languages) && (($qvalue*0.9) > $bestqval)) {
                $bestlang = $langprefix;
                $bestqval = $qvalue*0.9;
            }
        }
        return $bestlang;
    }

    /**
     * This method is used to retrieve the correct language on android clients prior to 4.7.4 where the language name was sent instead of ISO code
     * @param $lang
     */
    function junaioWorkaround($lang) {
        if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            return "en";
        }
        $http_accept_language = strtolower(trim($_SERVER['HTTP_ACCEPT_LANGUAGE']));

        if(strlen($http_accept_language) > 0 && $lang == "en") {
            if(($http_accept_language == "español")  || ($http_accept_language == "galego") || ($http_accept_language == "euskara") || ($http_accept_language == "català")) {
                $lang = "es";
            } else if ($http_accept_language == "italiano") {
                $lang = "it";
            } else if ($http_accept_language == "日本人" || $http_accept_language == "日本語") {
                $lang = "jp";
            } else if ($http_accept_language == "deutsch") {
                $lang = "de";
            } else if ($http_accept_language == "русский") {
                $lang = "ru";
            } else if ($http_accept_language == "中国的" ||$http_accept_language == "中文") {
                $lang = "zh";
            } else if ($http_accept_language == "français") {
                $lang = "fr";
            } else if ($http_accept_language == "Ελληνικά") {
                $lang = "el";
            } else {
                //leave it as it is
            }
        }

        return $lang;

    }

}

