<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/6/15
 * Time: 下午11:47
 */

namespace App\Lib\ES;

use Elasticsearch\ClientBuilder;

use EasySwoole\Core\AbstractInterface\Singleton;

class ElasticSearch
{
    use Singleton;

    protected $esClient;

    private function __construct()
    {
        $esConf = \Yaconf::get('es.host');
        $builder = ClientBuilder::create();
        try{
            $this->esClient = $builder->setHosts([$esConf[1]])->build();
        }catch (\Exception $ex){
            throw new \Exception($ex->getMessage());
        }

        if(empty($this->esClient))
        {
            //TODO
        }
    }

    public function __call($name, $arguments)
    {
        return $this->esClient->$name(...$arguments);
    }

}