<?php
include ("Singleton.php");

class Translator extends Singleton
{
    /**
     * @var string
     */
    const LANG_FILES = 'LANG_FILES_ARR';

    /**
     * @var Translator
     */
    protected static $instance = null;

    /**
     * @var Translations storage
     */
    protected $translations = array();

    /**
     * Translate a single key, giving params and a plural number
     * @param string $key
     * @param array $params (optional) default array
     * @param integer $pluralNumber
     * @return string
     */
    public function translate($key, array $params = array(), $pluralNumber = 0)
    {
        // setup translations array if not already in place
        if (empty($this->translations)) {
            $this->getTranslations();
        }

        // If there is no match, return the requested key
        if (!array_key_exists($key, $this->translations)) {
            return $key;
        }

        // Identify language and fallback here.
        $value = $this->translations[$key];
        $value = $this->getPluralFromNumber($value, $pluralNumber);
        $keys = array_keys($params);
        $vals = array_values($params);

        foreach ($keys as $position => $keyValue) {
            $keys[$position] = ':' . $keyValue . ':';
        }

        $value = str_replace($keys, $vals, $value);
        return $value;
    }

    /**
     * Gets the translations for the language specified in the cookie
     * Merges with the results with the default language to minimise change of having no matches.
     *
     * @throws \Exception
     * @return array
     */
    private function getTranslations()
    {
        $rawPath = '/';
        //$rawPath = APPLICATION_PATH . '/../app/resources/translations';
        $path = realpath($rawPath);
        if (!$path) {
            throw new \Exception('Invalid directory for translations. Expected: "' . $rawPath . '"');
        }

        /*
         * Example of managing files by language:
         *
         * $language = \FrameworkOrApplication->getLang();
         *
         * // Always get english translations first (as a fallback)
         *     if (file_exists("{$path}/english.php")) {
         *         $this->translations = @include "{$path}/english.php";
         *     }
         */

        if (file_exists("translations.php")) {
            $this->translations = @include "translations.php";
        }

        /*
         * If we have other languages, merge with the default to ensure there is a full set
         *
         *     if (file_exists("{$path}/{$language}.php")) {
         *         $this->translations = array_merge($this->translations, @include "{$path}/{$language}.php");
         *     }
         */

        return $this->translations;
    }

    /**
     * Get the translation value string if a plural is identified and work out which form is required
     * @param string $string
     * @param integer $number
     * @return string
     */
    public function getPluralFromNumber($string, $number)
    {
        /*
         * Check if the translation file array is pluralised
         * e.g. 'x_apples' => '{0} There are no apples||{1} There is one apple||{2,Inf} There are :count: apples'
         */
        if (preg_match('/^\{(\d+)(?:,\s?(\d+|Inf))?\}/i', $string) && is_numeric($number)) {

            $pluralPieces = explode('||', $string);
            // check each possible 'plural' format ('are no','is one','are x')
            foreach ($pluralPieces as $pluralPiece) {
                preg_match('/^\{(?P<lower>\d+)(?:,\s?(?P<upper>\d+|Inf))?\}/i', $pluralPiece, $matches);

                // compare the target values (e.g. '{1,1}') with the number parameter to check for match
                if (!empty($matches)) {

                    $lower = trim($matches['lower']);
                    if (isset($matches['upper'])) {
                        $upper = trim($matches['upper']);
                    } else {
                        $upper = trim($matches['lower']);
                    }

                    $match = strstr($pluralPiece, '}');
                    $match = trim($match, '}');
                    // cater for infinity (and beyond?)
                    if ($upper == 'Inf') {
                        if ($lower <= $number) {
                            $final = $match;
                            break;
                        }
                        // Compare value with upper and lower limit, if match found then tidy up and exit.
                    } elseif ($lower <= $number && $upper >= $number) {
                        $final = $match;
                        break;
                    } else {
                        // absolute failsafe
                        $final = $string;
                    }
                }
                unset($matches);
            }
        } else {
            // not pluralised, can just return the value as a whole
            $final = $string;
        }

        return $final;
    }

    /**
     * Gets all languages in the translations directory and returns their two char codes
     *
     * @return array
     */
    public static function getAvailableLanguages()
    {
        $availableLangs = array();
        foreach (static::getLanguageFiles() as $lang) {
            preg_match('/[A-z]{2}\.php/', $lang, $match);
            $availableLangs[] = (str_replace('.php', '', $match[0]));
        }

        return $availableLangs;
    }

    /**
     * Returns json encoded list of translations (for use outside of translator)
     * @return string json
     */
    public function getJsonEncodedTranslations()
    {

        if (empty($this->translations)) {
            $this->getTranslations();
        }

        return json_encode($this->translations);
    }

    /**
     * Retrieve a list of language files.
     * @return array
     */
    protected static function getLanguageFiles()
    {
        /**
         * Best to cache translations files.
         *
         *   $cache = FrameworkOrApplication::getInstance()->getCache();
         *   if ($cache->has(static::LANG_FILES)) {
         *        return $cache->get(static::LANG_FILES);
         *   }
         */

        $files = glob('../app/resources/translations/*.php');

        /*
         *   $cache->set(static::LANG_FILES, $files, 999999); // a long time
         */

        return $files;
    }
}
