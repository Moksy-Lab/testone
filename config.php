<?php 

class MoksyApp {
    private $jsonFilePath;
    private $data;
    private $defaultJsonFilePath = '/var/www/moksy.com/public_html/templates/sarah/project.json'; // Default JSON path


    public function __construct(string $jsonFilePath) {
        $this->jsonFilePath = $jsonFilePath;
        $this->initialize();
    }


    private function initialize() {
        if (!file_exists($this->jsonFilePath)) {
            // Fallback to the default JSON file if the specified file doesn't exist
            $this->jsonFilePath = $this->defaultJsonFilePath;
            
            if (!file_exists($this->jsonFilePath)) {
                // If the default file also doesn't exist, terminate with an error message
                die("Error: Default configuration file $this->jsonFilePath doesn't exist.");
            }
        }

        $jsonString = file_get_contents($this->jsonFilePath);
        if ($jsonString === false) {
            die("Could not read file $this->jsonFilePath.");
        }

        $this->data = json_decode($jsonString, true);
        if ($this->data === null && json_last_error() !== JSON_ERROR_NONE) {
            die("Error reading JSON from $this->jsonFilePath: " . json_last_error_msg());
        }
    }

    private function getNestedValue(array $data, string $key) {
        if (isset($data[$key])) {
            return $data[$key];
        }
        foreach ($data as $value) {
            if (is_array($value)) {
                $found = $this->getNestedValue($value, $key);
                if ($found !== null) {
                    return $found;
                }
            }
        }
        return null;
    }

    public function getValue(string $key) {
        return $this->getNestedValue($this->data, $key);
    }

    public function getChildrenValues(string $key1, string $key2) {
        return $this->getNestedValue($this->data, $key1)[$key2] ?? null;
    }

/**
 * Link Generator.
 *
 * This method generates a link based on given parameters. The method provides a default value for each parameter in case they are not provided.
 * The purpose of the link is determined by the $purpose parameter.
 * For example, if the purpose is 'download', the url is modified and appended with '.html'.
 * The application name parameter is included in the generated link, but only when the purpose is not 'download'.
 * All user inputs are sanitized to prevent security risks.
 *
 * @param string $url      The URL of the link, defaults to 'home'.
 * @param string $appName  The application name, defaults to 'moksy'.
 * @param string $purpose  The purpose of the link, defaults to 'preview'.
 *
 * @return string  The generated link.
 */
public function linkGenerator(string $url = 'home', string $appName = 'moksy', string $purpose = 'preview'): string
{
    $url = trim(filter_var($url, FILTER_SANITIZE_URL));
    $appName = trim(filter_var($appName, FILTER_SANITIZE_STRING));
    $purpose = trim(filter_var($purpose, FILTER_SANITIZE_STRING));

    switch ($url) {
        case 'home':
            if ($purpose === 'download') {
                $url = 'index';
            }
            break;
        case 'default':
            $url = '#';
            break;
    }

    return $purpose === 'download' ? $url . '.html' : '/templates/' . $appName . '/?page=' . $url;
}


     /**
     * Fetches data from the stored data object using provided keys.
     *
     * @param string $parentKey The parent key in the data object
     * @param string $key The key within the parent key
     * @param string $subKey The sub key within the key, where the final value is stored
     * @return mixed|null The value from the data object if found, or null if not found
     */
    public function getChildData($parentKey, $key, $subKey)
    {
        if (isset($this->data[$parentKey][$key]) && is_array($this->data[$parentKey][$key]) && isset($this->data[$parentKey][$key][$subKey])) {
            return $this->data[$parentKey][$key][$subKey];
        }

        // Return null or some default value if the keys are not set
        return null;
    }


    public function fetchProjectMedia($projectName, $outputType) {
        $url = "https://builder.moksy.com/wp-json/wp/v2/media/{$projectName}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if (!$data) {
            return null;
        }

        // Building the desired key
        $key = "{$projectName}-{$outputType}";
        if (isset($data[$key])) {
            return $data[$key];
        }

        return null;
    }

}

$currentPage = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);
$currentPage = !empty($currentPage) ? $currentPage : 'home.html';
$page = str_replace('.html', '', $currentPage);
$page = explode(".html", $currentPage);
$page = $page[0];

$jsonFilePath = 'project.json';
$app = new MoksyApp($jsonFilePath);
$author = 'Adam Levine';
$team = 'GGLink';
$slug = $page;
$projectID = $app->getValue('APP_ID');
$languages = 'en';
$purpose = isset($_GET['purpose']) && !empty($_GET['purpose']) ? $_GET['purpose'] : '';
$baseLink = 'https://moksy.com';
$appName = $app->getValue('APP_NAME');
$projectNickname = $appName;
$metaDescription = $app->getValue('meta_description');
$pageTitle = $app->getValue('meta_title');
$metaTitle = $app->getValue('meta_title');
$metaKeywords = $app->getValue('meta_keywords');
$socialImage = 'templates/'.$appName.'/assets/images/branding/logo.png'; //$app->getValue('APP_COVER');
$metaImage = 'templates/'.$appName.'/assets/images/branding/logo.png'; //$app->getValue('APP_COVER');
$facebookid = '';
$originallogo = $app->fetchProjectMedia($appName,'logo');
$logo = 'templates/'.$appName.'/assets/images/branding/logo.png';//$originallogo ?? $app->getValue('APP_LOGO_ONE');
$favicon = 'templates/'.$appName.'/assets/images/branding/favicon.png'; //$app->getValue('APP_FAVICON_ONE');
$touchIcon = $app->getValue('APP_TOUCH_ICON_ONE');
$styles = $app->getValue('projectStyles');
$scripts = $app->getValue('projectScripts');
$brandPrimaryColor = $app->getValue('brandPrimaryColor');
$primaryFont = $app->getValue('primaryFont');
$secondarFont = $app->getValue('secondarFont');
$thirdPrimaryFont = $app->getValue('thirdPrimaryFont');
$defaultFont = $app->getValue('defaultFont');
$fontsList = $primaryFont. '|' .$secondarFont. '|' .$thirdPrimaryFont. '|' .$defaultFont;
$rating = $app->getValue('rating');
$reviews = $app->getValue('total_reviews');
$publishDate = $app->getValue('APP_DATE');
$baseDir = '/var/www/moksy.com/codepot/snippets/modules/cms/';
$previewBaseDir = '/var/www/moksy.com/public_html/includes/preview/';
$key2 = 'snippets';
$pageBuild = $app->getChildrenValues($page, $key2);