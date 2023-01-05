# MaeCMS-Loader
place MaeCMS-Loader.php in the root folder, where you want to install Mae CMS and open it with your browser.
The loader will first check system requirements and then download and unpack the zip archive with the latest Mae CMS version.
Afterwards you will be provided with a link to the installation script where you can define database connection and so on.

## Changes:
V1.1: added a confirmation link, before starting to install  
V1.2: added checks if ZipArchive extension is installed and file write permissions are given.  
V1.3: added check for intl extension (date formats).  