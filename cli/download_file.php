<?php
require_once dirname(__FILE__, 2) . '/bootstrap.php';

class DownloadFile
{
    const PROGRAMS_XML ='http://www.onsen.ag/app/programs.xml';
    const DATA_ONSEN_DIR = DATA_DIR . '/onsen';
    const DOWNLOAD_ID_LIST = [
        'kamo',
    ];

    public function main ()
    {
        $ch = curl_init(self::PROGRAMS_XML);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result == false) {
            Logger::error('curl error.');
            return 1;
        }

        $result = json_decode(json_encode(
            simplexml_load_string($result)
        ), true);

        // download
        foreach ($this->getProgramList($result['program']) as $program) {
            $file_path = $this->getFilePath($program['@attributes']['id'], $program['program_number']);
            if (file_exists($file_path)) {
                Logger::debug('File is Already downloaded: ' . $file_path);
                continue;
            }
            $handle = fopen($file_path, 'w+');
            $ch = curl_init($program['movie_url']);
            curl_setopt($ch, CURLOPT_FILE, $handle);
            curl_exec($ch);
        }
    }

    private function getFilePath($program_id, $program_number)
    {
        $dir = self::DATA_ONSEN_DIR . '/' . $program_id;
        file_exists($dir) || mkdir($dir);
        return $dir . '/' . $program_number . '.mp3';
    }

    private function getProgramList($program_list)
    {
        foreach ($program_list as $program) {
            if (in_array($program['@attributes']['id'], self::DOWNLOAD_ID_LIST)) {
                yield $program;
            }
        }
    }
}

$download_file = new DownloadFile();
exit($download_file->main());
