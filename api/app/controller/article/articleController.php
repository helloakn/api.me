<?php 
/**
 * Developed By AKN 
 * Created Date 5-Feb-2022
 * Updated Date 5-Feb-2022
 * use from : route { localhosts/wwww/home}
 */

namespace Controller\article;

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

class articleController {
    function detail(Request $request,$parameter){
        $article_link = $parameter->id;

        $categories = Category::getList();

        $cmdStringDetail = <<<QUERY
        SELECT 
            Article.id, Article.title,Article.link, Article.image, Article.intro,Article.description,Article.created_at,
            Author.name as author_name, Author.profile_image as author_profile_image,
            CONCAT('[',
                GROUP_CONCAT('{',
                    '"id":"',C.id,'",'
                    '"name":"',C.name,'"'
                ,'}')
            ,']') categories
        FROM Article
        INNER    JOIN Author
                On Article.author_id = Author.id
        INNER    JOIN ArticleCategory AC 
                On Article.id = AC.article_id
        INNER    JOIN Category C 
                On AC.category_id = C.id
        WHERE
            Article.deleted_at IS NULL AND
            AC.deleted_at IS NULL AND
            C.deleted_at IS NULL AND

            Article.link = '$article_link'
                  
        GROUP BY Article.id, Article.title, Article.image, Article.intro,
        author_name,author_profile_image
        
QUERY;

        $result =  Database::fetchAllQuery($cmdStringDetail);
        

        if($result){
            $item =  new \stdClass();
            $item = (object)$result[0];
            $item->categories = json_decode($item->categories);
            
/* Start article Detail */
        $cmdStringarticleDetail = <<<QUERY
            SELECT 
                AD.id,AD.type,AD.title,AD.description,AD.asc_index
            FROM Article
            INNER   JOIN ArticleDetail AD 
                    On Article.id = AD.article_id
            WHERE
                Article.deleted_at IS NULL AND
                AD.deleted_at IS NULL AND
                Article.link = '$article_link'
            ORDER BY AD.asc_index ASC
QUERY;

        $resultArticleDetail =  Database::executeQueryPaginate($cmdStringarticleDetail,1,2000);
        $articleDetail = [];
        foreach($resultArticleDetail->data as $k=>$v){
            $articleDetail[] = $v;
        }
/* End article Detail */

/* Start Related articles */
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
                ORDER BY Article.id DESC
QUERY;

        $result =  Database::executeQueryPaginate($cmdStringLatestArticles,1,4);
        $latestArticles = [];
        foreach($result->data as $k=>$v){
            $v['categories'] = json_decode($v['categories']);
            $latestArticles[] = $v;
        }
/* End Related articles */

            $data = array(
                "code"      => 200,
                "status"    => "success",
                "message"   => "success",
                "data"      =>  array(
                    "article" => $item,
                    "articleDetail" => $articleDetail,
                    "category" => $categories,
                    "latestArticles"    => $latestArticles,
                    
                )
            );
            return $data;
        }
        else{
            $data = array(
                "code"      => 300,
                "status"    => "failed",
                "message"   => "there is no data",
                "data"      => array(
                    "detail" => null,
                    "category" => $categories
                    
                )
            );
            return $data;
        }
        
           
        
    }
    
}

?>