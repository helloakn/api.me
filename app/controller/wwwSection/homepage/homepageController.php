<?php 
/**
 * Developed By AKN 
 * Created Date 5-Feb-2022
 * Updated Date 5-Feb-2022
 * use from : route { localhosts/wwww/home}
 */

namespace Controller\WwwSection\homepage;

use API\providers\Request;
use API\providers\S3;
use API\Hash;
use API\providers\Validator;
use API\Schema\Database;
use API\Auth;


use Extenstions\Helper;
use API\providers\Env;

/**
 * Model */
use Model\Category;
use Model\MobileApp;
use Model\Article;

class homepageController {
    function list(Request $request){

        $categories = Category::getList();

        $mobileAppLists = MobileApp::getAllMobileApp();
       
        $latestArticles = null;

        $cmdStringLatestArticles = <<<QUERY
        SELECT 
            Article.id, Article.title,Article.link, Article.image, Article.intro,Article.created_at,
            CONCAT('[',
                GROUP_CONCAT('{',
                    '"id":"',C.id,'",'
                    '"name":"',C.name,'"'
                ,'}')
            ,']') categories
        FROM Article
        INNER    JOIN ArticleCategory AC 
                On Article.id = AC.article_id
        INNER    JOIN Category C 
                On AC.category_id = C.id
        WHERE
            Article.deleted_at IS NULL AND
            AC.deleted_at IS NULL AND
            C.deleted_at IS NULL
        GROUP BY Article.id, Article.title, Article.image, Article.intro
        ORDER BY RAND()
QUERY;

       // $result =  Database::executeQueryPaginate($cmdStringLatestArticles,1,6);
       $result =  Database::executeQueryPaginate($cmdStringLatestArticles,1,6000);
        $latestArticles = [];
        //return $result;exit;
        foreach($result->data as $k=>$v){
            
           // return json_decode($v['categories']);
            $v['categories'] = json_decode($v['categories']);
            $latestArticles[] = $v;
        }

        $data = array(
            "code"      => 200,
            "status"    => "success",
            "message"   => "success",
            "data"      => array(
                "category"          => $categories,
                "latestArticles"    => $latestArticles,
                "mobileAppLists"    =>$mobileAppLists
            )
        );
        return $data;
           
        
    }
    
}

?>