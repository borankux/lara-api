<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;

class YamlToJson extends Command
{
    const API_DOC_YAML = 'htdocs/main.yaml';
    const API_DOC_JSON = 'database/schemas/main.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'utils:yamltojson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Conver htdocs/main.yaml to database/schema/main.json';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 随便一写的，慢慢改好点，改通用一点吧。
        $apiDocs = Yaml::parse(file_get_contents(base_path(self::API_DOC_YAML)));
        $jsonStr = json_encode($apiDocs);
        $jsonFile = base_path(self::API_DOC_JSON);
        $handle = fopen($jsonFile, 'w') or die('Cannot open file:  '.$jsonFile);
        fwrite($handle, $jsonStr);
        fwrite($handle, PHP_EOL);
        fclose($handle);
    }
}
