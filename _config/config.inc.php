<?php
// Set the project run mode
$shipCalcEnv = 'dev';
global $root;
if ($shipCalcEnv == 'dev') {
    // Path where the .ENV file with all the good stuff resides
    $envPath = 'C:/usr/Apache24/cgi-bin/shipcalc/';
    // URL for the domain - no trailing slash
    define('SITE_ROOT', 'http://shipcalc.jdac.ddns.net');
} else {
    // Path where the .ENV file with all the good stuff resides
    $envPath = '__DIR__' . '/../../';
    // URL for the domain - no trailing slash
    define('SITE_ROOT', 'http://shipcalc.jdac.ddns.net');
}
require( $root . 'vendor/autoload.php');
$dotenv = new Dotenv\Dotenv($envPath);
$dotenv->load();

// File system path for the root folder of the domain - no trailing slash
define('WEB_ROOT','/source/web/shipcalc');
// File system path for the project dir. No trailing slash
define('PROJECT_DIR','');
// Alias for SITE_ROOT
define('SITE_URL', SITE_ROOT);
// Path to Project URL - No Trailing Slashes
define('PROJECT_URL', '');

// Where should the "Home" link in the navigation go. Can be set to a temporary page for development.
define('HOME_LINK','http://shipcalc.jdac.ddns.net/');

// Full path to folder that will hold uploaded media. Must be writable by the server user account. Should be outside web root if possible (no trailing slash)
// define('UPLOAD_DIR',WEB_ROOT.PROJECT_DIR.'/_uploads');

// Path to (virtual?) folder that will hold uploaded media from the base URL of the domain (no  trailing slash)
// define('UPLOAD_URL',SITE_ROOT.'/_user_media');

// Title used throughout project
define('PROJECT_TITLE','Instant Vehicle Shipping Cost Estimates');

// Short Title used in E-mail subjects, HTML title tags and elsewhere
define('PROJECT_TITLE_SHORT','Ship-Calc');

// E-mail addresses for server admin or primary contact
define('EMAIL_ADMIN','john@jdacsolutions.com');
define('EMAIL_CONTACT', 'john@jdacsolutions.com');
define('EMAIL_LEADS_NOTIFICATION', 'john@jdacsolutions.com');

// E-mail header separator. Usually CRLF (\r\n), but some poor quality Unix mail transfer agents replace LF by CRLF automatically (which leads to doubling CR if CRLF is used). 
// If messages are not received, try using a LF (\n) only. 
define('EMAIL_SEPARATOR',"\n");

// Set to TRUE on the production server
define('ARE_WE_LIVE', false);
        
# Unique session name. Can be altered if it conflicts with other server applications
define('SESSION_NAME','SHIPCALC_QUOTES');
# Two unique strings that will be used in auto-redirect and login processes
define('TOKEN_NAME',SESSION_NAME.'-token');
define('POSTBACK_PARAMETER_PREFIX','__postback__');

// Google Maps API Key used for this application - These should be unique for each web project and not resused in production
// environments.
// DEV Google Maps API Key
define('GOOGLE_MAPS_APIKEY', 'AIzaSyCK62oqRct1jmZ72wcYgLAkDr296MT8Oys');
// PROD Google Maps API Key
//define('GOOGLE_MAPS_APIKEY', '');

// Application-specific constants used in the estimation and carrier selection processes
define('CARRIER_DISPLAY_LIMIT', 4);
define('CARRIER_FOOTER_LIMIT', 2);
define('HEADER_IMAGES', 8);
const LOGO_WIDTH = array(200, 400, 600);
const LOGO_HEIGHT = array(108, 215,323);
define('LOGO_THUMBNAIL', 0);
define('LOGO_MED', 1);
define('LOGO_LG', 2);
$headerFilter = array(0,5,6,7,8);

// State names and abbreviations used in Lists
const STATES = array(
    'AL'=>'Alabama',
    'AK'=>'Alaska',
    'AZ'=>'Arizona',
    'AR'=>'Arkansas',
    'CA'=>'California',
    'CO'=>'Colorado',
    'CT'=>'Connecticut',
    'DE'=>'Delaware',
    'DC'=>'District of Columbia',
    'FL'=>'Florida',
    'GA'=>'Georgia',
    'HI'=>'Hawaii',
    'ID'=>'Idaho',
    'IL'=>'Illinois',
    'IN'=>'Indiana',
    'IA'=>'Iowa',
    'KS'=>'Kansas',
    'KY'=>'Kentucky',
    'LA'=>'Louisiana',
    'ME'=>'Maine',
    'MD'=>'Maryland',
    'MA'=>'Massachusetts',
    'MI'=>'Michigan',
    'MN'=>'Minnesota',
    'MS'=>'Mississippi',
    'MO'=>'Missouri',
    'MT'=>'Montana',
    'NE'=>'Nebraska',
    'NV'=>'Nevada',
    'NH'=>'New Hampshire',
    'NJ'=>'New Jersey',
    'NM'=>'New Mexico',
    'NY'=>'New York',
    'NC'=>'North Carolina',
    'ND'=>'North Dakota',
    'OH'=>'Ohio',
    'OK'=>'Oklahoma',
    'OR'=>'Oregon',
    'PA'=>'Pennsylvania',
    'RI'=>'Rhode Island',
    'SC'=>'South Carolina',
    'SD'=>'South Dakota',
    'TN'=>'Tennessee',
    'TX'=>'Texas',
    'UT'=>'Utah',
    'VT'=>'Vermont',
    'VA'=>'Virginia',
    'WA'=>'Washington',
    'WV'=>'West Virginia',
    'WI'=>'Wisconsin',
    'WY'=>'Wyoming',
);